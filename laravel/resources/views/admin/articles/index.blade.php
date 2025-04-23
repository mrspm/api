@extends('admin.layouts.main')

@php $title = $h1 = "Статьи" @endphp
@section('title'){{ $title }}@endsection
@section('h1'){{ $h1 }}@endsection

@section('content')
<table class="table table-striped projects">
    <thead>
        <tr>
            <th>Название</th>
            <th>К-во просмотров</th>
            <th>Опубликовано?</th>
            <th>Дата</th>
            <th><a class="btn btn-success btn-sm" href="{{ route('articles.create') }}">Новая статья</a></th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->viewed }}</td>
            <td>{{ $item->published ? 'Да' : 'Нет' }}</td>
            <td>{{ date('d.m.Y', strtotime($item->created_at)) }}</td>
            <td class="table-actions">
                <a class="btn btn-info btn-sm" href="{{ route('articles.edit', [$item]) }}"><i class="fas fa-pencil-alt"></i></a>
                <form action="{{ route('articles.destroy', [$item]) }}" method="post" class="del-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $items->links() }}
@endsection
