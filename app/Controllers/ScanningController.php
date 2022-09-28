<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\ScanningModel;
use App\Models\ImportCSVModel;

class ScanningController extends Controller {
    public function __construct(){
        $this->scanning = new ScanningModel();
        $this->importCSV = new ImportCSVModel();
        $request = \Config\Services::request();        
        // $this->dir = "C:/Motorola_Charger_TestLog/1_MUC_TestLog/CSV_Test_Log/TestMonitoring/"; //local
        $this->dir = "E:/Motorola_Charger_TestLog/1_MUC_TestLog/CSV_Test_Log/TestMonitoring/"; //server
    }

    public function index(){
        $current_date = date("Y-m-d");

        $data['totalTest'] = $this->importCSV
                                ->where("date(test_time) = '$current_date'")
                                ->countAllResults();
        $data['totalPass'] = $this->importCSV->where('result', 'Pass')
                                ->where("date(test_time) = '$current_date'")
                                ->countAllResults();

        $results = ['Fail', 'Abort'];
        $data['totalFail'] = $this->importCSV->whereIn('result', $results)
                                ->where("date(test_time) = '$current_date'")
                                ->countAllResults();        
       
        $yieldRate = 0;
        try {
            $yieldRate = ($data['totalPass']/$data['totalTest']) * 100;
        } catch (\Exception $e) {
            // echo($e->getMessage());
        }           
        $data['yieldRate'] = number_format($yieldRate,2);

        $data['getFtn'] = $this->scanning->getFailedTestName();
        $data['getModel'] = $this->scanning->getTestModel();

        if(!empty($data['getModel'])){
            for($i=0;$i<count($data['getModel']);$i++){
                $data['testTotalByModel'][] = $this->importCSV
                                                ->where('type',$data['getModel']['type'][$i])
                                                ->where("date(test_time) = '$current_date'")
                                                ->countAllResults();
                $data['testTotalPass'][] = $this->importCSV
                                            ->where('result', 'Pass')
                                            ->where('type',$data['getModel']['type'][$i])
                                            ->where("date(test_time) = '$current_date'")
                                            ->countAllResults();
                $data['testTotalFail'][] = $this->importCSV->whereIn('result', $results)
                                            ->where('type',$data['getModel']['type'][$i])
                                            ->where("date(test_time) = '$current_date'")
                                            ->countAllResults();
            }
        }
        return view('header')
            .view('sidebar')
            .view('index',$data)
            .view('footer');
    }

    public function importCsv(){
        return view('header')
            .view('sidebar')
            .view('importCSV')
            .view('footer');
    }

    public function importCsvToDb(){
        $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv],'
        ]);
        if (!$input) {
            $data['validation'] = $this->validator;
            return view('header')
                .view('sidebar')
                .view('index', $data)
                .view('footer'); 
        }else{
            if($file = $this->request->getFile('file')) {
                $fileName = $file->getName();
                if ($file->isValid() && ! $file->hasMoved()) {
                    $CSVfp = fopen($file,"r");
                    $i = 0;
                    $numberOfFields = 9;
                    $csvArr = array();
                    
                    while (($filedata[] = fgetcsv($CSVfp, 1000, ",")) !== FALSE) {
                        $num = count($filedata);
                        if($i > 0 && $num == $numberOfFields){
                            $csvArr[$i]['sn'] = substr($filedata[0][1],3);
                            $csvArr[$i]['type'] = substr($filedata[0][1],0,3);
                            $csvArr[$i]['unique_id'] = $filedata[0][1];
                            $csvArr[$i]['test_time'] = date('Y-m-d H:i:s',strtotime($filedata[1][1]));
                            $csvArr[$i]['total_time'] = $filedata[2][1];
                            $csvArr[$i]['process_name'] = $filedata[3][1];
                            $csvArr[$i]['operator_id'] = $filedata[4][1];
                            $csvArr[$i]['station_id'] = $filedata[5][1];
                            $csvArr[$i]['fixture'] = $filedata[6][1];
                            $csvArr[$i]['result'] = $filedata[7][1];
                            $csvArr[$i]['failed_test_name'] = $filedata[8][1];
                        }
                        $i++;
                    }
                    fclose($CSVfp);
                    $count = 0;
                    foreach($csvArr as $userdata){
                        $findRecord = $this->importCSV->where('unique_id', $userdata['unique_id'])->countAllResults();
                        if($findRecord == 0){
                            if($this->importCSV->insert($userdata)){
                                $count++;
                            }
                            session()->setFlashdata('message', $fileName.' file successfully added.');
                            session()->setFlashdata('alert-class', 'alert-success');
                        }else{
                            session()->setFlashdata('message', $fileName.' file already added.');
                            session()->setFlashdata('alert-class', 'alert-warning');
                        }
                    }
                }else{
                    session()->setFlashdata('message', 'CSV file coud not be imported.');
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }else{
                session()->setFlashdata('message', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->to(base_url().'/importCSV');     
    }

    public function searchItems(){
        $data['model'] = $this->scanning->getModels();
        $data['process_name'] = $this->scanning->getProcessName();
        $data['station_id'] = $this->scanning->getStationID();

        return view('header')
            .view('sidebar')
            .view('searchItems',$data)
            .view('footer');
    }

    public function searchItemsAjax(){
        if ($this->request->isAJAX()) {
            $unique_id = $this->request->getPost("unique_id");
            $files = scandir($this->dir,1);
            $fileName = "";         
            foreach ($files as $file) {
                $fileName .= strstr($file,$unique_id);
            }

            $file = fopen($this->dir.$fileName,"r");
            $csvData = array();
            $fileStream = array();

            $removeArr = array();
            
            $csvArr = array();           
            $i = 1;
            $numberOfFields = 10;
            $num = 0;
                    
            while (($filedata[] = fgetcsv($file)) !== FALSE) {
                $num = count($filedata);
                /* if($i > 0 && $num == $numberOfFields){
                    $csvArr[$filedata[0][0]] = $filedata[0][1];
                    $csvArr[$filedata[1][0]] = $filedata[1][1];
                    $csvArr[$filedata[2][0]] = $filedata[2][1];
                    $csvArr[$filedata[3][0]] = $filedata[3][1];
                    $csvArr[$filedata[4][0]] = $filedata[4][1];
                    $csvArr[$filedata[5][0]] = $filedata[5][1];
                    $csvArr[$filedata[6][0]] = $filedata[6][1];
                    $csvArr[$filedata[7][0]] = $filedata[7][1];
                    $csvArr[$filedata[8][0]] = $filedata[8][1];
                }                
                $i++; */               
            }

            $lastArr = $num-1;
            if(!empty($filedata[$lastArr][0])){
                $num -= 1;
            }
            
            for($j=10;$j<$num;$j++){
                $csvData[] = array(
                    "Num" => $i,
                    $filedata[9][1] => $filedata[$j][1],
                    $filedata[9][2] => $filedata[$j][2],
                    $filedata[9][3] => $filedata[$j][3],
                    $filedata[9][4] => $filedata[$j][4],
                    $filedata[9][5] => $filedata[$j][5],
                    $filedata[9][6] => $filedata[$j][6],
                    $filedata[9][7] => $filedata[$j][7],
                    $filedata[9][8] => $filedata[$j][8],
                    $filedata[9][9] => $filedata[$j][9],
                    $filedata[9][10] => $filedata[$j][10],
                    $filedata[9][11] => $filedata[$j][11],
                    $filedata[9][12] => $filedata[$j][12],
                    $filedata[9][13] => $filedata[$j][13],
                    $filedata[9][14] => $filedata[$j][14],
                    $filedata[9][15] => $filedata[$j][15]
                );
                $i++;
            }
            header("Content-Type: application/json;charset=utf-8");
            $jsonArr = array(
                "fileName"=>$fileName,
                "testResult"=>$csvData
            );
            echo json_encode($jsonArr);
            fclose($file);
        }
    }

    public function searchData(){
        if ($this->request->isAJAX()) {
            $search['model'] = $this->request->getPost("model");            
            $search['sn'] = $this->request->getPost("sn");
            $search['stationId'] = $this->request->getPost("stationId");
            $search['date_strt'] = $this->request->getPost("date_strt");
            $search['date_end'] = $this->request->getPost("date_end");
            $search['result'] = $this->request->getPost("result");
            $search['uniqueId'] = $this->request->getPost("uniqueId");
            $search['processName'] = $this->request->getPost("processName");
            $search['printedLabel'] = $this->request->getPost("printedLabel");
            
            $j=1;
            $getData = $this->scanning->getSearchData($search);
            $getDataFtn = $this->scanning->getFailedTestNameByModel($search);
            $data = [];
            if(!empty($getData)){
                for($i=0;$i<count($getData['model']);$i++){
                    $nestedData['#'] = $j;
                    $nestedData['model'] = $getData['model'][$i];
                    $nestedData['sn'] = $getData['sn'][$i];
                    $nestedData['unique_id'] = $getData['unique_id'][$i];
                    $nestedData['test_time'] = $getData['test_time'][$i];
                    $nestedData['total_time'] = $getData['total_time'][$i];
                    $nestedData['process_name'] = $getData['process_name'][$i];
                    $nestedData['operator_id'] = $getData['operator_id'][$i];
                    $nestedData['station_id'] = $getData['station_id'][$i];
                    $nestedData['fixture'] = $getData['fixture'][$i];
                    $nestedData['result'] = $getData['result'][$i];
                    $nestedData['failed_test_name'] = $getData['failed_test_name'][$i];
                    $nestedData['printed_label'] = $getData['printed_label'][$i];
                    $data[] = $nestedData;
                    $j++;
                }
            }

            $k=1;
            $dataFtn = [];
            if(!empty($getDataFtn)){
                for($i=0;$i<count($getDataFtn['failed_test_name']);$i++){
                    $nstData['#'] = $k;
                    $nstData['failed_test_name'] = $getDataFtn['failed_test_name'][$i];
                    $nstData['ftn'] = $getDataFtn['ftn'][$i];
                    $dataFtn[] = $nstData;
                    $k++;
                }
            }

            $jsonData = array("data"=>$data,"dataFtn"=>$dataFtn);
            echo json_encode($jsonData);
        } 
    }

    public function downloadFile($fileName){
        $url = $this->dir.$fileName;
        $file = basename($url);  
        $info = pathinfo($file);
        if ($info["extension"] == "csv") {
            header("Content-Description: File Transfer"); 
            header("Content-Type: application/octet-stream"); 
            header(
            "Content-Disposition: attachment; filename=\""
            . $file . "\"");
            readfile ($url);
        }
        exit();
        return redirect()->route('searchItems');
    }

    public function printLabel(){
        return view('header')
            .view('sidebar')
            .view('printLabel')
            .view('footer');
    }
}