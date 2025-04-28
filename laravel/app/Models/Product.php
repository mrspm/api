<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $table = 'oc_product';
    protected $primaryKey = 'product_id';

    /**
     * Get name, description etc for the product.
     */
    public function description(): HasOne
    {
        return $this->hasOne(ProductDescription::class)->where('language_id', config('ukrainian_language_id'));
    }
}
