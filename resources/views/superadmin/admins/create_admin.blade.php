@extends('layouts.app') {{-- Sesuaikan layout kamu --}}

@section('title', 'Create Admin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Create New Admin</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('superadmin.admins.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="username">Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="avatar">Avatar (optional)</label>
            <input type="file" name="avatar" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-success">Create Admin</button>
        <a href="{{ route('superadmin.admins.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
