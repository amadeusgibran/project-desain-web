@extends('layouts.admin')

@section('title', 'Create Project')

@section('content')
    <header class="admin-page-head">
        <div>
            <span class="eyebrow">Portfolio</span>
        <h1>ADD PROJECT</h1>
        </div>
    </header>

    <form class="admin-card admin-form" method="post" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
        @include('admin.projects._form', ['submitLabel' => 'SAVE PROJECT'])
    </form>
@endsection
