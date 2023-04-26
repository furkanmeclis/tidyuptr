@php
    $html_tag_data = [];
    $title = 'Sınavı Düzenle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.batchExam.index') => 'Sınavlar',
        $batch ? route('organizationAdmin.batchExam.show', $batch->id) : route('organizationAdmin.batchExam.index') => $batch ? $batch->name : 'Sınav Grubu',
        $batch ? route('organizationAdmin.batchExam.edit', $batch->id) : route('organizationAdmin.batchExam.index') => 'Sınavı Düzenle',
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
    <script src="/js/systemAdmin/exams/edit.js"></script>
    <script src="/js/organizationAdmin/exams/create.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($batch)

                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{$batch->name}} Adlı Sınavı Düzenle</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <div class="card">
                        <form id="updateExamResult"
                              action="{{ route('organizationAdmin.batchExam.update', $batch->id) }}"
                              method="POST" class="tooltip-end-bottom card-body" novalidate autocomplete="off">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 filled">
                                    <i data-acorn-icon="quiz"></i>
                                    <input type="text" class="form-control required"
                                           placeholder="Sınav İsmi" autocomplete="off"
                                           name="name"  value="{{$batch->name}}" />
                                </div>
                                <input
                                    id="lessonsSelect"
                                    data-lessons-href="{{route('organizationAdmin.batchExam.getLessons')}}"
                                    class="tagify--outside"
                                    name="lessons"
                                    placeholder="Dersler"
                                    value="{{json_encode($lessons)}}"
                                />
                            </div>
                            <div class="col-md-12 text-center mt-3">
                                <a class="btn btn-dark me-3" href="{{ route('organizationAdmin.batchExam.show',$batch->id)  }}">Vazgeç</a>
                                <button class="btn btn-primary" type="submit">Güncelle</button>
                            </div>
                            </div>
                        </form>
                    </div>
                    <!-- Content End -->

                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Sınav Bulunamadı Sınavlar Sayfası İçin <a
                            href="{{ route('organizationAdmin.batchExam.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
