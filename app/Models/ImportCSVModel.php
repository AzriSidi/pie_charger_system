<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class ImportCSVModel extends Model{
    protected $table = 'model_test_result';
    protected $allowedFields = [
        'sn',
        'type',
        'unique_id', 
        'test_time', 
        'total_time',
        'process_name',
        'operator_id',
        'station_id',
        'fixture',
        'result',
        'failed_test_name'
    ];
}