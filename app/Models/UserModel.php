<?php 

namespace App\Models;  
use CodeIgniter\Model;

  
class UserModel extends Model{

    protected $table = 'users';
    
    protected $allowedFields = [
        'name',
        'batch_no',
        'role',
        'password',
        'created_at'
    ];

}