@extends('admin.layouts.main')

@php $title = $h1 = "Редактировать заявку №" . $model->id @endphp
@section('title'){{ $title }}@endsection
@section('h1'){{ $h1 }}@endsection

@section('content')
<form action="{{ route('orders.update', [$model]) }}" method="post">
    @csrf
    <div class="form-group">
        <label for="form_name">Имя<span class="text-danger">*</span></label>
        <input id="form_name" name="name" value="{{ $model->name }}" type="text" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label for="form_phone">Телефон<span class="text-danger">*</span></label>
        <input id="form_phone" name="phone" value="{{ $model->phone }}" type="text" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="form_email">Email</label>
        <input id="form_email" name="email" value="{{ $model->email }}" type="text" class="form-control">
    </div>
    
    <div class="form-group">
        <a class="btn btn-default btn-sm back-link" href="javascript:void(0);"><i class="far fa-arrow-alt-circle-left"></i></a>
        <button type="submit" class="btn btn-sm btn-success">Сохранить</button>
    </div>
</form>
@endsection