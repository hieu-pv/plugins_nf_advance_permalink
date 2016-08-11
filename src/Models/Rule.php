<?php

namespace App\Models;

use NFWP\Models\NFModel;

class Rule extends NFModel
{
    protected $table    = DB_TABLE_NAME;
    protected $fillable = ['regex', 'type', 'entity_id'];

}
