@extends('admin.layouts.main')

@php $title = $h1 = "Редактировать статью " . $model->name @endphp
@section('title'){{ $title }}@endsection
@section('h1'){{ $h1 }}@endsection

@section('content')
<form action="{{ route('articles.update', [$model]) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="form_name">Название<span class="text-danger">*</span></label>
        <input id="form_name" name="name" value="{{ $model->name }}" type="text" class="form-control" required autocomplete="off">
    </div>

    <div class="form-group">
        <label for="form_alias">Alias<span class="text-danger">*</span></label>
        <input id="form_alias" name="alias" value="{{ $model->alias }}" type="text" class="form-control" required autocomplete="off">
    </div>

    <div class="form-group">
        <label for="form_meta_title">Meta Title<span class="text-danger">*</span></label>
        <input id="form_meta_title" name="meta_title" value="{{ $model->meta_title }}" type="text" class="form-control" required autocomplete="off">
    </div>

    <div class="form-group">
        <label for="form_meta_description">Meta Description<span class="text-danger">*</span></label>
        <input id="form_meta_description" name="meta_description" value="{{ $model->meta_description }}" type="text" class="form-control" required autocomplete="off">
    </div>

    <div class="form-group">
        <label for="form_article_text">Текст<span class="text-danger">*</span></label>
        <textarea id="form_article_text" name="article_text" required autocomplete="off" rows="10">{!! $model->article_text !!}</textarea>
    </div>

    <div class="form-group">
        <img src="{{ $model->image }}" alt="{{ $model->name }}" style="width: 200px; height: auto; display: block;">
        <label for="form_image">Изображение (jpg, png)</label>
        <input id="form_image" name="image" type="file" class="form-control" accept="image/jpeg, image/png" autocomplete="off">
    </div>

    <div class="form-group">
        <label for="form_created_at">Дата<span class="text-danger">*</span></label>
        <input id="form_created_at" class="form-control datetimepicker-date" name="created_at" value="{{ date('d.m.Y', strtotime($model->created_at)) }}" type="text" autocomplete="off" required>
    </div>

    <div class="form-group">
        <label for="form_published">Опубликовано</label>
        <select id="form_published" name="published" class="form-control" required autocomplete="off">
            <option value="0">Нет</option>
            <option value="1"{{ $model->published == 1 ? ' selected' : '' }}>Да</option>
        </select>
    </div>

    <div class="form-group">
        <a class="btn btn-default btn-sm back-link" href="javascript:void(0);"><i class="far fa-arrow-alt-circle-left"></i></a>
        <button type="submit" class="btn btn-sm btn-success">Сохранить</button>
    </div>
</form>
@endsection