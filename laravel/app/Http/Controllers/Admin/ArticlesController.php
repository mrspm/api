<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article as Model; //change
use App\Http\Components\Translit;

class ArticlesController extends Controller
{
    //change
    public $route_prefix = 'admin.articles';
    public $path_name = 'articles';
    public $rules = [
        'name' => 'required|max:255',
        'alias' => 'required|max:255|unique:articles,alias',
        'meta_title' => 'required|max:255',
        'meta_description' => 'required|max:255',
        'article_text' => 'required',
        'published' => 'required|integer',
        'created_at' => 'required',
    ];
    //change end

    public function index()
    {
        $items = Model::query()->orderBy('created_at', 'desc')->paginate(20);
        return view($this->route_prefix . '.index', ['items' => $items]);
    }

    public function create()
    {
        return view($this->route_prefix . '.create');
    }

    public function store(Request $request)
    {
        unset($this->rules['alias']);

        $request->validate($this->rules);

        $model = new Model();
        foreach(array_keys($this->rules) as $attribute) $model->$attribute = $request->$attribute;

        $model->alias = Translit::makeTranslit($model->name);
        $alias_exists = Model::query()->where('alias', $model->alias)->first();
        if(!empty($alias_exists->id)) $model->alias .= '-' . time();

        $model->created_at = date('Y-m-d', strtotime($model->created_at));

        if(!empty($request->image)){
            $model->image = '/storage/images/articles/' . $model->alias . '.' . $request->file('image')->extension();
            $request->file('image')->move($_SERVER['DOCUMENT_ROOT'] . '/storage/images/articles/', $model->alias . '.' . $request->file('image')->extension());
        }

        $model->save();

        return redirect()->route($this->path_name . '.index');
    }

    public function edit($id)
    {
        $model = Model::find($id);
        return view($this->route_prefix . '.edit', ['model' => $model]);
    }

    public function update(Request $request, $id)
    {
        $this->rules['alias'] .= ',' . $id; 

        $request->validate($this->rules);

        $model = Model::find($id);
        foreach(array_keys($this->rules) as $attribute) $model->$attribute = $request->$attribute;

        $model->created_at = date('Y-m-d', strtotime($model->created_at));

        if(!empty($request->image)){
            if(!empty($model->image) && file_exists($_SERVER['DOCUMENT_ROOT'] . $model->image)) unlink($_SERVER['DOCUMENT_ROOT'] . $model->image);
            $model->image = '/storage/images/articles/' . $model->alias . '.' . $request->file('image')->extension();
            $request->file('image')->move($_SERVER['DOCUMENT_ROOT'] . '/storage/images/articles/', $model->alias . '.' . $request->file('image')->extension());
        }

        $model->save();

        return redirect()->route($this->path_name . '.index');
    }

    public function destroy($id)
    {
        $model = Model::find($id);

        if(!empty($model->image) && file_exists($_SERVER['DOCUMENT_ROOT'] . $model->image)) unlink($_SERVER['DOCUMENT_ROOT'] . $model->image);

        $model->delete();

        return redirect()->route($this->path_name . '.index')->with('success', 'Успешно удалено.');
    }

}