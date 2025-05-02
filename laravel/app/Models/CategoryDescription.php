<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryDescription extends Model
{
    use HasFactory;

    protected $table = 'oc_category_description';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    /**
     * Get the product that description.
     */
    public function product(): BelongsTo
    {
        return $this->BelongsTo(Category::class, 'category_id', 'category_id');
    }
}

