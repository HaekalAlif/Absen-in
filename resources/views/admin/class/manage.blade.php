@extends('admin.layouts.layout')

@section('admin_page_title')
Manage Classes - Admin Panel
@endsection

@section('admin_layout')
<div class="container">
    <h3>Manage Classes</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('class.create') }}" class="btn btn-primary mb-3">Add New Class</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Batch Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classes as $class)
            <tr>
                <td>{{ $class->id }}</td>
                <td>{{ $class->name }}</td>
                <td>{{ $class->batch_year }}</td>
                <td>
                    <a href="{{ route('class.edit', $class->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('class.destroy', $class->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?');">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
