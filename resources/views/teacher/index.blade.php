@php
    $html_tag_data = [];
    $title = 'Anasayfa';
    $description = 'Home screen that contains stats, charts, call to action buttons and various listing elements.';
    $breadcrumbs = ['/' => 'Home', '/Dashboards' => 'Dashboards'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/glide.core.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/Chart.bundle.min.js"></script>
    <script src="/js/vendor/chartjs-plugin-datalabels.js"></script>
    <script src="/js/vendor/chartjs-plugin-rounded-bar.min.js"></script>
    <script src="/js/vendor/glide.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/glide.custom.js"></script>
    <script src="/js/cs/charts.extend.js"></script>
    <script src="/js/student/dashboard.js"></script>
    <script src="/js/vendor/list.js"></script>
    <script src="/js/plugins/lists.js"></script>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row g-2">
                    <div class="col-4 mb-5">
                        <a class="card hover-border-primary" href="{{route('teacher.student.index')}}">
                            <div class="h-100 row g-0 card-body align-items-center">
                                <div class="col-auto">
                                    <div class="sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center border border-primary">
                                        <i data-acorn-icon="online-class"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="heading mb-0 sh-8 d-flex align-items-center lh-1-25 ps-3 text-dark">Öğrenci Sayısı</div>
                                </div>
                                <div class="col-auto ps-3">
                                    <div class="display-5 text-primary">{{\App\Models\StudentTeacher::where('teacher_id',auth('teacher')->user()->id)->count()}}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4 mb-5">
                        <a class="card hover-border-primary" href="{{route('teacher.organization.index')}}">
                            <div class="h-100 row g-0 card-body align-items-center">
                                <div class="col-auto">
                                    <div class="sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center border border-primary">
                                        <i data-acorn-icon="lecture"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="heading mb-0 sh-8 d-flex align-items-center lh-1-25 ps-3 text-dark">Kurum Sayısı</div>
                                </div>
                                <div class="col-auto ps-3">
                                    <div class="display-5 text-primary">{{\App\Models\OrganizationTeacher::where('teacher_id',auth('teacher')->user()->id)->count()}}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4 mb-5">
                        <a class="card hover-border-primary" href="{{route('teacher.lessonRequest.index')}}">
                            <div class="h-100 row g-0 card-body align-items-center">
                                <div class="col-auto">
                                    <div class="sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center border border-primary">
                                        <i data-acorn-icon="calendar"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="heading mb-0 sh-8 d-flex align-items-center lh-1-25 ps-3 text-dark">Ders Talepleri</div>
                                </div>
                                <div class="col-auto ps-3">
                                    <div class="display-5 text-primary">
                                        {{\App\Models\LessonRequests::where('teacher_id',auth('teacher')->user()->id)->whereBetween('created_at',[\Carbon\Carbon::now()->subWeek(),\Carbon\Carbon::now()])->count()}}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-5">
                <div class="d-flex justify-content-between">
                    <a href="{{route('organizationAdmin.class.index')}}"><h2 class="small-title">Sınıflar</h2></a>
                </div>

                <div class="scroll-out">
                    <div class="scroll-by-count" data-count="3">
                        @php($class = \App\Models\Classes::where('teacher_id', auth('teacher')->user()->id)->first())
                        @if($class)
                            <div class="card mb-2">
                                <div class="row g-0 sh-12">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0">
                                                    <a href="{{route('teacher.class.show',$class->id)}}" class="text-truncate">{{$class->name}}</a>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$class->students()->count()}} Öğrenci
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                       type="button" href="{{route('teacher.class.show',$class->id)}}">
                                                        <i data-acorn-icon="eye" data-acorn-size="15"></i>
                                                    </a> <a class="btn btn-sm btn-icon btn-icon-start btn-outline-warning ms-1"
                                                            type="button" href="{{route('teacher.class.announcement.index',$class->id)}}">
                                                        <i data-acorn-icon="notification" data-acorn-size="15"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-info ms-1"
                                                       type="button" href="{{route('teacher.class.download',$class->id)}}">
                                                        <i data-acorn-icon="file-chart" data-acorn-size="15"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @php($classes = \App\Models\Classes::whereIn('organization_id', \App\Models\OrganizationTeacher::where('teacher_id',auth('teacher')->user()->id)->pluck('organization_id')->toArray())->get())
                        @foreach($classes as $class)
                            <div class="card mb-2">
                                <div class="row g-0 sh-12">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0">
                                                    <a href="{{route('teacher.class.show',$class->id)}}" class="text-truncate">{{$class->name}}</a>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$class->students()->count()}} Öğrenci
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                       type="button" href="{{route('teacher.class.show',$class->id)}}">
                                                        <i data-acorn-icon="eye" data-acorn-size="15"></i>
                                                    </a> <a class="btn btn-sm btn-icon btn-icon-start btn-outline-warning ms-1"
                                                            type="button" href="{{route('teacher.class.announcement.index',$class->id)}}">
                                                        <i data-acorn-icon="notification" data-acorn-size="15"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-info ms-1"
                                                       type="button" href="{{route('teacher.class.download',$class->id)}}">
                                                        <i data-acorn-icon="file-chart" data-acorn-size="15"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if(count($classes) == 0 && !$class)
                            <div class="alert alert-warning">
                                Sınıf Bulunamadı
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-5">
                <div class="d-flex justify-content-between">
                    <h2 class="small-title">Son 1 Haftada Dersime Katılmayan Öğrenciler</h2>
                </div>

                <div class="scroll-out">
                    <div class="scroll-by-count" data-count="3">
                        @php($attendances  = \App\Models\ClassAttendance::whereIn('day_hour_id',\App\Models\DayHours::where('teacher_id',auth('teacher')->user()->id)->pluck('id')->toArray())->orderBy('created_at','desc')->get())
                        @foreach($attendances as $attendance)
                            @php($student = $attendance->student())
                            @php($lesson = $attendance->lesson())
                            <div class="card mb-2">
                                <div class="row g-0 sh-12">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0">
                                                    <a class="text-truncate" href="{{route('teacher.student.show',$student->id)}}">{{$student->name}}</a>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$lesson->name}} {{$attendance->created_at->format('d.m.Y H:i')}}
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                       type="button" href="{{route('teacher.student.show',$student->id)}}">
                                                        <i data-acorn-icon="eye" data-acorn-size="15"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if(count($attendances) == 0)
                            <div class="alert alert-warning">
                                Kayıt Bulunamadı
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-5">
                <div class="d-flex justify-content-between">
                    <h2 class="small-title">Soru & Cevap</h2>
                </div>

                <div class="scroll-out">
                    <div class="scroll-by-count" data-count="3">
                        @php($questions  = \App\Models\Questions::where('teacher_id',auth('teacher')->user()->id)->orderBy('created_at','desc')->get())
                        @foreach($questions as $question)
                            <div class="card mb-2">
                                <div class="row g-0 sh-12">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0 {{$question->is_answered ? "text-decoration-line-through":""}}">
                                                    <a class="text-truncate" href="{{route('teacher.questionAnswer.index',$question->id)}}">{{$question->question}}</a>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$question->created_at->format('d.m.Y H:i')}}
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                       type="button" href="{{route('teacher.questionAnswer.index',$question->id)}}">
                                                        <i data-acorn-icon="eye" data-acorn-size="15"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if(count($attendances) == 0)
                            <div class="alert alert-warning">
                                Kayıt Bulunamadı
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-5">
                <div class="d-flex justify-content-between">
                    <h2 class="small-title">Ödevlendirmelerim</h2>
                </div>

                <div class="scroll-out">
                    <div class="scroll-by-count" data-count="3">
                        @php($assignments  = \App\Models\Assignments::where('teacher_id',auth('teacher')->user()->id)->orderBy('created_at','desc')->get())
                        @foreach($assignments as $assignment)
                            <div class="card mb-2">
                                <div class="row g-0 sh-12">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0">
                                                    <a class="text-truncate" href="{{route('teacher.assignment.show',$assignment->id)}}">{{$assignment->title}}</a>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$assignment->created_at->format('d.m.Y H:i')}}
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                       type="button" href="{{route('teacher.assignment.show',$assignment->id)}}">
                                                        <i data-acorn-icon="eye" data-acorn-size="15"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if(count($assignments) == 0)
                            <div class="alert alert-warning">
                                Ödev Kaydı Bulunamadı
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection
