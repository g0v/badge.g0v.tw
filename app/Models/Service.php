<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model 
{
    protected $table = 'service';

    public function getData()
    {
        $data = json_decode($this->data);
        if (!property_exists($data, 'public')) {
            $data->public = true;
        }
        return $data;
    }

}
