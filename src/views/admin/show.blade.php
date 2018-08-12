@extends('multiauth::layouts.app') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Admin List
                    <span class="float-right">
                        <a href="/admin/register" class="btn btn-sm btn-success">New Admin</a>
                    </span>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($admins as $admin)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $admin->name }}
                            @foreach ($admin->roles as $role)
                                <span class="badge badge-primary badge-pill">{{ $role->name }}</span>
                            @endforeach
                            <div class="float-right">
                                <a href="" class="btn btn-sm btn-secondary mr-3" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $admin->id }}').submit();">Delete</a>
                                <form id="delete-form-{{ $admin->id }}" action="/admin/{{ $admin->id }}" method="POST" style="display: none;">
                                    @csrf @method('delete')
                                </form>
                            
                                <a href="/admin/{{ $admin->id }}/edit" class="btn btn-sm btn-primary mr-3">Edit</a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection