@php
    $html_tag_data = [];
    $title = 'Yeni Sınıf Ekle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.class.index') => 'Sınıflar',
        route('organizationAdmin.class.create') => 'Yeni Sınıf Ekle',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2.min.css"/>
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css"/>
@endsection
@section('js_vendor')
    <script src="/js/cs/wizard.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/imask.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/organizationAdmin/class/create.table.js"></script>
    <script src="/js/organizationAdmin/class/create.js"></script>
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
            <div class="col-md-12">
                <div class="mb-5 wizard" id="classWizard">
                    <div class="border-0 pb-0">
                        <ul class="nav nav-tabs justify-content-center" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-center" href="#basicSecond" role="tab">
                                    <div class="mb-1 title d-none d-sm-block">Öğrenci Seçimi</div>
                                    <div class="text-small description d-none d-md-block">Sınıfa Eklenecek Öğrenciler
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-center" href="#basicThird" role="tab">
                                    <div class="mb-1 title d-none d-sm-block">Temel Bilgiler</div>
                                    <div class="text-small description d-none d-md-block">Sınıf Hakkında Temel Bilgiler
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content mt-5 mb-5">
                        <div class="tab-pane fade" id="basicSecond" role="tabpanel">
                            <div class="data-table-rows slim">
                                <!-- Controls Start -->
                                <div class="row">
                                    <!-- Search Start -->
                                    <div class="col mb-1">
                                        <div
                                            class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                            <input class="form-control datatable-search" placeholder="Ara"
                                                   data-datatable="#datatableRows2" />
                                            <span class="search-magnifier-icon">
                                                <i data-acorn-icon="search"></i>
                                            </span>
                                            <span class="search-delete-icon d-none">
                                                <i data-acorn-icon="close"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Search End -->


                                </div>
                                <!-- Controls End -->

                                <!-- Table Start -->
                                <div class="data-table-responsive-wrapper">
                                    <table id="datatableRows2" class="data-table"
                                           data-ajax-url="{{ route('organizationAdmin.student.getStudents') }}">
                                        <thead>
                                        <tr>
                                            <th class="text-muted text-uppercase">#Id</th>
                                            <th class="text-muted text-uppercase">Öğrenci Adı</th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table End -->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="basicThird" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <form id="createClass" action="{{ route('organizationAdmin.class.store') }}"
                                          method="POST" class="tooltip-end-bottom" novalidate>
                                        <div class="mb-3 filled ">
                                            <i data-acorn-icon="online-class"></i>
                                            <input class="form-control" placeholder="Sınıf Adı" name="name" />

                                        </div>
                                        <div class="filled mb-3 w-100 " >
                                            <i data-acorn-icon="lecture"></i>
                                            <select class="select2"
                                                    data-placeholder="Sınıf Öğretmeni Seçimi" name="teacher_id">
                                                @foreach($teachers as $teacher)
                                                    <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-primary" type="submit">Ekle</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center border-0 pt-1">
                        <button class="btn btn-icon btn-icon-start btn-outline-primary btn-prev" type="button">
                            <i data-acorn-icon="chevron-left"></i>
                            <span>Geri</span>
                        </button>
                        <button class="btn btn-icon btn-icon-end btn-outline-primary btn-next" type="button">
                            <span>İleri</span>
                            <i data-acorn-icon="chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
