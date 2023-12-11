@php
    $html_tag_data = [];
    $title = 'Ayarlar';
    $description = 'Home screen that contains stats, charts, call to action buttons and various listing elements.';
    $breadcrumbs = ['/' => 'Home', '/Dashboards' => 'Dashboards'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
    <script src="/js/app.js"></script>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-sm-6 d-flex align-items-start justify-content-end">

                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="card mb-5">
                        <div class="card-body">
                            <form id="userSettings" action="{{route("organizationAdmin.settings")}}">
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Adınız Soyadınız</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="text" name="name" class="form-control" value="{{$user->name}}">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Email Adresiniz</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="email" name="email" class="form-control" value="{{$user->email}}">
                                    </div>
                                </div><div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Telefon Numaranız</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="tel" name="phone" class="form-control" value="{{$user->phone}}">
                                    </div>
                                </div><div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Adres</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="text" name="address" class="form-control" value="{{$user->address}}">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Şifre</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="password" name="password" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row mt-5">
                                    <div class="col-sm-8 col-md-9 col-lg-10 ms-auto">
                                        <button type="submit" class="btn btn-outline-primary">Güncelle</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

