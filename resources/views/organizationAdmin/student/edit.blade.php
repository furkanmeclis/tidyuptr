@php
    $html_tag_data = [];
    $title = 'Öğrenciyi Düzenle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.student.index') => 'Öğrenciler',
         $student ? route('organizationAdmin.student.show', $student->id) : route('organizationAdmin.student.index') => 'Öğrenciyi Görüntüle',
         $student ? route('organizationAdmin.student.edit', $student->id) : route('organizationAdmin.student.index') => 'Öğrenciyi Düzenle'
         ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection
@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/imask.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/organizationAdmin/students/edit.table.js"></script>
    <script src="/js/organizationAdmin/students/edit.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($student)
                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{ $student->name }} Adlı Öğrenciyi Düzenle</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <div>
                        <div class="row">
                            <div class="col-md-8">

                                <h1 class=" h3">Temel Ayarlar</h1>
                                <div class="card mb-5">
                                    <div class="card-body">
                                        <form id="updateStudent"
                                            action="{{ route('organizationAdmin.student.update', $student->id) }}"
                                            method="POST" class="tooltip-end-bottom" novalidate>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="school"></i>
                                                <input class="form-control" placeholder="Kurum Adı"
                                                    value="{{ $student->name }}" name="name" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="content"></i>
                                                <input class="form-control" value="{{ $student->identity_number }}" type="number" placeholder="Kimlik Numarası"
                                                       name="identity_number" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="medal"></i>
                                                <input class="form-control" value="{{ $student->grade }}" type="number" placeholder="Sınıfı"
                                                       name="grade" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="at-sign"></i>
                                                <input class="form-control" placeholder="Email Adresi"
                                                    value="{{ $student->email }}" name="email" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="phone"></i>
                                                <input class="form-control" id="phoneNumber"
                                                    value="{{ $student->phone }}" placeholder="Telefon Numarası"
                                                    name="phone" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <textarea placeholder="Adres" name="address" class="form-control" rows="3">{{ $student->address }}</textarea>
                                                <i data-acorn-icon="notebook-1"></i>
                                            </div>
                                            <button class="btn btn-primary" type="submit">Güncelle</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" id="updatePassword">
                                <h1 class=" h3">Şifre Ayarları</h1>
                                <div class="card mb-5">
                                    <div class="card-body">
                                        <form id="updateStudentPassword"
                                            action="{{ route('organizationAdmin.student.updatePassword', $student->id) }}"
                                            method="PUT" class="tooltip-end-bottom" novalidate>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="lock-off"></i>
                                                <input class="form-control" id="password" type="password"
                                                    placeholder="Yeni Şifre" name="password" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="lock-off"></i>
                                                <input class="form-control" type="password"
                                                    placeholder="Yeni Şifreyi Onaylayın" name="confirmPassword" />
                                            </div>
                                            <button class="btn btn-primary" type="submit">Şifreyi Güncelle</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h1 class=" h3">Öğretmen Seçimi</h1>
                                <div class="data-table-rows slim">
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
                                    <div class="data-table-responsive-wrapper">
                                        <table id="datatableRows2" class="data-table"
                                               data-ajax-url="{{ route('organizationAdmin.teacher.getTeachers',$student->id) }}">
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
                                </div>
                                <div class="text-center mt-3">
                                    <form id="updateTeacher" action="{{ route('organizationAdmin.student.updateTeacher',$student->id) }}">
                                        <input type="hidden" name="teacher_id">
                                    <button class="btn btn-primary" type="submit">
                                        Kaydet
                                    </button></form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content End -->
                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Güncellenecek Kayıt Bulunamadı Öğrenciler Sayfası İçin <a
                            href="{{ route('organizationAdmin.student.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
