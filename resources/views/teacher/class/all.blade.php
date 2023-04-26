@php
    $html_tag_data = [];
    $title = 'Sınıfım';
    $description = 'Sınıfım';
    $breadcrumbs = [route('teacher.index') => 'Anasayfa', route('teacher.class.index') => 'Sınıfım'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/quill.bubble.css"/>
@endsection

@section('js_vendor')
    <script src="/js/vendor/quill.min.js"></script>
    <script src="/js/vendor/quill.active.js"></script>
@endsection

@section('js_page')
    <script src="/js/teacher/class/all.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title and Top Buttons Start -->
                <div class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-12">
                            <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                        <!-- Title End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->



            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        İÇERİK
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="filled custom-control-container editor-container">
                                    <div class="html-editor sh-30" id="quillEditorFilled"></div>
                                    <i data-acorn-icon="message"></i>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <form id="createMessage" action="{{route('teacher.class.store')}}">
                                    <input type="file" name="file" class="d-none" id="fileInput">
                                    <input type="hidden" name="description">
                                <button class="btn btn-warning m-3 btn-block" id="addFile" type="button">Dosya Ekle</button>
                                <button class="btn btn-primary mx-3 btn-block" type="submit">Gönder</button>
                                <div id="fileSelected" style="display: none">
                                    <hr>
                                    <p id="fileName">Dosya Seçilmedi</p>
                                    <button class="btn mb-1 btn-sm btn-icon btn-icon-only btn-danger shadow" type="button" id="removeFile">
                                        <i data-acorn-icon="bin"></i>
                                    </button>
                                </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
@endsection
