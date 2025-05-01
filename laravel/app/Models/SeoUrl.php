<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoUrl extends Model
{
    use HasFactory;

    protected $table = 'oc_seo_url';
    protected $primaryKey = 'seo_url_id';
    public $timestamps = false;
}
