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
    <script src="/js/organizationAdmin/class/createTimeTable.js"></script>
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
                            <form action="{{route('organizationAdmin.class.createTimeTableStore',$class->id)}}" id="step2Form">
                                <div class="mb-n2" id="lessonSelectTemplateArea">
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
    <template id="lessonAccordion">
        <div class="card d-flex mb-2">
            <div
                class="d-flex flex-grow-1"
                role="button"
                data-bs-toggle="collapse"
                data-bs-target="#dayLessonSelect{day_index}"
                aria-expanded="true"
                aria-controls="dayLessonSelect{day_index}"
            >
                <div class="card-body py-4">
                    <div class="btn btn-link list-item-heading p-0">{day_name}</div>
                </div>
            </div>
            <div id="dayLessonSelect{day_index}" class="collapse" data-bs-parent="#lessonSelectTemplateArea">
                <div class="card-body accordion-content pt-0">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="top-label" style="flex: 1">
                                <div class="input-group">
                                    <input type="time" required name="days[{day_index}][start_time]" value="09:00" class="form-control"/>
                                </div>
                                <span>Eğitim Başlangıç Saati</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="top-label" style="flex: 1">
                                <div class="input-group">
                                    <input type="number" required value="40" min="0" name="days[{day_index}][duration]" class="form-control"/>
                                </div>
                                <span>Ders Süresi</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="top-label" style="flex: 1">
                                <div class="input-group">
                                    <input type="number" required value="10" min="0" name="days[{day_index}][recess]" class="form-control"/>
                                </div>
                                <span>Tenefüs Süresi</span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {hour_lesson_template}
                </div>
            </div>
        </div>
    </template>
    <template id="teacherAndLessonTemplate">
        <h3>{hour_index}.Ders</h3>
        <div class="d-flex">
            <div class="filled mb-3 w-100 me-3" style="flex: 1">
                <i data-acorn-icon="lecture"></i>
                <select class="select2" name="days[{day_index}][hours][{hour_index}][lesson_id]"
                        data-name="lesson_id"
                        data-day-index="{day_index}"
                        data-hour-index="{hour_index}"
                        required
                        data-placeholder="Ders Seçimi" name="lesson_id">
                    @foreach($lessons as $lesson)
                        <option value="{{$lesson->id}}" data-recess="isRecess-{day_index}-{hour_index}"
                                data-teacher="isTeacher-{day_index}-{hour_index}">{{$lesson->name}}</option>
                    @endforeach
                        <option value="recess"
                                class="recess-control"
                                data-recess="isRecess-{day_index}-{hour_index}"
                                data-teacher="isTeacher-{day_index}-{hour_index}"
                        >Ara</option>
                </select>
            </div>
            <div class="mb-3 filled me-3" style="flex: 1;display:none" id="isRecess-{day_index}-{hour_index}" >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-clock mb-3 d-inline-block text-primary"><path d="M8 12L9.70711 10.2929C9.89464 10.1054 10 9.851 10 9.58579V6"></path><circle cx="10" cy="10" r="8"></circle></svg>
                <input class="form-control" type="number" value="60" min="0" name="days[{day_index}][hours][{hour_index}][recess]" placeholder="Ara Süresi">
            </div>
            <div class="filled mb-3 w-100 " style="flex: 1" id="isTeacher-{day_index}-{hour_index}">
                <i data-acorn-icon="lecture"></i>
                <select class="select2" required name="days[{day_index}][hours][{hour_index}][teacher_id]"
                        data-name="teacher_id"
                        data-day-index="{day_index}"
                        data-hour-index="{hour_index}"
                        data-placeholder="Öğretmen Seçimi" name="teacher_id">
                    @foreach($teachers as $teacher)
                        <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <hr>
    </template>
@endsection
