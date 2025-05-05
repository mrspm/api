<?php

namespace App\Models;

use App\Http\Components\Translit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Config;

class Category extends Model
{
    use HasFactory;

    protected $table = 'oc_category';
    protected $primaryKey = 'category_id';
    protected $guarded = ['category_id'];

    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    /**
     * Get name, description etc for the product.
     */
    public function description(): HasOne
    {
        return $this->hasOne(CategoryDescription::class, 'category_id', 'category_id')->where('language_id', Config::get('app.ukrainian_language_id'));
    }

    /*
 * save product alias
 */
    public function saveAlias(): void
    {
        SeoUrl::where('query', 'category_id=' . $this->category_id)->delete();

        $seo_url = new SeoUrl;
        $seo_url->language_id = Config::get('app.ukrainian_language_id');
        $seo_url->query = 'category_id=' . $this->category_id;
        $seo_url->keyword = Translit::makeTranslit($this->description->name);
        $seo_url->save();
    }
}
