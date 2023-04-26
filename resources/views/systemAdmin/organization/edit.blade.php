@php
    $html_tag_data = [];
    $title = 'Kurumu Düzenle';
    $description = '';
    $breadcrumbs = [route('systemAdmin.index') => 'Anasayfa', route('systemAdmin.organization.index') => 'Kurumlar', $organization ? route('systemAdmin.organization.edit', $organization->id) : route('systemAdmin.organization.index') => 'Kurumu Düzenle'];
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
    <script src="/js/systemAdmin/organizations/edit.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($organization)
                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{ $organization->name }} Adlı Kurumu Düzenle</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <div>
                        <div class="row">
                            <div class="col-md-8">

                                <h1 class=" h3">Temel Ayarlar</h1>
                                <div class="card mb-5">
                                    <div class="card-body">
                                        <form id="updateOrganization"
                                            action="{{ route('systemAdmin.organization.update', $organization->id) }}"
                                            method="POST" class="tooltip-end-bottom" novalidate>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="school"></i>
                                                <input class="form-control" placeholder="Kurum Adı"
                                                    value="{{ $organization->name }}" name="name" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="at-sign"></i>
                                                <input class="form-control" placeholder="Email Adresi"
                                                    value="{{ $organization->email }}" name="email" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="phone"></i>
                                                <input class="form-control" id="phoneNumber"
                                                    value="{{ $organization->phone }}" placeholder="Telefon Numarası"
                                                    name="phone" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <textarea placeholder="Adres" name="address" class="form-control" rows="3">{{ $organization->address }}</textarea>
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
                                        <form id="updateOrganizationPassword"
                                            action="{{ route('systemAdmin.organization.updatePassword', $organization->id) }}"
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
                    </div>
                    <!-- Content End -->
                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Güncellenecek Kayıt Bulunamadı Kurumlar Sayfası İçin <a
                            href="{{ route('systemAdmin.organization.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
