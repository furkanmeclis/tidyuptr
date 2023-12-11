@php
    $html_tag_data = [];
    $title = 'Ders Programım';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.student.index') => 'Sınıflar',
        route('organizationAdmin.student.create') => 'Sınıfı Görüntüle',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])
@section('css')
@endsection
@section('js_vendor')
@endsection
@section('js_page')
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <section class="scroll-section" id="title">
                <div class="page-title-container">
                    <h1 class="mb-0 pb-0 display-4">{{ $title }}</h1>
                    @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                </div>
            </section>
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                        <a href="{{route('student.schedule.create')}}" class="btn btn-primary btn-sm">Yeni Oluştur</a>
                </div>
            </div>
                @component('components.timetable',["timetable" => $timeTable])
                @endcomponent
        </div>
    </div>
@endsection
