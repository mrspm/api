@extends('admin.layouts.main')

@php $title = $h1 = "Заявка №" . $model->id @endphp
@section('title'){{ $title }}@endsection
@section('h1'){{ $h1 }}@endsection

@section('content')
<table class="table table-striped projects">
    <tr>
        <td>Номер</td>
        <td>{{ $model->id }}</td>
    </tr>
    <tr>
        <td>Имя</td>
        <td>{{ $model->name }}</td>
    </tr>
    <tr>
        <td>Телефон</td>
        <td>{{ $model->phone }}</td>
    </tr>
    <tr>
        <td>Email</td>
        <td>{{ $model->email ?? '' }}</td>
    </tr>
    <tr>
        <td>Дата</td>
        <td>{{ date('d.m.Y H:i', strtotime($model->created_at)) }}</td>            
    </tr>
</table>

<div class="action-div">
    <a class="btn btn-default btn-sm back-link" href="javascript:void(0);"><i class="far fa-arrow-alt-circle-left"></i></a>
    <a class="btn btn-info btn-sm" href="{{ route('orders.edit', [$model]) }}"><i class="fas fa-pencil-alt"></i></a>
    <form action="{{ route('orders.destroy', [$model]) }}" method="post" class="del-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
    </form>
</div>
@endsection