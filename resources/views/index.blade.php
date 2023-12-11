@php
    $title = env('APP_NAME');
    $description = 'Coming Soon Page'
@endphp
@extends('layout_full',['title'=>$title, 'description'=>$description])
@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
@endsection

@section('content_left')
@endsection

@section('content_right')
    <div class="sw-lg-80 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
        <div class="sw-lg-70 px-5">
            <div class="mb-3">
                <h2 class="cta-1 mb-0 text-primary">{{env('APP_NAME')}} Yönetim Linkleri</h2>
            </div>
            <div class="list-group mb-2">
                <a href="{{route('systemAdmin.login')}}" class="list-group-item list-group-item-action">Sistem Yöneticisi</a>
                <a href="{{route('organizationAdmin.login')}}" class="list-group-item list-group-item-action">Kurum Yöneticisi</a>
                <a href="{{route('teacher.login')}}" class="list-group-item list-group-item-action">Öğretmen</a>
                <a href="{{route('student.login')}}" class="list-group-item list-group-item-action">Öğrenci</a>
            </div>
        </div>
    </div>
@endsection
