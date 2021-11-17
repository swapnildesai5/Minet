<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientSubCategory extends Model
{
    protected $table = 'client_sub_categories';
   
    public function client_category()
    {
        return $this->belongsTo(ClientCategory::class, 'category_id');
    }
}
