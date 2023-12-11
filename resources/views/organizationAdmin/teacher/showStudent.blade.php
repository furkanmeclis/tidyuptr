@php
    $html_tag_data = [];
    $title = 'Öğrenciler';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.teacher.index') => 'Öğretmenler',
        $teacher ? route('organizationAdmin.teacher.show', $teacher->id) : route('organizationAdmin.teacher.index') => 'Öğretmeni Görüntüle',
        route('organizationAdmin.student.index') => 'Öğrenciler',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/bootstrap-submenu.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js"></script>
    <script src="/js/organizationAdmin/students/all.js"></script>
    <script src="/js/organizationAdmin/students/helper.js"></script>
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

                <!-- Content Start -->
                <div class="data-table-rows slim">
                    <!-- Controls Start -->
                    <div class="row">
                        <!-- Search Start -->
                        <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
                            <div
                                class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                <input class="form-control datatable-search" placeholder="Ara"
                                       data-datatable="#datatableRows" />
                                <span class="search-magnifier-icon">
                                    <i data-acorn-icon="search"></i>
                                </span>
                                <span class="search-delete-icon d-none">
                                    <i data-acorn-icon="close"></i>
                                </span>
                            </div>
                        </div>
                        <!-- Search End -->

                        <div class="col-sm-12 col-md-7 col-lg-9 col-xxl-10 text-end mb-1">

                            <div class="d-inline-block">
                                <!-- Print Button Start -->
                                <a href="{{ route('organizationAdmin.student.create') }}"
                                   class="btn btn-icon btn-icon-only btn-foreground-alternate shadow "
                                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0"
                                   title="Yeni Kurum Ekle" type="button">
                                    <i data-acorn-icon="plus"></i>
                                </a>
                                <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                                        data-datatable="#datatableRows" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-delay="0" title="Yazdır" type="button">
                                    <i data-acorn-icon="print"></i>
                                </button>
                                <!-- Print Button End -->

                                <!-- Export Dropdown Start -->
                                <div class="d-inline-block datatable-export" data-datatable="#datatableRows">
                                    <button class="btn p-0" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                        <span class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown"
                                              data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip"
                                              title="İndir">
                                            <i data-acorn-icon="download"></i>
                                        </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <button class="dropdown-item export-copy" type="button">Kopyala</button>
                                        <button class="dropdown-item export-excel" type="button">Excel</button>
                                        <button class="dropdown-item export-cvs" type="button">Csv</button>
                                    </div>
                                </div>
                                <!-- Export Dropdown End -->

                                <!-- Length Start -->
                                <div class="dropdown-as-select d-inline-block datatable-length"
                                     data-datatable="#datatableRows" data-childSelector="span">
                                    <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                        <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip"
                                              data-bs-placement="top" data-bs-delay="0" title="Satır Sayısı">
                                            5 Öğrenci
                                        </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <a class="dropdown-item active" href="#">5 Öğrenci</a>
                                        <a class="dropdown-item" href="#">10 Öğrenci</a>
                                        <a class="dropdown-item" href="#">20 Öğrenci</a>
                                    </div>
                                </div>
                                <!-- Length End -->
                            </div>
                        </div>
                    </div>
                    <!-- Controls End -->

                    <!-- Table Start -->
                    <div class="data-table-responsive-wrapper">
                        <table id="datatableRows" class="data-table">
                            <thead>
                            <tr>
                                <th class="text-muted text-uppercase">Öğrenci Adı</th>
                                <th class="text-muted text-uppercase">Email Adresi</th>
                                <th class="empty">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($students as $student)
                                @php $student = $student->student(); @endphp
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td><a href="{{ route('organizationAdmin.student.show', $student->id) }}"
                                           class="btn mb-1 btn-sm btn-icon btn-icon-only btn-success shadow "
                                           data-bs-toggle="tooltip" data-bs-placement="left" data-bs-delay="0"
                                           title="Görüntüle" type="button">
                                            <i data-acorn-icon="eye"></i>
                                        </a> <a href="{{ route('organizationAdmin.student.exam.index', $student->id) }}"
                                                class="btn mb-1 btn-sm btn-icon btn-icon-only btn-info shadow "
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Sınavları Görüntüle"
                                                type="button">
                                            <i data-acorn-icon="quiz"></i>
                                        </a> <a href="{{ route('organizationAdmin.student.edit', $student->id) }}"
                                                class="btn mb-1 btn-sm btn-icon btn-icon-only btn-warning shadow "
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"
                                                type="button">
                                            <i data-acorn-icon="edit"></i>
                                        </a> <a href="{{ route('organizationAdmin.student.destroy', $student->id) }}"
                                                class="btn  mb-1 btn-sm btn-icon btn-icon-only btn-danger shadow delete-student-btn"
                                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-delay="0"
                                                title="Sil" type="button">
                                            <i data-acorn-icon="bin"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Table End -->
                </div>
                <!-- Content End -->


            </div>
        </div>
    </div>
@endsection
