<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class PackDataModel extends Model{
    public function __construct(){    
        $this->db = \Config\Database::connect();
        $this->session = session();
    }

    public function getCustNo(){
        $table = $this->db->table('info_packing_unit');
        $query = $table->select('cust_no')
                    ->orderBy('cust_no');
        $result = $query->get();
        $data = [];
        foreach ($result->getResult() as $row){
            $data[] = $row->cust_no;
        }
        return $data;
    }

    public function getModel(){
        $table = $this->db->table('info_packing_unit');
        $query = $table->select('model')
                    ->orderBy('model');;
        $result = $query->get();
        $data = [];
        foreach ($result->getResult() as $row){
            $data[] = $row->model;
        }
        return $data;
    }

    public function getSearchDataSN($search){
        $column = "a.model, a.box_id, GROUP_CONCAT(a.sn) AS sn";
        $table = $this->db->table('packing_box_id a, packing_data b');
        $query = $table->select($column);
        if($search['model'] != null){
            $query = $table->where("a.model",$search['model']);
		}if($search['boxId'] != null){
            $query = $table->where("a.box_id",$search['boxId']);
		}if($search['sn'] != null){
            $query = $table->where("a.sn",$search['sn']);
		}if($search['date_strt'] != null && $search['date_end'] != null){
            $query = $table->where("date(b.timestamp) BETWEEN '".
                     $search['date_strt']."' and '".$search['date_end']."'");
        }
        $query = $table->where("a.box_id = b.box_id");
        $query = $table->orderBy('a.id', 'DESC');
        $query = $table->groupBy('a.box_id');
        $data = [];
        $result = $query->get();        
        if($query->countAllResults() > 0) {
            foreach ($result->getResult() as $row) {
                $data['model'][] = $row->model;
                $data['box_id'][] = $row->box_id;
                $data['sn'][][] = $row->sn;    
            }
        }
        return $data;
    }

    public function getItemDetail(){
        $table = $this->db->table('list_item_detail');
        $query = $table->select('item_detail')
                    ->orderBy('item_detail');
        $result = $query->get();
        $data = [];
        foreach ($result->getResult() as $row){
            $data[] = $row->item_detail;
        }
        return $data;
    }

    public function addModel($data){
        $batchNo = $this->session->get('batch_no');
        $tableInfoUnit = $this->db->table('info_packing_unit');
        $tablePackItem = $this->db->table('packing_box_item');
        $tableItemDetail = $this->db->table('list_item_detail');        
        $query = $tableInfoUnit->select("*");
        $query = $tableInfoUnit->where("cust_no",$data['custNo']);
        $query = $tableInfoUnit->where("model",$data['model']);        
        if($query->countAllResults() > 0) {
            $data = "fail";
        }else{
            $dataInfo = [
                'file_path' => $data['label_path'],
                'cust_no' => $data['custNo'],
                'model' => $data['model'],
                'quantity_per_box' => $data['quantityBox'],
                'line_no' => $data['lineNo'],
                'single_unit_weight' => $data['singleUnit'],
                'tolerance' => $data['tolerance'],
                'check_sn' => $data['checkSN']
            ];
            $dataScan = [
                'model' => $data['model'],
                'item_no' => $data['totalScan'],
                'item_scan' => implode(',',$data['itemScan'])
            ];            
            $itemDetailFilter = array_values(array_filter($data['itemDetail']));
            $itemDetailTxtFilter = array_values(array_filter($data['itemDetailText']));
            if(empty($itemDetailTxtFilter)){
                $itemDetail = ['item_detail' => implode(',',$data['itemDetail'])];
                $dataScan = array_merge($dataScan,$itemDetail);           
            }else{          
                $arrMerge = array_merge($itemDetailFilter,$itemDetailTxtFilter);
                $itemDetail = ['item_detail' =>  implode(',',$arrMerge)];
                $dataScan = array_merge($dataScan,$itemDetail); 
                foreach($itemDetailTxtFilter as $value){
                    $itemDetailTxt = ['item_detail' =>  $value];
                    $itemDetailTxtImp = implode(',', $itemDetailTxtFilter);                       
                    $query = $tableItemDetail->select("*");
                    $query = $tableItemDetail->where("item_detail", $itemDetailTxtImp);
                    $itemDetailCount = count($itemDetailTxtFilter);
                    if($query->countAllResults() == 0) {
                        $tableItemDetail->insert($itemDetailTxt);
                    }
                }                                        
            }
            $tableInfoUnit->insert($dataInfo);
            $query = $this->db->getLastQuery();
            $getQuery1['info_unit'] = $query->getQuery();

            $tablePackItem->insert($dataScan);
            $query = $this->db->getLastQuery();
            $getQuery2['pack_item'] = $query->getQuery();

            $queryData = array_merge($getQuery1,$getQuery2);
            $jsonData = json_encode($queryData);
            $this->auditTrail($batchNo,"INSERT",$jsonData);
            $data = "success";
        }
        return $data;       
    }

    public function searchByModel($data){
        $columnA = "a.cust_no,a.quantity_per_box,a.line_no,a.single_unit_weight,tolerance,check_sn";
        $columnB = ",b.item_no,b.item_detail,b.item_scan";
        $table = $this->db->table('info_packing_unit as a');
        $query = $table->select($columnA.$columnB)
                        ->where('a.model', $data['model'])
                        ->join('packing_box_item as b', 'a.model = b.model', 'LEFT');        
        $result = $query->get();  
        $data = [];        
        if($query->countAllResults() > 0){
            foreach ($result->getResult() as $row){
                $data['cust_no'][] = $row->cust_no;
                $data['quantity_per_box'][] = $row->quantity_per_box;
                $data['line_no'][] = $row->line_no;
                $data['single_unit_weight'][] = $row->single_unit_weight;
                $data['tolerance'][] = $row->tolerance;
                $data['checkSN'][] = $row->check_sn;
                $data['item_no'][] = $row->item_no;
                $data['item_detail'][] = $row->item_detail;
                $data['item_scan'][] = $row->item_scan;  
            }
        }
        return $data;        
    }

    public function editModel($data){
        $batchNo = $this->session->get('batch_no');
        $tableInfoUnit = $this->db->table('info_packing_unit');
        $tablePackItem = $this->db->table('packing_box_item');
        $tableItemDetail = $this->db->table('list_item_detail');        
        $query = $tableInfoUnit->select("*");
        $query = $tableInfoUnit->where("model",$data['model']);        
        if($query->countAllResults() > 0) {
            $dataInfo = [
                'cust_no' => $data['custNo'],
                'quantity_per_box' => $data['quantityBox'],
                'line_no' => $data['lineNo'],
                'single_unit_weight' => $data['singleUnit'],
                'tolerance' => $data['tolerance'],
                'check_sn' => $data['checkSN']
            ];
            $dataScan = [
                'model' => $data['model'],
                'item_no' => $data['totalScan'],
                'item_scan' => implode(',',$data['itemScan'])
            ];            
            $itemDetailFilter = array_values(array_filter($data['itemDetail']));
            $itemDetailTxtFilter = array_values(array_filter($data['itemDetailText']));
            if(empty($itemDetailTxtFilter)){
                $itemDetail = ['item_detail' => implode(',',$data['itemDetail'])];
                $dataScan = array_merge($dataScan,$itemDetail);           
            }else{          
                $arrMerge = array_merge($itemDetailFilter,$itemDetailTxtFilter);
                $itemDetail = ['item_detail' =>  implode(',',$arrMerge)];
                $dataScan = array_merge($dataScan,$itemDetail); 
                foreach($itemDetailTxtFilter as $value){
                    $itemDetailTxt = ['item_detail' =>  $value];
                    $itemDetailTxtImp = implode(',', $itemDetailTxtFilter);                       
                    $query = $tableItemDetail->select("*");
                    $query = $tableItemDetail->where("item_detail", $itemDetailTxtImp);
                    $itemDetailCount = count($itemDetailTxtFilter);
                    if($query->countAllResults() == 0) {
                        $tableItemDetail->insert($itemDetailTxt);
                    }
                }                                        
            }
            $query = $tablePackItem->select("*");
            $query = $tablePackItem->where("model",$data['model']);        
            if($query->countAllResults() > 0) {
                $tableInfoUnit
                    ->where('model',$data['model'])
                    ->update($dataInfo);
                $query = $this->db->getLastQuery();
                $getQuery1['info_unit'] = $query->getQuery();
                $tablePackItem
                    ->where('model',$data['model'])
                    ->update($dataScan);
                $query = $this->db->getLastQuery();
                $getQuery2['pack_item'] = $query->getQuery();
                $queryData = array_merge($getQuery1,$getQuery2);
                $jsonData = json_encode($queryData);
                $this->auditTrail($batchNo,"UPDATE",$jsonData);
                $data = "success";
            }else{
                $tableInfoUnit
                    ->where('model',$data['model'])
                    ->update($dataInfo);
                $query = $this->db->getLastQuery();
                $getQuery1['info_unit'] = $query->getQuery();
                $tablePackItem->insert($dataScan);
                $query = $this->db->getLastQuery();
                $getQuery2['pack_item'] = $query->getQuery();
                $queryData = array_merge($getQuery1,$getQuery2);
                $jsonData = json_encode($queryData);
                $this->auditTrail($batchNo,"UPDATE/INSERT",$jsonData);                
                $data = "success";
            }
        }else{
            $data = "fail";
        }
        return $data;
    }

    public function auditTrail($batchNo,$statement,$query){
        $tablePackAudit = $this->db->table('packing_audit_trail');
        $dataAudit = [
            'batch_no'  => $batchNo,
            'statement' => $statement,
            'query'     => $query,
        ];
        $tablePackAudit->insert($dataAudit);
    }

    public function searchSN($boxID){
        $table = $this->db->table('packing_box_id');
        $query = $table->select('sn')       
                ->where('box_id',$boxID);
        $result = $query->get();
        $sn = "";
        foreach ($result->getResult() as $row) {
            $sn = $row->sn;
        }
        return $sn;
    }

    public function searchDataTable($search){
        $column = "cust_no,box_id,quantity_per_box,gross_weight,plant_id,line_no,shift,date_time,packed_by";
        $table = $this->db->table('packing_data');
        $query = $table->select($column);
        if($search['custNo'] != null){
            $query = $table->where("cust_no",$search['custNo']);
		}if($search['boxId'] != null){
            $query = $table->where("box_id",$search['boxId']);
		}if($search['lineNo'] != null){
            $query = $table->where("line_no",$search['lineNo']);
		}if($search['date_strt'] != null && $search['date_end'] != null){
            $query = $table->where("date(timestamp) BETWEEN '".
                     $search['date_strt']."' and '".$search['date_end']."'");
        }if($search['packedBy'] != null){
            $query = $table->where("packedBy",$search['packedBy']);
		}if($search['shift'] != null){
            $query = $table->where("shift",$search['shift']);
        }
        $query = $table->orderBy('id', 'DESC');
        return $query;
    }

    public function deleteSN($boxID){
        $packBoxId = $this->db->table('packing_box_id');
        $packData = $this->db->table('packing_data');
        
        $query = $packBoxId->select("*");
        $query = $packBoxId->where("box_id",$boxID);        
        $query2 = $packData->select("*");
        $query2 = $packData->where("box_id",$boxID);  
        
        if($query->countAllResults() > 0 && $query2->countAllResults() > 0) {
            $packBoxId->where('box_id',$boxID)
                        ->delete();
            $packData->where('box_id',$boxID)
                        ->delete();
            $mgs = "success";
        }else{
            $mgs = "failed";
        }
        
        return $mgs;
    }
}