<?php

namespace App\Models;

use App\Http\Components\Translit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $table = 'oc_product';
    protected $primaryKey = 'product_id';
    protected $guarded = ['product_id'];

    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    /**
     * Get name, description etc for the product.
     */
    public function description(): HasOne
    {
        return $this->hasOne(ProductDescription::class, 'product_id', 'product_id')->where('language_id', Config::get('app.ukrainian_language_id'));
    }

    /**
     * Get product category.
     */
    public function getCategory(): Category
    {
        $category = Category::whereRaw("category_id IN (SELECT category_id FROM oc_product_to_category WHERE product_id = " . $this->product_id. " AND main_category = 1)")->first();
        if(empty($category)) $category = Category::whereRaw("category_id IN (SELECT category_id FROM oc_product_to_category WHERE product_id = " . $this->product_id. ")")->first();
        if(empty($category)) $category = new Category;

        return $category;
    }

    /*
     * Adding category to product... because of composite primary key in Opencart DB table
     */
    public function addCategory(Category $category): void
    {
        DB::table('oc_product_to_category')->where('product_id', $this->product_id)->delete();

        DB::table('oc_product_to_category')->insert([
            'product_id' => $this->product_id,
            'category_id' => $category->category_id,
        ]);
    }

    /*
     * save product alias
     */
    public function saveAlias(): void
    {
        SeoUrl::where('query', 'product_id=' . $this->product_id)->delete();

        $seo_url = new SeoUrl;
        $seo_url->language_id = Config::get('app.ukrainian_language_id');
        $seo_url->query = 'product_id=' . $this->product_id;
        $seo_url->keyword = $this->product_id  . '-' . Translit::makeTranslit($this->description->name);
        $seo_url->save();
    }

    /*
     * Generates URL for product
     */
    public function getUrl(): string
    {
        $product_path = '';

        $seo_url = SeoUrl::where('query', 'product_id=' . $this->product_id)->where('language_id', Config::get('app.ukrainian_language_id'))->first();
        if(empty($seo_url)) return '';
        $product_path = $seo_url->keyword;

        $category = $this->getCategory();
        if(empty($category)) return '';

        $seo_url = SeoUrl::where('query', 'category_id=' . $category->category_id)->where('language_id', Config::get('app.ukrainian_language_id'))->first();
        if(!empty($seo_url)) $product_path = $seo_url->keyword . '/' . $product_path;

        $has_parent  = true;
        if(empty($category->parent_id)) $has_parent = false;
        $cur_category = $category;
        while($has_parent) {
            $parent_category = Category::where('category_id', $cur_category->parent_id)->first();
            if(!empty($parent_category)) {
                $cur_category = $parent_category;
                $seo_url = SeoUrl::where('query', 'category_id=' . $parent_category->category_id)->where('language_id', Config::get('app.ukrainian_language_id'))->first();
                if(!empty($seo_url)) $product_path = $seo_url->keyword . '/' . $product_path;
            } else {
                $has_parent = false;
            }
        }

        $product_path = Config::get('app.url') . '/ua/' . $product_path;

        return $product_path;
    }
}
