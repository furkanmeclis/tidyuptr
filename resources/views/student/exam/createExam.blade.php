@php
    $html_tag_data = [];
    $title = 'Sınav Sonucu Ekle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.batchExam.index') => 'Sınavlar',
        $batch ? route('organizationAdmin.batchExam.show', $batch->id) : route('organizationAdmin.batchExam.show') => $batch ? $batch->name : 'Sınav Grubu',
        $batch ? route('organizationAdmin.batchExam.exam.create', $batch->id) : route('organizationAdmin.batchExam.show',$batch->id) => 'Sınav Sonucu Ekle',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection
@section('js_vendor')
    <script src="/js/cs/wizard.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/organizationAdmin/exams/create.js"></script>
    <script src="/js/organizationAdmin/exams/create.table.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($batch)
                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{$batch->name}} Adlı Sınava Sonuç Ekle</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <div class="mb-5 wizard" id="examResultWizard">
                        <div class="border-0 pb-0">
                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center" href="#basicSecond" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">Öğrenci Seçimi</div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center" href="#basicThird" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">Sonuç Bilgileri</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content mt-5 mb-5">
                            <div class="tab-pane fade" id="basicSecond" role="tabpanel">
                                <div class="data-table-rows slim">
                                    <!-- Controls Start -->
                                    <div class="row">
                                        <!-- Search Start -->
                                        <div class="col mb-1">
                                            <div
                                                class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                                <input class="form-control datatable-search" placeholder="Ara"
                                                       data-datatable="#datatableRows2" />
                                                <span class="search-magnifier-icon">
                                                <i data-acorn-icon="search"></i>
                                            </span>
                                                <span class="search-delete-icon d-none">
                                                <i data-acorn-icon="close"></i>
                                            </span>
                                            </div>
                                        </div>
                                        <!-- Search End -->


                                    </div>
                                    <!-- Controls End -->

                                    <!-- Table Start -->
                                    <div class="data-table-responsive-wrapper">
                                        <table id="datatableRows2" class="data-table"
                                               data-ajax-url="{{ route('organizationAdmin.student.getStudents') }}">
                                            <thead>
                                            <tr>
                                                <th class="text-muted text-uppercase">#Id</th>
                                                <th class="text-muted text-uppercase">Öğrenci Adı</th>
                                                <th class="empty">&nbsp;</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Table End -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="basicThird" role="tabpanel">
                                        <form id="createExamResult"
                                              action="{{ route('organizationAdmin.batchExam.exam.store', $batch->id) }}"
                                              method="POST" class="tooltip-end-bottom" novalidate autocomplete="off">
                                            <div class="row">
                                                @foreach($lessons as $lesson)
                                                    <div class="col">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h5 class="mb-0">{{$lesson->name}}</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="mb-3 filled">
                                                                    <i data-acorn-icon="check" class="text-success"></i>
                                                                    <input type="number" class="form-control required"
                                                                           placeholder="Doğru Sayısı" autocomplete="off"
                                                                           min="0"
                                                                           name="lessons[{{$lesson->id}}][correct_answers]" />
                                                                </div>
                                                                <div class="mb-3 filled">
                                                                    <i data-acorn-icon="multiply" class="text-danger"></i>
                                                                    <input type="number" class="form-control required"
                                                                           placeholder="Yanlış Sayısı"
                                                                           autocomplete="off"
                                                                           min="0"
                                                                           name="lessons[{{$lesson->id}}][wrong_answers]"  />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endforeach
                                                <div class="col-md-12 text-center mt-3">

                                                    <a class="btn btn-dark me-3" href="{{route('organizationAdmin.batchExam.show',$batch->id)}}">Vazgeç</a><button class="btn btn-primary" type="submit">Ekle</button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="student_id">
                                        </form>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center border-0 pt-1">
                            <button class="btn btn-icon btn-icon-start btn-outline-primary btn-prev" type="button">
                                <i data-acorn-icon="chevron-left"></i>
                                <span>Geri</span>
                            </button>
                            <button class="btn btn-icon btn-icon-end btn-outline-primary btn-next" type="button">
                                <span>İleri</span>
                                <i data-acorn-icon="chevron-right"></i>
                            </button>
                        </div>
                    </div>

                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Sınav Grubu Bulunamadı Sınav Grupları Sayfası İçin <a
                            href="{{ route('organizationAdmin.batchExam.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
