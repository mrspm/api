<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Config;

class Category extends Model
{
    use HasFactory;

    protected $table = 'oc_category';
    protected $primaryKey = 'category_id';

    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    /**
     * Get name, description etc for the product.
     */
    public function description(): HasOne
    {
        return $this->hasOne(CategoryDescription::class, 'category_id', 'category_id')->where('language_id', Config::get('app.ukrainian_language_id'));
    }
}
