<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class ScanningModel extends Model
{
    public function __construct(){    
        $this->db = \Config\Database::connect();
    }

    public function getFailedTestName(){
        $results = ['Fail', 'Abort'];
        $table = $this->db->table('model_test_result');
        $query = $table->select('failed_test_name,COUNT(failed_test_name) AS ftn')          
                ->whereIn('result', $results)->groupBy('failed_test_name')->having('ftn > 0')
                ->orderBy('ftn', 'DESC');
        $result = $query->get();
        foreach ($result->getResult() as $row) {
            $data['failed_test_name'][] = $row->failed_test_name;
            $data['ftn'][] = $row->ftn;
        }
        return $data;
    }

    public function getModels(){
        $table = $this->db->table('info_charger_scanning a, model_test_result b');
        $query = $table->select('DISTINCT(a.model)')          
                ->where('a.type=b.type');
        $result = $query->get();
        foreach ($result->getResult() as $row) {
            $model[] = $row->model;
        }
        return $model;
    }

    public function getProcessName(){
        $table = $this->db->table('model_test_result');
        $query = $table->select('DISTINCT(process_name) as process_name');
        $result = $query->get();
        foreach ($result->getResult() as $row) {
            $data[] = $row->process_name;
        }
        return $data;
    }

    public function getStationID(){
        $table = $this->db->table('model_test_result');
        $query = $table->select('DISTINCT(station_id) as station_id');
        $result = $query->get();
        foreach ($result->getResult() as $row) {
            $data[] = $row->station_id;
        }
        return $data;
    }

    public function getTestModel(){
        $table = $this->db->table('info_charger_scanning a');
        $query = $table->select('DISTINCT(a.model) as model, a.type')
                ->join('model_test_result b', 'a.type = b.type', 'right');
        $result = $query->get();
        foreach ($result->getResult() as $row) {
            $data['model'][] = $row->model;
            $data['type'][] = $row->type;
        }
        return $data;
    }

    public function getSearchData($search){
        $column = "a.model,b.sn,b.unique_id,b.test_time,b.total_time,b.process_name,
                    b.operator_id,b.station_id,b.fixture,b.result,b.failed_test_name,
                    b.printed_label";
        $table = $this->db->table('info_charger_scanning a, model_test_result b');
        $query = $table->select($column)->where('a.type=b.type');

        if($search['model'] != null){
            $query = $table->where("a.model",$search['model']);
		}if($search['sn'] != null){
            $query = $table->where("b.sn",$search['sn']);
		}if($search['stationId'] != null){
            $query = $table->where("b.station_id",$search['stationId']);
		}if($search['date_strt'] != null && $search['date_end'] != null){
            $query = $table->where("date(b.test_time) BETWEEN '".
                     $search['date_strt']."' and '".$search['date_end']."'");
        }if($search['result'] != null){
            $query = $table->where("b.result",$search['result']);
		}if($search['uniqueId'] != null){
            $query = $table->where("b.unique_id",$search['uniqueId']);
        }if($search['processName'] != null){
            $query = $table->where("b.process_name",$search['processName']);
		}if($search['printedLabel'] != null){
            $query = $table->where("b.printed_label",$search['printedLabel']);
		}
        $query = $table->orderBy('b.test_time', 'DESC');

        $data = [];
        $result = $query->get();        
        if($query->countAllResults() > 0) {
            foreach ($result->getResult() as $row) {
                if($row->printed_label == 1){
                    $pl = "Yes";
                }elseif($row->printed_label == 0){
                    $pl = "No";
                }
                $data['model'][] = $row->model;
                $data['sn'][] = $row->sn;
                $data['unique_id'][] = $row->unique_id;
                $data['test_time'][] = $row->test_time;
                $data['total_time'][] = $row->total_time;
                $data['process_name'][] = $row->process_name;
                $data['operator_id'][] = $row->operator_id;
                $data['station_id'][] = $row->station_id;
                $data['fixture'][] = $row->fixture;
                $data['result'][] = $row->result;
                $data['failed_test_name'][] = $row->failed_test_name;
                $data['printed_label'][] = $pl;            
            }
        }
        return $data;
    }

    public function getFailedTestNameByModel($search){
        $table = $this->db->table('info_charger_scanning');
        $query = $table->select('type')          
                ->where('model',$search['model']);
        $result = $query->get();
        $type = "";
        foreach ($result->getResult() as $row) {
            $type = $row->type;
        }
        // $results = ['Fail', 'Abort'];
        if(!empty($type)){
            $table = $this->db->table('model_test_result');
            $query = $table->select('failed_test_name,COUNT(failed_test_name) AS ftn')
                    ->where('type',$type)
                    ->where('result', $search['result'])->groupBy('failed_test_name')->having('ftn > 0')
                    ->orderBy('ftn', 'DESC');
            $result = $query->get();
            $data = [];
            foreach ($result->getResult() as $row) {
                $data['failed_test_name'][] = $row->failed_test_name;
                $data['ftn'][] = $row->ftn;
            }
        }
        return $data;
    }
}