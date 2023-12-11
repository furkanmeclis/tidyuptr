@php
    $html_tag_data = [];
    $title = 'Yeni Sınav Ekle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.batchExam.index') => 'Sınavlar',
        route('organizationAdmin.batchExam.create') => 'Yeni Sınav Ekle'
        ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/tagify.css"/>
@endsection
@section('js_vendor')
    <script src="/js/vendor/tagify.min.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/organizationAdmin/exams/create.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-0 pb-0 display-4">{{ $title }}</h1>
                        @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                    </div>
                </section>
                <!-- Title End -->

                <!-- Content Start -->
                <div>
                    <section class="scroll-section" id="basic">

                        <!-- Filled Start -->
                        <section class="scroll-section" id="filled">
                            <div class="card mb-5">
                                <div class="card-body">
                                    <form id="createExam" action="{{ route('organizationAdmin.batchExam.store') }}"
                                          method="POST" class="tooltip-end-bottom" novalidate>
                                        <div class="alert alert-warning mb-3">
                                            <span class="alert-message">
                                                Optik Tarayıcıdan Okuma Yapacak İseniz Ders Sırasına Göre Seçmelisiniz.
                                                Örnek Önce Türkçe,Sonra Sosyal Bilgiler, Sonra Matematik, Sonra Fen Bilgisi Gibi.
                                            </span>
                                        </div>
                                        <div class="mb-3 filled ">
                                            <i data-acorn-icon="quiz"></i>
                                            <input class="form-control" placeholder="Sınav Adı" name="name" />
                                        </div>
                                        <input id="lessonsSelect" data-lessons-href="{{route('organizationAdmin.batchExam.getLessons')}}" class="tagify--outside" name="lessons" placeholder="Dersler" />
                                        <button class="btn btn-primary mt-3" type="submit">Ekle</button>
                                    </form>
                                </div>
                            </div>
                        </section>
                </div>
                <!-- Content End -->
            </div>
        </div>
    </div>
@endsection
