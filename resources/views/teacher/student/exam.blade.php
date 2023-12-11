@php
    $html_tag_data = [];
    $title = 'Sınavlar';
    $description = '';
    $breadcrumbs = [
        route('systemAdmin.index') => 'Anasayfa',
        route('systemAdmin.student.index') => 'Öğrenciler',
        $student ? route('systemAdmin.student.show',$student->id) : '#' =>'Öğrenciyi Görüntüle',
        $student ? route('systemAdmin.student.exam',$student->id) : '#' => 'Sınavlar',
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
    <script src="/js/systemAdmin/exams/all.js"></script>
    <script src="/js/systemAdmin/exams/helper.js"></script>
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
                            <h1 class="mb-0 pb-0 display-4" id="title">{{$student ? $student->name.' Adlı Öğrenciye Ait Sınavlar' : 'Sınavlar'}}</h1>
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
                                <button
                                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                                    data-datatable="#datatableRows" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-delay="0" title="Yazdır" type="button">
                                    <i data-acorn-icon="print"></i>
                                </button>
                                <!-- Print Button End -->

                                <!-- Export Dropdown Start -->
                                <div class="d-inline-block datatable-export" data-datatable="#datatableRows">
                                    <button class="btn p-0" data-bs-toggle="dropdown" type="button"
                                            data-bs-offset="0,3">
                                            <span
                                                class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown"
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
                                            <span class="btn btn-foreground-alternate dropdown-toggle"
                                                  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0"
                                                  title="Satır Sayısı">
                                                5 Kayıt
                                            </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <a class="dropdown-item active" href="#">5 Kayıt</a>
                                        <a class="dropdown-item" href="#">10 Kayıt</a>
                                        <a class="dropdown-item" href="#">20 Kayıt</a>
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
                                <th class="text-muted text-uppercase">#ID</th>
                                <th class="text-muted text-uppercase">Sınav Tarihi</th>
                                <th class="empty">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($exams as $exam)
                                <tr>
                                    <td>{{ $exam->id }}</td>
                                    <td>{{ $exam->date() }}</td>
                                    <td>
                                        <a
                                            href="{{ route('teacher.exam.show', $exam->id) }}"
                                            class="btn mb-1 btn-sm btn-icon btn-icon-only btn-success shadow show-exam-btn"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Görüntüle"
                                            type="button">
                                            <i data-acorn-icon="eye"></i>
                                        </a> <a
                                            href="{{ route('teacher.student.downloadPdf', $exam->id) }}"
                                            class="btn mb-1 btn-sm btn-icon btn-icon-only btn-info shadow"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Görüntüle"
                                            type="button">
                                            <i data-acorn-icon="cloud-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Table End -->
                </div>
                <div class="modal fade" id="xlExample" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Sınav Sonucu</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="examResultArea"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
