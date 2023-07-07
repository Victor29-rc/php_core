<?php 

namespace app\Models;

use app\Models\Model;

class Home extends Model{
    protected $table = 'contacts';
    protected $primaryKey = 'id';
    protected $softDelete = true;
}