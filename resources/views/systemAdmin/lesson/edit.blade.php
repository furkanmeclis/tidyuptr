@php
    $html_tag_data = [];
    $title = 'Dersi Düzenle';
    $description = '';
    $breadcrumbs = [
        route('systemAdmin.index') => 'Anasayfa',
        route('systemAdmin.lesson.index') => 'Dersler',
        $lesson ? route('systemAdmin.lesson.show', $lesson->id) : route('systemAdmin.lesson.index') => 'Dersi Görüntüle',
        $lesson ? route('systemAdmin.lesson.edit', $lesson->id) : route('systemAdmin.lesson.index') => 'Dersi Düzenle',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection
@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/imask.js"></script>
@endsection
@section('js_page')
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/systemAdmin/lesson/custom.datatable.extend.js"></script>
    <script src="/js/systemAdmin/lesson/edit.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($lesson)
                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{ $lesson->name }} Adlı Dersi Düzenle</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="card mb-5">
                                    <div class="card-body">
                                        <form id="updateLesson"
                                            action="{{ route('systemAdmin.lesson.update', $lesson->id) }}" method="POST"
                                            class="tooltip-end-bottom" novalidate>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="book"></i>
                                                <input class="form-control" placeholder="Dersin Adı"
                                                       value="{{ $lesson->name }}" name="name" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="graduation"></i>
                                                <input class="form-control" placeholder="Sınıf" type="number"
                                                       value="{{ $lesson->grade }}" min="0" max="20" name="grade" />
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <a href="{{ route('systemAdmin.lesson.index') }}"
                                                        class="btn btn-dark me-1">Vazgeç</a>
                                                    <button class="btn btn-primary" type="submit">Güncelle</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Content End -->
                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Güncellenecek Kayıt Bulunamadı Dersler Sayfası İçin <a
                            href="{{ route('systemAdmin.lesson.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
