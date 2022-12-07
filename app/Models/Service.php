<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model 
{
    protected $table = 'service';

    public function getData()
    {
        return json_decode($this->data);
    }

}
