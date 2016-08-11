<?php

namespace NFWP\Models;

use Illuminate\Database\Eloquent\Model;
use NFWP\Database\NFDatabase;

class NFModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $database = new NFDatabase();
        $capsule  = $database->getCapsule();
        $capsule->bootEloquent();
    }
}
