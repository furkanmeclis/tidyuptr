@php
    $html_tag_data = [];
    $title = 'Yeni Lisans Ekle';
    $description = '';
    $breadcrumbs = [route('systemAdmin.index') => 'Anasayfa', route('systemAdmin.organization.index') => 'Kurumlar', $organization ? route('systemAdmin.organization.edit', $organization->id) : route('systemAdmin.organization.index') => 'Kurumu Düzenle', $organization ? route('systemAdmin.organization.license.index', $organization->id) : route('systemAdmin.organization.index') => 'Lisans Kayıtları', $organization ? route('systemAdmin.organization.license.create', $organization->id) : route('systemAdmin.organization.index') => 'Lisans Kaydı Ekle'];
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
    <script src="/js/systemAdmin/organizations/license.create.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($organization)
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
                                        <form autocomplete="off" id="createOrganizationLicense"
                                            action="{{ route('systemAdmin.organization.license.store', $organization->id) }}"
                                            method="POST" class="tooltip-end-bottom" novalidate>

                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="calendar"></i>
                                                <input type="text" class="form-control"
                                                    placeholder="Lisans Başlangıç Tarihi"
                                                    id="datePickerCreateOrganizationLicenseStartDate"
                                                    name="licenseStartDate" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="calendar"></i>
                                                <input type="text" class="form-control"
                                                    placeholder="Lisans Sona Erme Tarihi"
                                                    id="datePickerCreateOrganizationLicenseExpireDate"
                                                    name="licenseExpireDate" />
                                            </div>
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" name="active" type="checkbox"
                                                    id="flexSwitchCheckChecked" checked="">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">Lisansı
                                                    Aktifleştir</label>
                                            </div>
                                            <a href="{{ route('systemAdmin.organization.license.index', $organization->id) }}"
                                                class="btn btn-dark" type="button">Vazgeç</a>
                                            <button class="btn btn-primary" type="submit">Ekle</button>
                                        </form>
                                    </div>
                                </div>
                            </section>
                    </div>
                    <!-- Content End -->
                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Kurum Bulunamadı Kurumlar Sayfası İçin <a
                            href="{{ route('systemAdmin.organization.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
