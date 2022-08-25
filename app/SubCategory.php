<?php

namespace App;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Models\TranslateAwareModel;
use App\Models\Variants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SubCategory extends TranslateAwareModel
{
    // use SoftDeletes;

    public $translatable = ['name', 'description'];

    protected $table = 'sub_categories';
    protected $appends = ['logom'];
    protected $fillable = ['sub_category_name', 'sub_category_description', 'image', ];
    protected $imagePath = '/uploads/sub-categories/';


    protected function getImge($imageValue, $default, $version = '_large.jpg')
    {
        if ($imageValue == '' || $imageValue == null) {
            //No image
            return $default;
        } else {
            if (strpos($imageValue, 'http') !== false) {
                //Have http
                if (strpos($imageValue, '.jpg') !== false || strpos($imageValue, '.jpeg') !== false || strpos($imageValue, '.png') !== false) {
                    //Has extension
                    return $imageValue;
                } else {
                    //No extension
                    return $imageValue.$version;
                }
            } else {
                //Local image
                return ($this->imagePath.$imageValue).$version;
            }
        }
    }

    public function substrwords($text, $chars, $end = '...')
    {
        if (strlen($text) > $chars && strpos($text, " ") !== false) {
            $text = $text.' ';
            $text = substr($text, 0, $chars);
            $text = substr($text, 0, strrpos($text, ' '));
            $text = $text.'...';
        }

        return $text;
    }

    public function getLogomAttribute()
    {
        return $this->getImge($this->image, config('global.restorant_details_image'));
    }

    public function getIconAttribute()
    {
        return $this->getImge($this->image, config('global.restorant_details_image'), '_thumbnail.jpg');
    }



}
