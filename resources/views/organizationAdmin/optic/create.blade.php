@php
    $html_tag_data = [];
    $title = 'Yeni Optik Şeması Ekle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.batchExam.index') => 'Sınavlar',
        route('organizationAdmin.batchExam.create') => 'Yeni Sınav Ekle'
        ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css"/>
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css"/>
@endsection
@section('js_vendor')
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/organizationAdmin/optic/create.js"></script>
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
                <div id="contentStart">
                        <!-- Filled Start -->
                        <section class="scroll-section" id="filled">
                            <div class="card mb-5">
                                <div class="card-body">
                                    <form id="uploadFmt" action="{{ route('organizationAdmin.optic.uploadFmt') }}"
                                          method="POST" class="tooltip-end-bottom" novalidate>
                                        <div class="mb-3 filled ">
                                            <i data-acorn-icon="quiz"></i>
                                            <input class="form-control" placeholder="Şema Adı" name="name" />
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="file" name="file" placeholder="Fmt Dosyanız" class="form-control" accept=".fmt">
                                            <button class="btn btn-gradient-secondary" id="firstSubmit" type="submit">Yükle</button>
                                        </div>
                                        <div class="row" id="confirmArea" style="display: none">
                                            <div class="col-md-6 mb-5">
                                                <div class="paper-for-fmt" id="paper"></div>
                                            </div>
                                            <div class="col-md-6 mb-5">
                                                <ul class="list-group"></ul>
                                            </div>
                                            <div class="col-md-12 d-flex justify-content-center align-items-center" id="submitBtnArea">
                                                <button type="submit" class="btn btn-gradient-primary">Onayla</button>
                                            </div>
                                        </div>
                                        <div id="inputs">

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                </div>
            </div>
        </div>
    </div>
@endsection
