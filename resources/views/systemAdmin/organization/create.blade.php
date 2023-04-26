@php
    $html_tag_data = [];
    $title = 'Yeni Kurum Ekle';
    $description = '';
    $breadcrumbs = [route('systemAdmin.index') => 'Anasayfa', route('systemAdmin.organization.index') => 'Kurumlar', route('systemAdmin.organization.create') => 'Yeni Kurum Ekle'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection
@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/imask.js"></script>
@endsection
@section('js_page')
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/systemAdmin/organizations/create.js"></script>
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
                <div>
                    <section class="scroll-section" id="basic">

                        <!-- Filled Start -->
                        <section class="scroll-section" id="filled">
                            <div class="card mb-5">
                                <div class="card-body">
                                    <form id="createOrganization" action="{{ route('systemAdmin.organization.store') }}"
                                        method="POST" class="tooltip-end-bottom" novalidate>
                                        <div class="mb-3 filled ">
                                            <i data-acorn-icon="school"></i>
                                            <input class="form-control" placeholder="Kurum Adı" name="name" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="at-sign"></i>
                                            <input class="form-control" placeholder="Email Adresi" name="email" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="phone"></i>
                                            <input class="form-control" id="phoneNumber" placeholder="Telefon Numarası"
                                                name="phone" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="lock-off"></i>
                                            <input class="form-control" type="password" id="password" placeholder="Şifre"
                                                name="password" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="lock-off"></i>
                                            <input class="form-control" type="password" placeholder="Şifreyi Onaylayın"
                                                name="confirmPassword" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="calendar"></i>
                                            <input type="text" class="form-control" placeholder="Lisans Başlangıç Tarihi"
                                                id="datePickerCreateOrganizationLicenseStartDate" name="licenseStartDate" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <i data-acorn-icon="calendar"></i>
                                            <input type="text" class="form-control" placeholder="Lisans Sona Erme Tarihi"
                                                id="datePickerCreateOrganizationLicenseExpireDate"
                                                name="licenseExpireDate" />
                                        </div>
                                        <div class="mb-3 filled">
                                            <textarea placeholder="Adres" name="address" class="form-control" rows="3"></textarea>
                                            <i data-acorn-icon="notebook-1"></i>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Ekle</button>
                                    </form>
                                </div>
                            </div>
                        </section>
                </div>
                <!-- Content End -->
            </div>
        </div>
    </div>
@endsection
