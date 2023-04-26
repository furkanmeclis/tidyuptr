@php
    $html_tag_data = [];
    $title = 'Kurumu Görüntüle';
    $description = '';
    $breadcrumbs = [route('systemAdmin.index') => 'Anasayfa', route('systemAdmin.organization.index') => 'Kurumlar', $organization ? route('systemAdmin.organization.show', $organization->id) : route('systemAdmin.organization.index') => 'Kurumu Görüntüle'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($organization)
                    <!-- Title Start -->
                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{ $organization->name }} <span
                                    class="badge bg-outline-{{ $organization->active ? 'primary' : 'warning' }}">{{ $organization->active ? 'Aktif' : 'Deaktif' }}</span>
                            </h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <!-- Title End -->

                    <!-- Content Start -->
                    <div class="row">
                        <div class="col-md-3 mb-5">
                            <h1 class="mb-3 h3">Kurum Ayarları</h1>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('systemAdmin.organization.edit', $organization->id) }}">
                                            <i data-acorn-icon="edit" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">Düzenle</p>
                                            <div class="text-extra-small fw-medium text-muted">Kurum Ayarlarını Düzenler
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('systemAdmin.organization.edit', $organization->id) }}#updatePassword">
                                            <i data-acorn-icon="lock-off" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">Şifre Değiştir</p>
                                            <div class="text-extra-small fw-medium text-muted">Kurum Giriş Şifresini
                                                Değiştirir
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5">
                            <h1 class="mb-3 h3">İletişim Seçenekleri</h1>
                            @if ($organization->phone != null)
                                <div class="card mb-2">
                                    <a href="tel:{{ $organization->phone }}" class="row g-0 sh-9">
                                        <div class="col d-flex align-items-center">
                                            <div class="card-body py-0">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <div class="name "><i data-acorn-icon="phone" class="text-dark"></i>
                                                        {{ $organization->phone }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            <div class="card mb-2">
                                <a href="mailto:{{ $organization->email }}" class="row g-0 sh-9">
                                    <div class="col d-flex align-items-center">
                                        <div class="card-body py-0">
                                            <div class="d-flex flex-column justify-content-center">
                                                <div class="name "><i data-acorn-icon="email" class="text-dark"></i>
                                                    {{ $organization->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @if ($organization->address != null)
                                <div class="card mb-2 ">
                                    <a target="_blank"
                                        href="https://www.google.com/maps/search/?api=1&query={{ urlencode($organization->address) }}"
                                        class="row g-0 sh-9">
                                        <div class="col d-flex align-items-center">
                                            <div class="card-body py-0">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <div class="name"><i data-acorn-icon="pin" class="text-dark"></i>
                                                        {{ $organization->address }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <h1 class="mb-3 h3">Kurum İstatistikleri</h1>
                            <div class="row g-2">
                                <div class="col-6 col-xl-6 sh-19">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                           href="{{ route('systemAdmin.organization.showExams', $organization->id) }}">
                                            <i data-acorn-icon="quiz" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">Sınavlar</p>
                                            <div class="text-extra-small fw-medium text-muted">Kurumun Düzenlediği Sınavlar
                                            </div>
                                        </a>
                                    </div>
                                </div><div class="col-6 col-xl-6 sh-19">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                           href="{{ route('systemAdmin.organization.license.index', $organization->id) }}">
                                            <i data-acorn-icon="calendar" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">Lisans Kaydı</p>
                                            <div class="text-extra-small fw-medium text-muted">Kurum Lisans Kaydı
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 col-xl-6 sh-19">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center" href="{{ route('systemAdmin.organization.showStudent', $organization->id) }}">
                                            <i data-acorn-icon="backpack" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">{{ \App\Models\Student::where('organization_id',$organization->id)->count() }} Öğrenci</p>
                                            <div class="text-extra-small fw-medium text-muted">Kurumdaki Kayıtlı Öğrenci
                                                Sayısı
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 col-xl-6 sh-19">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('systemAdmin.organization.showTeacher', $organization->id) }}">
                                            <i data-acorn-icon="graduation" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">
                                                {{ \App\Models\OrganizationTeacher::where('organization_id', $organization->id)->count() }}
                                                Öğretmen</p>
                                            <div class="text-extra-small fw-medium text-muted">Kurumdaki Kayıtlı Öğretmen
                                                Sayısı
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
                    <div class="alert alert-warning">Görüntülenecek Kayıt Bulunamadı Kurumlar Sayfası İçin <a
                            href="{{ route('systemAdmin.organization.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
