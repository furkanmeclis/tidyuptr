@php
    $html_tag_data = [];
    $title = 'Yeni Ders Ekle';
    $description = '';
    $breadcrumbs = [
        route('systemAdmin.index') => 'Anasayfa',
        route('systemAdmin.lesson.index') => 'Dersler',
        route('systemAdmin.lesson.create') => 'Yeni Ders Ekle',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
@endsection
@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/systemAdmin/lesson/create.js"></script>
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
                <form id="createLesson" autocomplete="off" action="{{ route('systemAdmin.lesson.store') }}" method="POST"
                    class="tooltip-end-bottom" novalidate>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <h5 class="mb-3">Ders Bilgileri</h5>
                                    <div class="mb-3 ">
                                        <input class="form-control" placeholder="Ders Adı" name="name" />
                                    </div>
                                    <div class="mb-3 ">
                                        <input class="form-control" type="number" min="1" max="20" placeholder="Sınıf" name="grade" />
                                    </div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <h5 class="mb-3">Dersin Konuları</h5>
                                    <div id="topicsCreate">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text font-weight-bold">Konu Adı:</span>
                                            <input type="text" class="form-control" name="topics[0][name]"
                                                placeholder="örn. Dalga Mekaniği">
                                            <span class="input-group-text font-weight-bold">Konu Katsayısı:</span>
                                            <input type="number" class="form-control float-input-js"
                                                name="topics[0][coefficient]" placeholder="örn 5.4">
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-warning" type="button" id="addTopic">Konu Ekle</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('systemAdmin.lesson.index') }}" class="btn btn-dark me-1">Vazgeç</a>
                                    <button class="btn btn-primary" type="submit">Dersi Ekle</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
