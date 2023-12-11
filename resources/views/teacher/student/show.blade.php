@php
    $html_tag_data = [];
    $title = 'Öğrenciyi Görüntüle';
    $description = '';
    $breadcrumbs = [
        route('teacher.index') => 'Anasayfa',
        route('teacher.student.index') => 'Öğrenciler',
        $student ? route('teacher.student.show', $student->id) : route('teacher.student.index') => 'Öğrenciyi Görüntüle'
        ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])
@section('js_page')
    <script src="/js/teacher/students/helper.js"></script>
@endsection

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


                        <div class="col-md-5">
                            <h1 class="mb-3 h3">İstatistikleri</h1>
                            <div class="row g-2">
                                <div class="col-6 col-xl-6 sh-19">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center"
                                            href="{{ route('teacher.student.exam',$student->id) }}">
                                            <i data-acorn-icon="quiz" class="text-primary"></i>
                                            <p class="heading mt-3 text-body">Sınavlar</p>
                                            <div class="text-extra-small fw-medium text-muted">Öğrencinin yapmış olduğu sınavlar
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 col-xl-6 sh-19">
                                    <div class="card h-100 hover-scale-up">
                                        <a class="card-body text-center delete-student-btn"
                                           href="{{ route('teacher.student.destroy',$student->id) }}">
                                            <i data-acorn-icon="bin" class="text-danger"></i>
                                            <p class="heading mt-3 text-body">Bağlantıyı Kaldır</p>
                                            <div class="text-extra-small fw-medium text-muted">Öğrenci İle Aranızdaki İlişik Kesilir
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @component('components.chart',["url"=> route('teacher.getStatsStudent',$student->id)])
                            @endcomponent
                        </div>
                    </div>
                    <!-- Content End -->
                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Görüntülenecek Kayıt Bulunamadı Öğrenciler Sayfası İçin <a
                            href="{{ route('teacher.student.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
