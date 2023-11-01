<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\ScanningModel;
use App\Models\ImportCSVModel;

class ScanningController extends BaseController {
    public function __construct(){
        $this->scanning = new ScanningModel();
        $this->importCSV = new ImportCSVModel();
        $request = \Config\Services::request();
        $this->dir = "C:/Motorola_Charger_TestLog/1_MUC_TestLog/CSV_Test_Log/TestMonitoring/"; //local
        // $this->dir = "E:/Motorola_Charger_TestLog/1_MUC_TestLog/CSV_Test_Log/TestMonitoring/"; //server
    }

    public function index(){
        return redirect()->route('scan/dashboard');
    }

    public function dashboard(){
        return view('scan/header')
            .view('scan/sidebar')
            .view('scan/index')
            .view('scan/footer');
    }

    public function viewFailByModel(){
        if ($this->request->isAJAX()) {
            $model = $this->request->getPost("model");
            $data = $this->scanning->viewFailByModel($model);
            echo json_encode($data);
        }
    }

    public function uploadCSV(){
        return view('scan/header')
            .view('scan/sidebar')
            .view('scan/uploadCSV')
            .view('scan/footer');
    }

    public function importCsvToDb(){
        $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv],'
        ]);

        if (!$input) {
            $data['validation'] = $this->validator;
            $mgsAlert['message'] = 'No CSV file that be imported.';
            $mgsAlert['alertClass'] = 'alert-danger';
        }else{
            $file = $this->request->getFile('file');
            if($file) {
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
                            $csvArr[$i]['file_name'] = $fileName;
                        }
                        $i++;
                    }
                    fclose($CSVfp);
                    $count = 0;
                    foreach($csvArr as $userdata){
                        $findRecord = $this->importCSV->where('file_name', $userdata['file_name'])->countAllResults();
                        if($findRecord == 0){
                            if($this->importCSV->insert($userdata)){
                                $count++;
                            }
                            $file->move($this->dir, $fileName);
                            session()->setFlashdata('message', $fileName.' file successfully added.');
                            session()->setFlashdata('alert-class', 'alert-success');
                            $mgsAlert['message'] = $fileName.' file successfully added.';
                            $mgsAlert['alertClass'] = 'alert-success';
                        }else{
                            session()->setFlashdata('message', $fileName.' file already added.');
                            session()->setFlashdata('alert-class', 'alert-warning');
                            $mgsAlert['message'] = $fileName.' file already added.';
                            $mgsAlert['alertClass'] = 'alert-warning';
                        }
                    }
                }else{
                    session()->setFlashdata('message', 'CSV file could not be imported.');
                    session()->setFlashdata('alert-class', 'alert-danger');
                    $mgsAlert['message'] = 'CSV file could not be imported.';
                    $mgsAlert['alertClass'] = 'alert-danger';
                }
            }else{
                session()->setFlashdata('message', 'CSV file could not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
                $mgsAlert['message'] = 'CSV file could not be imported.';
                $mgsAlert['alertClass'] = 'alert-danger';
            }
        }
        echo json_encode($mgsAlert);     
    }

    public function searchItems(){        
        $data['model'] = $this->scanning->getModels();
        $data['process_name'] = $this->scanning->getProcessName();
        $data['station_id'] = $this->scanning->getStationID();

        return view('scan/header')
            .view('scan/sidebar')
            .view('scan/searchItems',$data)
            .view('scan/footer');
    }

    public function searchItemsAjax(){
        if ($this->request->isAJAX()) {
            $unique_id = $this->request->getPost("unique_id");
            $fileName = $this->scanning->searchCSVFile($unique_id);
            $filePath = $this->dir.$fileName;
            $files = is_file($filePath);
            // var_dump($files);
            // echo $filePath."<br>";
            if(file_exists($filePath)){
                // echo "path exist";
            }else{
                // echo "path not exist";
            }
            $file = fopen($filePath,"r");
            $csvData = array();
            $fileStream = array();

            $removeArr = array();
            
            $csvArr = array();           
            $i = 1;
            $numberOfFields = 10;
            $num = 0;
                    
            while (($filedata[] = fgetcsv($file)) !== FALSE) {
                $num = count($filedata);          
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
        return redirect()->route('scan/searchItems');
    }

    public function printLabel(){
        return view('scan/header')
            .view('scan/sidebar')
            .view('scan/printLabel')
            .view('scan/footer');
    }
}