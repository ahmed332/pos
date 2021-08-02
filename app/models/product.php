<?php

namespace App\models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use Translatable;
    /**
    * The relations to eager load on every query.
    *
    * @var array
    */
   protected $with = ['translations'];


   protected $translatedAttributes = ['name','description'];


    protected $guarded = [];
    protected $appends=['profit_percent'];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');

    }//end fo category RELATIONS
    public function getProfitPercentAttribute()
{
    $profit = $this->sale_price - $this->purchase_price;
    $profit_percent = $profit * 100 / $this->purchase_price;
    return number_format($profit_percent, 2);

}//end of get profit attribute
}


