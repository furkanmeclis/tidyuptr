@php
    $html_tag_data = [];
    $title = 'Öğretmeni Düzenle';
    $description = '';
    $breadcrumbs = [
        route('systemAdmin.index') => 'Anasayfa',
        route('systemAdmin.teacher.index') => 'Öğretmenler',
        $teacher ? route('systemAdmin.teacher.edit', $teacher->id) : route('systemAdmin.teacher.index') => 'Öğretmeni Düzenle',
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
    <script src="/js/systemAdmin/teachers/custom.datatable.extend.js"></script>
    <script src="/js/systemAdmin/teachers/edit.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($teacher)
                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{ $teacher->name }} Adlı Öğretmeni Düzenle</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <div>
                        <div class="row mb-3">
                            <div class="col-md-8">

                                <h1 class=" h3">Temel Ayarlar</h1>
                                <div class="card mb-5">
                                    <div class="card-body">
                                        <form id="updateTeacher"
                                            action="{{ route('systemAdmin.teacher.update', $teacher->id) }}" method="POST"
                                            class="tooltip-end-bottom" novalidate>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="lecture"></i>
                                                <input class="form-control" placeholder="Öğretmen Adı"
                                                    value="{{ $teacher->name }}" name="name" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="at-sign"></i>
                                                <input class="form-control" placeholder="Email Adresi"
                                                    value="{{ $teacher->email }}" name="email" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="phone"></i>
                                                <input class="form-control" id="phoneNumber" value="{{ $teacher->phone }}"
                                                    placeholder="Telefon Numarası" name="phone" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="online-class"></i>
                                                <input class="form-control" type="number"
                                                    value="{{ $teacher->max_students }}" placeholder="Öğrenci Kapasitesi"
                                                    name="max_students" />
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="is_mentor" type="checkbox" id="flexSwitchCheckDefault">
                                                <label class="form-check-label" for="flexSwitchCheckDefault">Mentör Öğretmen</label>
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
                                        <form id="updateTeacherPassword"
                                            action="{{ route('systemAdmin.teacher.updatePassword', $teacher->id) }}"
                                            method="POST" class="tooltip-end-bottom" novalidate>
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
                        </div>
                        <hr>
                        <div class="data-table-rows slim">

                            <div class="col-md-12" id="editOrganizations">
                                <h1 class="h3">Kurumlar</h1>
                                <p class="text-small text-muted">Öğretmenin Tanımlandığı Kurumlar</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <button class="btn btn-primary shadow " type="button"
                                        action="{{ route('systemAdmin.teacher.updateOrganizations', $teacher->id) }}"
                                        id="saveButton">
                                        <span class="d-flex align-items-center">
                                            <i data-acorn-icon="save" class="me-1"></i> <span>Kaydet</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div
                                        class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                        <input class="form-control datatable-search" placeholder="Kurumlarda Ara"
                                            data-datatable="#datatableRows" />
                                        <span class="search-magnifier-icon">
                                            <i data-acorn-icon="search"></i>
                                        </span>
                                        <span class="search-delete-icon d-none">
                                            <i data-acorn-icon="close"></i>
                                        </span>
                                    </div>
                                </div>

                            </div>
                            <div class="data-table-responsive-wrapper">
                                <table id="datatableRows" class="data-table">
                                    <thead>
                                        <tr>

                                            <th class="text-small text-muted text-uppercase">Kurum Id</th>
                                            <th class="text-small text-muted text-uppercase">Kurum Adı</th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($organizations as $organization)
                                            <tr class="{{ $organization->selected ? 'selected' : '' }}">
                                                <td>{{ $organization->id }}</td>
                                                <td>{{ $organization->name }}</td>
                                                <td>{{ $organization->selected ? 'selected' : '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Content End -->
                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Güncellenecek Kayıt Bulunamadı Öğretmenler Sayfası İçin <a
                            href="{{ route('systemAdmin.teacher.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
