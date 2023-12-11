@php
    $title = 'Kayıt Sayfası';
    $description = 'Kayıt Sayfası';
@endphp

@extends('layout_full', ['title' => $title, 'description' => $description])

@section('css')
@endsection

@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/student/auth/register.js"></script>
@endsection

@section('content_left')
    <div class="min-h-100 d-flex align-items-center">
        <div class="w-100 w-lg-75 w-xxl-50">
            <div>
                <div class="mb-5">
                    <h1 class="display-3 text-white">Çoklu Konseptler</h1>
                    <h1 class="display-3 text-white">Projeniz İçin Hazır</h1>
                </div>
                <p class="h6 text-white lh-1-5 mb-5">
                    Özelleştirilmiş teknolojiler için yüksek ödüllü entelektüel sermayeyi dinamik olarak hedefleyin. Süreç
                    odaklı topluluklardan önce çıkan
                    yeni temel yetkinlikleri objektif bir şekilde entegre edin...
                </p>
                <div class="mb-5">
                    <a class="btn btn-lg btn-outline-white" href="/">Daha Fazlasını Öğrenin</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content_right')
    <div
        class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
        <div class="sw-lg-50 px-5">
            <div class="sh-11">
                <div class="logo-default"></div>
            </div>
            <div class="mb-5">
                <h2 class="cta-1 mb-0 text-primary">Hoş geldiniz,</h2>
                <h2 class="cta-1 text-primary">başlayalım!</h2>
            </div>
            <div class="mb-5">
                <p class="h6">Bilgilerinizi Doldurarak Sizde Bize Katılabilirsiniz</p>
            </div>
            <div>
                <form id="systemAdminLoginForm" class="tooltip-end-bottom" action="{{ route('student.register') }}"
                      novalidate>
                    @csrf
                    <div class="mb-3 filled ">
                        <i data-acorn-icon="online-class"></i>
                        <input class="form-control" placeholder="Öğrenci Adı" name="name" />

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

                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary" type="submit">Ekle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
