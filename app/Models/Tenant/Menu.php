<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Menu extends Model
{
    use HasFactory;

    public function price(): Attribute
    {
        return new Attribute(
            get: function($value){
                $price = $value / 100;
                return number_format($price, 2 , ',','.');
            },
            set: function($value){
                $value = (float) str_replace(['.',','],[''.'.'], $value);
                return $value * 100;
            }
        );
    }
}
