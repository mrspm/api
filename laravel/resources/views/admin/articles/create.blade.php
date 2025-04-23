@extends('admin.layouts.main')

@php $title = $h1 = "Новая статья" @endphp
@section('title'){{ $title }}@endsection
@section('h1'){{ $h1 }}@endsection

@section('content')
<form action="{{ route('articles.store') }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label for="form_name">Название<span class="text-danger">*</span></label>
        <input id="form_name" name="name" value="{{ old('name') }}" type="text" class="form-control" required autocomplete="off">
    </div>

    <div class="form-group">
        <label for="form_meta_title">Meta Title<span class="text-danger">*</span></label>
        <input id="form_meta_title" name="meta_title" value="{{ old('meta_title') }}" type="text" class="form-control" required autocomplete="off">
    </div>

    <div class="form-group">
        <label for="form_meta_description">Meta Description<span class="text-danger">*</span></label>
        <input id="form_meta_description" name="meta_description" value="{{ old('meta_description') }}" type="text" class="form-control" required autocomplete="off">
    </div>
    
    <div class="form-group">
        <label for="form_article_text">Текст<span class="text-danger">*</span></label>
        <textarea id="form_article_text" name="article_text" required autocomplete="off" rows="10">{!! old('article_text') !!}</textarea>
    </div>

    <div class="form-group">
        <label for="form_image">Изображение (jpg, png)</label>
        <input id="form_image" name="image" type="file" class="form-control" accept="image/jpeg, image/png" autocomplete="off">
    </div>

    <div class="form-group">
        <label for="form_created_at">Дата<span class="text-danger">*</span></label>
        <input id="form_created_at" class="form-control datetimepicker-date" name="created_at" value="{{ !empty(old('created_at')) ?  old('created_at') : date('d.m.Y') }}" type="text" autocomplete="off" required>
    </div>

    <div class="form-group">
        <label for="form_published">Опубликовано</label>
        <select id="form_published" name="published" class="form-control" required autocomplete="off">
            <option value="0">Нет</option>
            <option value="1" @if(old('published' == 1)) selected @endif)>Да</option>
        </select>
    </div>

    <div class="form-group">
        <a class="btn btn-default btn-sm back-link" href="javascript:void(0);"><i class="far fa-arrow-alt-circle-left"></i></a>
        <button type="submit" class="btn btn-sm btn-success">Сохранить</button>
    </div>
</form>
@endsection