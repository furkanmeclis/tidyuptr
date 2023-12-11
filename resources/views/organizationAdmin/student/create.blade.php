@php
    $html_tag_data = [];
    $title = 'Yeni Öğrenci Ekle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.student.index') => 'Öğrenciler',
        route('organizationAdmin.student.create') => 'Yeni Öğrenci Ekle',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection
@section('js_vendor')
    <script src="/js/cs/wizard.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/imask.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/organizationAdmin/students/create.js"></script>
    <script src="/js/organizationAdmin/students/create.table.js"></script>
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
                <div class="mb-5 wizard" id="studentWizard">
                    <div class="border-0 pb-0">
                        <ul class="nav nav-tabs justify-content-center" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-center" href="#basicSecond" role="tab">
                                    <div class="mb-1 title d-none d-sm-block">Öğretmen Seçimi</div>
                                    <div class="text-small description d-none d-md-block">Öğrencinin Ekleneceği Öğretmen
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-center" href="#basicThird" role="tab">
                                    <div class="mb-1 title d-none d-sm-block">Temel Bilgiler</div>
                                    <div class="text-small description d-none d-md-block">Öğrencinin Hakkında Temel Bilgiler
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
                                        data-ajax-url="{{ route('organizationAdmin.teacher.getTeachers') }}">
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-uppercase">#Id</th>
                                                <th class="text-muted text-uppercase">Öğretmen Adı</th>
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
                                    <form id="createStudent" action="{{ route('organizationAdmin.student.store') }}"
                                        method="POST" class="tooltip-end-bottom" novalidate>
                                        <div class="mb-3 filled ">
                                            <i data-acorn-icon="online-class"></i>
                                            <input class="form-control" placeholder="Öğrenci Adı" name="name" />

                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="content"></i>
                                            <input class="form-control" type="number" placeholder="Kimlik Numarası"
                                                   name="identity_number" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="medal"></i>
                                            <input class="form-control" type="number" placeholder="Sınıfı"
                                                   name="grade" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="at-sign"></i>
                                            <input class="form-control" placeholder="Email Adresi" name="email" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="lock-off"></i>
                                            <input class="form-control" type="password" id="password"
                                                placeholder="Şifre" name="password" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="lock-off"></i>
                                            <input class="form-control" type="password" placeholder="Şifreyi Onaylayın"
                                                name="confirmPassword" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="phone"></i>
                                            <input class="form-control" id="phoneNumber" placeholder="Telefon Numarası"
                                                name="phone" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <textarea placeholder="Adres" name="address" class="form-control" rows="3"></textarea>
                                            <i data-acorn-icon="notebook-1"></i>
                                        </div>
                                        <input type="hidden" name="organization_id" value="0">
                                        <input type="hidden" name="teacher_id">

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
