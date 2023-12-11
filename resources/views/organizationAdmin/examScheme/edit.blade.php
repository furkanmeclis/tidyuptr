@php
    $html_tag_data = [];
    $title = 'Şemayı Düzenle';
    $description = '';
    $breadcrumbs = [];
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
    <script src="/js/systemAdmin/exams/edit.js"></script>
    <script src="/js/organizationAdmin/exams/create.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($scheme)

                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{$scheme->name}} Adlı Şemayı Düzenle</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <div class="card">
                        <form id="updateExamResult"
                              action="{{ route('organizationAdmin.examScheme.update', $scheme->id) }}"
                              method="POST" class="tooltip-end-bottom card-body" novalidate autocomplete="off">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 filled">
                                        <i data-acorn-icon="quiz"></i>
                                        <input type="text" class="form-control required"
                                               placeholder="Şema İsmi" autocomplete="off"
                                               name="name"  value="{{$scheme->name}}" />
                                    </div>
                                    <div class="mb-3 filled ">
                                        <i data-acorn-icon="medal"></i>
                                        <input class="form-control" placeholder="Sınıf" value="{{$scheme->grade}}" name="grade" />
                                    </div>
                                    <input
                                        id="lessonsSelect"
                                        data-lessons-href="{{route('organizationAdmin.batchExam.getLessons')}}"
                                        class="tagify--outside"
                                        name="lessons"
                                        placeholder="Dersler"
                                        value="{{json_encode($scheme->lessons()->map(function ($lesson) {
                                            return [
                                                    'id' => $lesson->id,
                                                    'value' => $lesson->name." - ".$lesson->grade,
                                                ];
                                        }))}}"
                                    />
                                </div>
                                <div class="col-md-12 text-center mt-3">
                                    <button class="btn btn-primary" type="submit">Güncelle</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Content End -->

                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Şema Bulunamadı Şemalar Sayfası İçin <a
                            href="{{ route('organizationAdmin.examScheme.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
