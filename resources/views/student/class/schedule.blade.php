@php
    $html_tag_data = [];
    $title = 'Sınıf Ders Programı';
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
                    <h1 class="mb-0 pb-0 display-4">{{ $title }} {{$class->name}}</h1>
                    @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                </div>
            </section>
            @component('components.timetable',["timetable" => $timeTable])
            @endcomponent
        </div>
    </div>
@endsection
