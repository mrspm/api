@extends('admin.layouts.main')

@php $title = $h1 = "Заявки" @endphp
@section('title'){{ $title }}@endsection
@section('h1'){{ $h1 }}@endsection

@section('content')
<table class="table table-striped projects">
    <thead>
        <tr>
            <th>№</th>
            <th>Имя</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Дата</th>
            <th><a class="btn btn-success btn-sm" href="{{ route('orders.create') }}">Новая заявка</a></th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->phone }}</td>
            <td>{{ $item->email ?? '' }}</td>
            <td>{{ date('d.m.Y H:i', strtotime($item->created_at)) }}</td>            
            <td class="table-actions">
                <a class="btn btn-primary btn-sm" href="{{ route('orders.show', [$item]) }}"><i class="fas fa-eye"></i></a>
                <a class="btn btn-info btn-sm" href="{{ route('orders.edit', [$item]) }}"><i class="fas fa-pencil-alt"></i></a>
                <form action="{{ route('orders.destroy', [$item]) }}" method="post" class="del-form">
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
