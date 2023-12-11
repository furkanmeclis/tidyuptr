@php
    $html_tag_data = [];
    $title = 'Yeni Sınıf Ekle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.class.index') => 'Sınıflar',
        route('organizationAdmin.class.create') => 'Sınıf Ders Programı Oluştur',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css"/>
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css"/>
@endsection
@section('js_vendor')
    <script src="/js/cs/wizard.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/input-spinner.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/student/schedule/create.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <section class="scroll-section" id="title">
                <div class="page-title-container">
                    <h1 class="mb-0 pb-0 display-4">{{ $title }}</h1>
                    @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                </div>
            </section>
            <div class="col-md-12">
                <div class="mb-5 wizard" id="classWizard">
                    <div class="border-0 pb-0">
                        <ul class="nav nav-tabs justify-content-center" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-center" href="#daySelect" role="tab">
                                    <div class="mb-1 title d-none d-sm-block">Gün Seçimi</div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-center" href="#hourTableSelect" role="tab">
                                    <div class="mb-1 title d-none d-sm-block">Saat Seçimi</div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-center" href="#hourLessonSelect" role="tab">
                                    <div class="mb-1 title d-none d-sm-block">Ders Seçimi</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content mt-5 mb-5">
                        <div class="tab-pane fade" id="daySelect" role="tabpanel">
                            <form id="step0Form">
                                <div class="row g-2">

                                    <div class="col-md-3">
                                        <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                            <input type="checkbox"
                                                   name="day[]"
                                                   value="0" class="form-check-input position-absolute e-2 t-2 z-index-1" />
                                            <span class="card form-check-label w-100">
                                          <span class="card-body text-center">
                                            <span class="heading mt-3 text-body text-primary d-block">Pazartesi</span>
                                          </span>
                                        </span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                            <input type="checkbox"
                                                   name="day[]"
                                                   value="1" class="form-check-input position-absolute e-2 t-2 z-index-1" />
                                            <span class="card form-check-label w-100">
                                          <span class="card-body text-center">
                                            <span class="heading mt-3 text-body text-primary d-block">Salı</span>
                                          </span>
                                        </span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                            <input type="checkbox"
                                                   name="day[]"
                                                   value="2" class="form-check-input position-absolute e-2 t-2 z-index-1" />
                                            <span class="card form-check-label w-100">
                                          <span class="card-body text-center">
                                            <span class="heading mt-3 text-body text-primary d-block">Çarşamba</span>
                                          </span>
                                        </span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                            <input type="checkbox"
                                                   name="day[]"
                                                   value="3" class="form-check-input position-absolute e-2 t-2 z-index-1" />
                                            <span class="card form-check-label w-100">
                                          <span class="card-body text-center">
                                            <span class="heading mt-3 text-body text-primary d-block">Perşembe</span>
                                          </span>
                                        </span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                            <input type="checkbox"
                                                   name="day[]"
                                                   value="4" class="form-check-input position-absolute e-2 t-2 z-index-1" />
                                            <span class="card form-check-label w-100">
                                          <span class="card-body text-center">
                                            <span class="heading mt-3 text-body text-primary d-block">Cuma</span>
                                          </span>
                                        </span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                            <input type="checkbox"
                                                   name="day[]"
                                                   value="5"
                                                   class="form-check-input position-absolute e-2 t-2 z-index-1" />
                                            <span class="card form-check-label w-100">
                                          <span class="card-body text-center">
                                            <span class="heading mt-3 text-body text-primary d-block">Cumartesi</span>
                                          </span>
                                        </span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                            <input type="checkbox"
                                                   name="day[]"
                                                   value="6"
                                                   class="form-check-input position-absolute e-2 t-2 z-index-1"  />
                                            <span class="card form-check-label w-100">
                                          <span class="card-body text-center">
                                            <span class="heading text-body mt-3 text-primary d-block">Pazar</span>
                                          </span>
                                        </span>
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="hourTableSelect" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <form id="step1Form">
                                        <div class="row" id="hourTemplateArea"></div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="hourLessonSelect" role="tabpanel">
                            <form action="{{route('student.schedule.store')}}" id="step2Form">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="alert alert-info">Maksimum Belirleyeceğiniz Süre <b id="totalHour"></b> Saattir</div>
                                    </div>
                                    <div class="col-md-12 py-3 border-bottom">
                                        <div class="row d-flex align-items-center">
                                            <div class="col-6"><div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="lesson_select[0][status]" data-lesson-hour-select="true" data-selector-name="lesson_select[0][hour]" class="custom-control-input" id="lessonSelect0">
                                                    <label class="custom-control-label" for="lessonSelect0">Rehberlik</label>
                                                </div></div>
                                            <div class="col-6">
                                                <div class="top-label" style="flex: 1">
                                                    <div class="input-group spinner" data-trigger="spinner">
                                                        <input type="number" name="lesson_select[0][hour]" class="form-control" value="2" min="0" max="2" data-rule="quantity" />
                                                    </div>
                                                    <span>Haftalık Ders Sayısı</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($lessons as $lesson)
                                        <div class="col-md-12 py-3 border-bottom">
                                            <div class="row d-flex align-items-center">
                                                <div class="col-6"><div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="lesson_select[{{$lesson->id}}][status]" data-lesson-hour-select="true" data-selector-name="lesson_select[{{$lesson->id}}][hour]" class="custom-control-input" id="lessonSelect{{$lesson->id}}">
                                                        <label class="custom-control-label" for="lessonSelect{{$lesson->id}}">{{$lesson->name}}</label>
                                                    </div></div>
                                                <div class="col-6">
                                                    <div class="top-label" style="flex: 1">
                                                        <div class="input-group spinner" data-trigger="spinner">
                                                            <input type="number" name="lesson_select[{{$lesson->id}}][hour]" class="form-control" value="4" min="0" data-rule="quantity" />
                                                        </div>
                                                        <span>Haftalık Ders Sayısı</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-3 text-center">
                                    <button class="btn btn-primary" type="submit">Kaydet</button>
                                </p>
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
            </div>

        </div>
    </div>
    <template id="hourTemplate">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 style="flex: 1">{day_name}</h3>
                <div class="top-label me-3" style="flex: 1">
                    <div class="input-group">
                        <input type="time" required name="days[{day_index}][start_time]" value="09:00" class="form-control"/>
                    </div>
                    <span>Başlangıç Saati</span>
                </div>
                <div class="top-label" style="flex: 1">
                    <div class="input-group spinner" data-trigger="spinner">
                        <input type="number" name="days[{day_index}][hours]" data-name="hours" data-day-index="{day_index}" data-step-2="true" class="form-control" value="7" min="1" data-rule="quantity" />
                    </div>
                    <span>Ders Sayısı</span>
                </div>
            </div>
            <hr>
        </div>
    </template>
@endsection
