<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\PackDataModel;
use App\Models\UserModel;

class PackingController extends BaseController{
    public function __construct(){
        $this->packing = new PackDataModel();
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index(){
        return redirect()->route('pack/dashboard');
    }    

    public function dashboard(){
        $data['name'] = $this->session->get('name');
        return view('pack/header')
            .view('pack/sideTopBar',$data)
            .view('pack/index',$data)
            .view('pack/footer');
    }

    public function login(){
        return view('pack/header')
            .view('pack/login')
            .view('pack/footer');
    }

    public function loginAuth(){       
        if($this->request->isAJAX()){
            $batchNo = $this->request->getPost('batchNo');
            $password = $this->request->getPost('password');        
            $data = $this->userModel->where('batch_no', $batchNo)->first();            
            if($data){
                $pass = $data['password'];
                $authenticatePassword = password_verify($password, $pass);
                if($authenticatePassword){
                    $ses_data = [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'batch_no' => $data['batch_no'],
                        'role' => $data['role'],
                        'isLoggedIn' => TRUE
                    ];
                    $this->session->set($ses_data);
                    $data = 'ok';         
                }else{
                    $data = 'Password is incorrect.';
                }
            }else{
                $data = 'Batch No does not exist.';
            }
            echo json_encode(array("response"=>$data));
        }
    }    

    public function register(){
        return view('pack/header')
            .view('pack/register')
            .view('pack/footer');
    }

    public function store(){
        if($this->request->isAJAX()){
            $rules = [
                'name'            => 'required|min_length[2]|max_length[50]',
                'batchNo'         => 'required|min_length[4]|max_length[50]|is_unique[users.batch_no]',
                'role'            => 'required|min_length[4]|max_length[50]',
                'password'        => 'required|min_length[4]|max_length[50]',
                'confirmPassword' => 'required_with[password]|matches[password]'
            ];          
            if($this->validate($rules)){
                $data = [
                    'name'     => $this->request->getPost('name'),
                    'batch_no' => $this->request->getPost('batchNo'),
                    'role'     => $this->request->getPost('role'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
                ];
                $this->userModel->save($data);
                $data = 'ok';
            }else{
                $data = $this->validator->getErrors();
            }
            echo json_encode(array("response"=>$data));
        }
    }

    public function profile(){
        if($this->session->get('batch_no') != null){
            $data['batchNo'] = $this->session->get('batch_no');
            $data['name'] = $this->session->get('name');
            $data['role'] = $this->session->get('role');
            return view('pack/header')
                .view('pack/sideTopBar',$data)
                .view('pack/profile',$data)
                .view('pack/footer');
        }else{
            return redirect()->to('/pack/login');
        }
    }

    public function logout(){
        $this->session->destroy();
        return redirect()->to('/pack');
    }

    public function searchItems(){
        $data['custNo'] = $this->packing->getCustNo();
        $data['name'] = $this->session->get('name');
        return view('pack/header')
            .view('pack/sideTopBar',$data)
            .view('pack/searchItems',$data)
            .view('pack/footer');
    }

    public function searchData(){
        if($this->request->isAJAX()){
            $search['custNo'] = $this->request->getPost("custNo");            
            $search['boxId'] = $this->request->getPost("boxId");
            $search['lineNo'] = $this->request->getPost("lineNo");
            $search['date_strt'] = $this->request->getPost("date_strt");
            $search['date_end'] = $this->request->getPost("date_end");
            $search['packedBy'] = $this->request->getPost("packedBy");
            $search['shift'] = $this->request->getPost("shift");           
            $j=1;
            $getData = $this->packing->getSearchData($search);
            $data = [];
            if(!empty($getData)){
                for($i=0;$i<count($getData['cust_no']);$i++){
                    $nestedData['#'] = $j;
                    $nestedData['cust_no'] = $getData['cust_no'][$i];
                    $nestedData['box_id'] = $getData['box_id'][$i];
                    $nestedData['quantity_per_box'] = $getData['quantity_per_box'][$i];
                    $nestedData['gross_weight'] = $getData['gross_weight'][$i];
                    $nestedData['plant_id'] = $getData['plant_id'][$i];
                    $nestedData['line_no'] = $getData['line_no'][$i];
                    $nestedData['shift'] = $getData['shift'][$i];                    
                    $nestedData['packed_by'] = $getData['packed_by'][$i];
                    $nestedData['date_time'] = $getData['date_time'][$i];
                    $data[] = $nestedData;
                    $j++;
                }
            }
            $jsonData = array("data"=>$data);
            echo json_encode($jsonData);
        }
    }

    public function addModel(){
        if($this->session->get('batch_no') != null){
            $data['itemDetail'] = $this->packing->getItemDetail();
            $data['name'] = $this->session->get('name');
            return view('pack/header')
                .view('pack/sideTopBar',$data)
                .view('pack/addModel',$data)
                .view('pack/footer');
        }else{
            return redirect()->to('/pack/login');
        }        
    }

    public function sendModel(){
        if($this->request->isAJAX()){
            $path = "T:\SUC_Scanning\SUC_Scanning_System\Label_Packing\Charger Assembly Carton Label.lbl";
            $data['label_path'] = $path;
            $data['custNo'] = $this->request->getPost("custNo");
            $data['model'] = $this->request->getPost("model");
            $data['quantityBox'] = $this->request->getPost("quantityBox");
            $data['lineNo'] = $this->request->getPost("lineNo");
            $data['singleUnit'] = $this->request->getPost("singleUnit");
            $data['tolerance'] = $this->request->getPost("tolerance");
            $data['totalScan'] = $this->request->getPost("totalScan");         
            $data['itemDetail'] = $this->request->getPost("itemDetail");
            $data['itemDetailText'] = $this->request->getPost("itemDetailText");
            $data['itemScan'] = $this->request->getPost("itemScan");
            $saveData = $this->packing->addModel($data);
            $json = array("response"=>$saveData);
            echo json_encode($json);
        }
    }

    public function editModel(){
        if($this->session->get('batch_no') != null){
            $data['model'] = $this->packing->getModel();
            $data['itemDetail'] = $this->packing->getItemDetail();
            $data['name'] = $this->session->get('name');
            return view('pack/header')
                .view('pack/sideTopBar',$data)
                .view('pack/editModel',$data)
                .view('pack/footer');
        }else{
            return redirect()->to('/pack/login');
        }    
    }

    public function searchModel(){
        if($this->request->isAJAX()){
            $data['model'] = $this->request->getPost("model");
            $searchModel = $this->packing->searchByModel($data);
            $json = array("response"=>$searchModel);
            echo json_encode($json);
        }
    }

    public function saveEditModel(){
        if($this->request->isAJAX()){
            $data['custNo'] = $this->request->getPost("custNo");
            $data['model'] = $this->request->getPost("model");
            $data['quantityBox'] = $this->request->getPost("quantityBox");
            $data['lineNo'] = $this->request->getPost("lineNo");
            $data['singleUnit'] = $this->request->getPost("singleUnit");
            $data['tolerance'] = $this->request->getPost("tolerance");
            $data['totalScan'] = $this->request->getPost("totalScan");         
            $data['itemDetail'] = $this->request->getPost("itemDetail");
            $data['itemDetailText'] = $this->request->getPost("itemDetailText");
            $data['itemScan'] = $this->request->getPost("itemScan");
            $saveData = $this->packing->editModel($data);
            $json = array("response"=>$saveData);
            echo json_encode($json);
        }
    }
}