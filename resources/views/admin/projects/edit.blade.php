@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
    <header class="admin-page-head">
        <div>
            <span class="eyebrow">Portfolio</span>
        <h1>EDIT PROJECT</h1>
        </div>
    </header>

    <form class="admin-card admin-form" method="post" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.projects._form', ['submitLabel' => 'UPDATE PROJECT'])
    </form>
@endsection
