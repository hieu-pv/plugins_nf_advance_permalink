<?php
namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;
use NFWP\Database\NFDatabase;

class Database extends NFDatabase
{
    public function up()
    {
        global $wpdb;
        
        if (!Capsule::schema()->hasTable(DB_TABLE_NAME)) {
            Capsule::schema()->create(DB_TABLE_NAME, function ($table) {
                $table->increments('id');
                $table->string('regex')->unique();
                $table->string('type');
                $table->integer('entity_id')->unsigned()->default(0);
                $table->timestamps();
            });
        } else {
            // Capsule::schema()->drop(DB_TABLE_NAME);
        }
    }
}
