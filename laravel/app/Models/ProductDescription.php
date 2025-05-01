<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDescription extends Model
{
    use HasFactory;

    protected $table = 'oc_product_description';
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    /**
     * Get the product that description.
     */
    public function product(): BelongsTo
    {
        return $this->BelongsTo(Product::class, 'product_id', 'product_id');
    }
}
