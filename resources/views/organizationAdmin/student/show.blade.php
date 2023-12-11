@php
    $html_tag_data = [];
    $title = 'Öğrenciyi Görüntüle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.student.index') => 'Öğrenciler',
        $student ? route('organizationAdmin.student.show', $student->id) : route('organizationAdmin.student.index') => 'Öğrenciyi Görüntüle'
        ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($student)
                    <!-- Title Start -->
                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{ $student->name }}
                            </h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <!-- Title End -->

                    <!-- Content Start -->
                    <div class="row">
                        <div class="col-md-3 mb-5">
                            <h1 class="mb-3 h3">Temel Ayarlar</h1>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('organizationAdmin.student.edit', $student->id) }}">
                                            <i data-acorn-icon="edit" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">Düzenle</p>
                                            <div class="text-extra-small fw-medium text-muted">Ayarları Düzenler
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('organizationAdmin.student.edit', $student->id) }}#updatePassword">
                                            <i data-acorn-icon="lock-off" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">Şifre Değiştir</p>
                                            <div class="text-extra-small fw-medium text-muted">Giriş Şifresini
                                                Değiştirir
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5">
                            <h1 class="mb-3 h3">İletişim Seçenekleri</h1>
                            @if ($student->phone != null)
                                <div class="card mb-2">
                                    <a href="tel:{{ $student->phone }}" class="row g-0 sh-9">
                                        <div class="col d-flex align-items-center">
                                            <div class="card-body py-0">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <div class="name "><i data-acorn-icon="phone" class="text-dark"></i>
                                                        {{ $student->phone }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            <div class="card mb-2">
                                <a href="mailto:{{ $student->email }}" class="row g-0 sh-9">
                                    <div class="col d-flex align-items-center">
                                        <div class="card-body py-0">
                                            <div class="d-flex flex-column justify-content-center">
                                                <div class="name "><i data-acorn-icon="email" class="text-dark"></i>
                                                    {{ $student->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @if ($student->address != null)
                                <div class="card mb-2 ">
                                    <a target="_blank"
                                        href="https://www.google.com/maps/search/?api=1&query={{ urlencode($student->address) }}"
                                        class="row g-0 sh-9">
                                        <div class="col d-flex align-items-center">
                                            <div class="card-body py-0">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <div class="name"><i data-acorn-icon="pin" class="text-dark"></i>
                                                        {{ $student->address }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <h1 class="mb-3 h3">İstatistikleri</h1>
                            <div class="row g-2">
                                <div class="col-6 col-xl-6 sh-19">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('organizationAdmin.student.exam.index',$student->id) }}">
                                            <i data-acorn-icon="quiz" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">Sınavlar</p>
                                            <div class="text-extra-small fw-medium text-muted">Öğrencinin yapmış olduğu sınavlar
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                @if($teacher_id = \App\Models\StudentTeacher::where('student_id', $student->id)->first())
                                <div class="col-6 col-xl-6 sh-19">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('organizationAdmin.teacher.show', $teacher_id->id) }}">
                                            <i data-acorn-icon="graduation" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">
                                                {{ \App\Models\StudentTeacher::where('student_id', $student->id)->first()->teacher()->first()->name }}
                                                </p>
                                            <div class="text-extra-small fw-medium text-muted">Öğrenci İle İlgilenen Öğretmen
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-12">
                        @component('components.chart',["url"=> route('organizationAdmin.getStatsStudent',$student->id)])
                        @endcomponent
                    </div>
                </div>
                    <!-- Content End -->
                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Görüntülenecek Kayıt Bulunamadı Öğrenciler Sayfası İçin <a
                            href="{{ route('organizationAdmin.student.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
