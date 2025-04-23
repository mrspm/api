<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order as Model; //change

class OrdersController extends Controller
{
    //change
    public $route_prefix = 'admin.orders';
    public $path_name = 'orders';
    public $rules = [
        'title' => 'required|max:255',
        'name' => 'nullable|max:255',
        'phone' => 'nullable|max:255',
        'email' => 'nullable|email|max:255',
    ];
    //change end

    public function index()
    {
        $items = Model::query()->orderBy('created_at', 'desc')->paginate(20);
        return view($this->route_prefix . '.index', ['items' => $items]);
    }

    public function show($id)
    {
        $model = Model::find($id);
        return view($this->route_prefix . '.show', ['model' => $model]);
    }

    public function create()
    {
        return view($this->route_prefix . '.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules);

        $model = new Model();
        foreach(array_keys($this->rules) as $attribute) $model->$attribute = $request->$attribute;
        $model->save();

        return redirect()->route($this->path_name . '.index');
    }

    public function edit($id)
    {
        $model = Model::find($id);
        return view($this->route_prefix . '.edit', ['model' => $model,]);
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->rules);

        $model = Model::find($id);
        foreach(array_keys($this->rules) as $attribute) $model->$attribute = $request->$attribute;
        $model->save();

        return redirect()->route($this->path_name . '.index');
    }

    public function destroy($id)
    {
        $model = Model::find($id);

        $model->delete();

        return redirect()->route($this->path_name . '.index')->with('success', 'Успешно удалено.');
    }

}