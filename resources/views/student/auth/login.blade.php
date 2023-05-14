@php
    $title = 'Giriş Sayfası';
    $description = 'Giriş Sayfası';
@endphp

@extends('layout_full', ['title' => $title, 'description' => $description])

@section('css')
@endsection

@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/student/auth/login.js"></script>
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
                <p class="h6">Lütfen kimlik bilgilerinizi kullanarak giriş yapın.</p>
            </div>
            <div>
                <form id="systemAdminLoginForm" class="tooltip-end-bottom" action="{{ route('student.login') }}"
                    novalidate>
                    @csrf
                    <div class="mb-3 filled form-group tooltip-end-top">
                        <i data-acorn-icon="email"></i>
                        <input class="form-control" placeholder="E-posta" name="email" />
                    </div>
                    <div class="mb-3 filled form-group tooltip-end-top">
                        <i data-acorn-icon="lock-off"></i>
                        <input class="form-control pe-7" name="password" type="password" placeholder="Parola" />
                        <a class="text-small position-absolute t-3 e-3"
                            href="/Pages/Authentication/ForgotPassword">Unuttum?</a>
                    </div>
                    <div class="mb-3 position-relative form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me" />
                            <label class="form-check-label" for="rememberMe">
                                Beni Hatırla
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary">Giriş Yap</button>
                </form>
            </div>
        </div>
    </div>
@endsection
