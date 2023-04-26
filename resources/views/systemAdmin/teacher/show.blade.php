@php
    $html_tag_data = [];
    $title = 'Öğretmeni Görüntüle';
    $description = '';
    $breadcrumbs = [
        route('systemAdmin.index') => 'Anasayfa',
        route('systemAdmin.teacher.index') => 'Öğretmenler',
        $teacher ? route('systemAdmin.teacher.show', $teacher->id) : route('systemAdmin.teacher.index') => 'Öğretmeni Görüntüle',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($teacher)
                    <!-- Title Start -->
                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{ $teacher->name }}
                            </h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <!-- Title End -->

                    <!-- Content Start -->
                    <div class="row">
                        <div class="col-md-3 mb-5">
                            <h1 class="h3 mb-3">Temel Ayarlar</h1>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('systemAdmin.teacher.edit', $teacher->id) }}">
                                            <i data-acorn-icon="edit" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">Düzenle</p>
                                            <div class="text-extra-small fw-medium text-muted">Öğretmen Bilgilerini Düzenler
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('systemAdmin.teacher.edit', $teacher->id) }}#updatePassword">
                                            <i data-acorn-icon="lock-off" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">Şifre Değiştir</p>
                                            <div class="text-extra-small fw-medium text-muted">Öğretmen Hesap Şifresini
                                                Değiştirir
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5">
                            <h1 class="h3  mb-3">İletişim Seçenekleri</h1>
                            @if ($teacher->phone != null)
                                <div class="card mb-2">
                                    <a href="tel:{{ $teacher->phone }}" class="row g-0 sh-9">
                                        <div class="col d-flex align-items-center">
                                            <div class="card-body py-0">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <div class="name "><i data-acorn-icon="phone" class="text-dark"></i>
                                                        {{ $teacher->phone }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            <div class="card mb-2">
                                <a href="mailto:{{ $teacher->email }}" class="row g-0 sh-9">
                                    <div class="col d-flex align-items-center">
                                        <div class="card-body py-0">
                                            <div class="d-flex flex-column justify-content-center">
                                                <div class="name "><i data-acorn-icon="email" class="text-dark"></i>
                                                    {{ $teacher->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h1 class=" h3  mb-3">İstatistikler</h1>
                            <div class="row g-2">
                                <div class="col-6 col-xl-6 sh-19">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center" href="{{route('systemAdmin.teacher.showStudent',$teacher->id)}}">
                                            <i data-acorn-icon="backpack" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">{{\App\Models\StudentTeacher::where('teacher_id',$teacher->id)->count()}} Öğrenci</p>
                                            <div class="text-extra-small fw-medium text-muted">İlgilendiği Kayıtlı Öğrenci
                                                Sayısı
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 col-xl-6 sh-19">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('systemAdmin.teacher.showOrganization', $teacher->id) }}">
                                            <i data-acorn-icon="school" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">
                                                {{ \App\Models\OrganizationTeacher::where('teacher_id', $teacher->id)->count() }}
                                                Kurum</p>
                                            <div class="text-extra-small fw-medium text-muted">Çalıştığı Kurum Sayısı
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content End -->
                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Görüntülenecek Kayıt Bulunamadı Öğretmenler Sayfası İçin <a
                            href="{{ route('systemAdmin.teacher.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
