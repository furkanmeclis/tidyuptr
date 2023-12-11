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
    <script src="/js/vendor/glide.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/glide.custom.js"></script>
    <script src="/js/plugins/carousels.js"></script>
    <script src="/js/vendor/list.js"></script>
    <script src="/js/plugins/lists.js"></script>
    <script>
        if (document.querySelector('#glideAchievements')) {
            new GlideCustom(
                document.querySelector('#glideAchievements'),
                {
                    gap: 0,
                    rewind: false,
                    bound: true,
                    type: 'carousel',
                    autoplay: 4000,
                    perView:2,
                    breakpoints: {
                        600: {perView: 1},
                        1400: {perView: 2},
                        1900: {perView: 2},
                        3840: {perView: 5},
                    },
                },
                true,
            ).mount();
        }
    </script>
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
                        <a class="card hover-border-primary" href="{{route('organizationAdmin.student.index')}}">
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
                                    <div class="display-5 text-primary">{{\App\Models\Student::where('organization_id',auth('organization')->user()->id)->count()}}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4 mb-5">
                        <a class="card hover-border-primary" href="{{route('organizationAdmin.teacher.index')}}">
                            <div class="h-100 row g-0 card-body align-items-center">
                                <div class="col-auto">
                                    <div class="sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center border border-primary">
                                        <i data-acorn-icon="lecture"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="heading mb-0 sh-8 d-flex align-items-center lh-1-25 ps-3 text-dark">Öğretmen Sayısı</div>
                                </div>
                                <div class="col-auto ps-3">
                                    <div class="display-5 text-primary">{{\App\Models\OrganizationTeacher::where('organization_id',auth('organization')->user()->id)->count()}}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4 mb-5">
                        <a class="card hover-border-primary" href="{{route('organizationAdmin.class.index')}}">
                            <div class="h-100 row g-0 card-body align-items-center">
                                <div class="col-auto">
                                    <div class="sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center border border-primary">
                                        <i data-acorn-icon="home-garage"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="heading mb-0 sh-8 d-flex align-items-center lh-1-25 ps-3 text-dark">Sınıf Sayısı</div>
                                </div>
                                <div class="col-auto ps-3">
                                    <div class="display-5 text-primary">{{\App\Models\Classes::where('organization_id',auth('organization')->user()->id)->count()}}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @php($exam_ids = \App\Models\Exams::whereIn('student_id',\App\Models\Student::where('organization_id',auth('organization')->user()->id)->pluck('id')->toArray())->pluck('id')->toArray())
            @if(count($exam_ids) != 0)
                <div class="col-md-6">
                    @php($exam_count = count($exam_ids))
                    @php($scores = \App\Models\ExamResults::whereIn('exam_id',$exam_ids)->get())
                    @php($lessons = [])
                    @foreach($scores as $score)
                            <?php
                            $lessonId = $score->lesson_id;
                            $data = isset($lessons[$lessonId]) ? $lessons[$lessonId] : [];

                            if (empty($data)) {
                                $data = [
                                    'total' => 0,
                                    'correct_answers' => 0,
                                    'wrong_answers' => 0,
                                ];
                            }

                            $data['correct_answers'] += $score->correct_answers;
                            $data['wrong_answers'] += $score->wrong_answers;
                            $lessons[$lessonId] = $data;
                            ?>
                    @endforeach
                    @php($lastData = [])
                    @if(count($lessons) != 0)
                        @foreach($lessons as $lesson_id => $scores)
                                <?php
                                $std = new \stdClass();
                                $std->lesson = \App\Models\Lesson::find($lesson_id);
                                $std->correct_answers = round($scores['correct_answers'] / $exam_count,2);
                                $std->wrong_answers = round($scores['wrong_answers'] / $exam_count);
                                $std->total = round($scores['correct_answers'] / $exam_count) - (round($scores['wrong_answers'] / $exam_count) / 4);
                                $lastData[] = $std;
                                ?>
                        @endforeach
                    @endif
                    <div class="d-flex justify-content-between">
                        <h2 class="small-title">Kurum Başarı Ortalaması ({{$exam_count}} Sınavda , {{count($lessons)}} Ayrı Derste)</h2>
                    </div>
                    <div class="row gx-2">
                        <div class="col-12 p-0">
                            <div class="glide" id="glideAchievements">
                                <div class="glide__track" data-glide-el="track">
                                    <div class="glide__slides">
                                        @if(count($lastData) != 0)
                                            @foreach($lastData as $lesson)
                                                <div class="glide__slide">
                                                    <div class="card mb-5 sh-25">
                                                        <div class="card-body">
                                                            <p class="h4  mb-0 d-flex justify-content-center">{{$lesson->lesson->name}}</p>
                                                            <hr>
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr class="table-active">
                                                                    <th class="text-center">Doğru</th>
                                                                    <th class="text-center">Yanlış</th>
                                                                    <th class="text-center">Net</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="text-success text-center">{{$lesson->correct_answers}} D</td>
                                                                    <td class="text-danger text-center">{{$lesson->wrong_answers}} Y</td>
                                                                    <td class="font-weight-bold text-center">{{$lesson->total}} N</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @php($batchExams = \App\Models\BatchExams::where('organization_id',auth('organization')->user()->id)->get())
            <div class="col-md-6 mb-5">
                <div class="d-flex justify-content-between">
                    <a href="{{route('organizationAdmin.class.index')}}"><h2 class="small-title">Toplu Sınavlar</h2></a>
                </div>
                <div class="scroll-out">
                    <div class="scroll-by-count" data-count="3">
                        @foreach($batchExams as $exam)
                            @php($score = $exam->average())
                            <div class="card mb-2">
                                <div class="row g-0 sh-12">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0">
                                                    <a href="{{route('organizationAdmin.batchExam.show',$exam->id)}}" class="text-truncate">{{$exam->name}}</a>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$score->count}} Öğrenci Katıldı
                                                    </div>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$exam->created_at->format('d.m.Y H:i')}}
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                    @if(!isset($score->empty))
                                                        <span class="ms-1 w-100 font-weight-bold">
                                                        Ortalama {{$score->total}} Net
                                                    </span>
                                                    @endif
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                       type="button" href="{{route('organizationAdmin.batchExam.show',$exam->id)}}">
                                                        <i data-acorn-icon="eye" data-acorn-size="15"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-info ms-1"
                                                       type="button" href="{{route('organizationAdmin.batchExam.download',$exam->id)}}">
                                                        <i data-acorn-icon="file-chart" data-acorn-size="15"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-5">
                <div class="d-flex justify-content-between">
                    <a href="{{route('organizationAdmin.class.index')}}"><h2 class="small-title">Sınıflar</h2></a>
                </div>

                <div class="scroll-out">
                    <div class="scroll-by-count" data-count="3">
                        @foreach(\App\Models\Classes::where('organization_id',auth('organization')->user()->id)->get() as $class)
                            <div class="card mb-2">
                                <div class="row g-0 sh-12">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0">
                                                    <a href="{{route('organizationAdmin.class.show',$class->id)}}" class="text-truncate">{{$class->name}}</a>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$class->students()->count()}} Öğrenci
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                       type="button" href="{{route('organizationAdmin.class.show',$class->id)}}">
                                                        <i data-acorn-icon="eye" data-acorn-size="15"></i>
                                                    </a> <a class="btn btn-sm btn-icon btn-icon-start btn-outline-warning ms-1"
                                                            type="button" href="{{route('organizationAdmin.class.announcement.index',$class->id)}}">
                                                        <i data-acorn-icon="notification" data-acorn-size="15"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-info ms-1"
                                                       type="button" href="{{route('organizationAdmin.class.download',$class->id)}}">
                                                        <i data-acorn-icon="file-chart" data-acorn-size="15"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-5">
                <div class="d-flex justify-content-between">
                    <h2 class="small-title">Derse Katılmayan Öğrenciler</h2>

                </div>
                <ul class="nav nav-tabs nav-tabs-title nav-tabs-line-title responsive-tabs" id="lineTitleTabsContainer" role="tablist">



                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#firstLineTitleTab" role="tab" aria-selected="false">Gün</a>
                    </li><li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#secondLineTitleTab" role="tab" aria-selected="false">Hafta</a>
                    </li><li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#thirdLineTitleTab" role="tab" aria-selected="true" aria-expanded="true">Ay</a>
                    </li>
                    <li class="nav-item dropdown flex-fill justify-content-end">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">İndir</a>
                        <div class="dropdown-menu" style="">
                            <a class="dropdown-item" href="{{route('organizationAdmin.attendance.downloadAttendanceToday')}}">Gün</a>
                            <a class="dropdown-item" href="{{route('organizationAdmin.attendance.downloadAttendanceWeek')}}">Hafta</a>
                            <a class="dropdown-item" href="{{route('organizationAdmin.attendance.downloadAttendanceMonth')}}">Ay</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown ms-auto pe-0 responsive-tab-dropdown d-none">
                        <a class="btn btn-icon btn-icon-only btn-background pt-0 bg-transparent pe-0" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-more-horizontal undefined"><path d="M9 10C9 9.44772 9.44772 9 10 9V9C10.5523 9 11 9.44772 11 10V10C11 10.5523 10.5523 11 10 11V11C9.44772 11 9 10.5523 9 10V10zM2 10C2 9.44772 2.44772 9 3 9V9C3.55228 9 4 9.44772 4 10V10C4 10.5523 3.55228 11 3 11V11C2.44772 11 2 10.5523 2 10V10zM16 10C16 9.44772 16.4477 9 17 9V9C17.5523 9 18 9.44772 18 10V10C18 10.5523 17.5523 11 17 11V11C16.4477 11 16 10.5523 16 10V10z"></path></svg>
                        </a>
                        <ul class="dropdown-menu mt-2 dropdown-menu-end" style=""></ul>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show overflow-auto scroll" style="max-height: 350px;" id="firstLineTitleTab" role="tabpanel">
                        @php($dayAttendances = \App\Models\ClassAttendance::whereIn('student_id',\App\Models\Student::where('organization_id',auth('organization')->user()->id)->pluck('id')->toArray())->whereDate('created_at',\Carbon\Carbon::today())->where('attendance_status',0)->orderBy('created_at','desc')->get())
                        @if(count($dayAttendances) == 0)
                            <div class="alert alert-warning">Veri Bulunamadı</div>
                        @endif
                        @foreach($dayAttendances as $attendance)
                            @php($student = $attendance->student())
                            @php($lesson = $attendance->lesson())
                            <div class="card mb-2">
                                <div class="row g-0 sh-12">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0">
                                                    <a class="text-truncate" href="{{route('organizationAdmin.student.show',$student->id)}}">{{$student->name}}</a>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$lesson->name}} {{$attendance->created_at->format('d.m.Y H:i')}}
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                       type="button" href="{{route('organizationAdmin.student.show',$student->id)}}">
                                                        <i data-acorn-icon="eye" data-acorn-size="15"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="tab-pane fade overflow-auto scroll" style="max-height: 350px;" id="secondLineTitleTab" role="tabpanel">
                        @php($weekAttendances = \App\Models\ClassAttendance::whereIn('class_id',\App\Models\Classes::where('organization_id',auth('organization')->user()->id)->pluck('id')->toArray())->whereBetween('created_at',[\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek()])->where('attendance_status',0)->orderBy('created_at','desc')->get())
                        @if(count($weekAttendances) == 0)
                            <div class="alert alert-warning">Veri Bulunamadı</div>
                        @endif
                        @foreach($weekAttendances as $attendance)
                            @php($student = $attendance->student())
                            @php($lesson = $attendance->lesson())
                            <div class="card mb-2">
                                <div class="row g-0 sh-12">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0">
                                                    <a class="text-truncate" href="{{route('organizationAdmin.student.show',$student->id)}}">{{$student->name}}</a>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$lesson->name}} {{$attendance->created_at->format('d.m.Y H:i')}}
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                       type="button" href="{{route('organizationAdmin.student.show',$student->id)}}">
                                                        <i data-acorn-icon="eye" data-acorn-size="15"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="tab-pane fade overflow-auto scroll" style="max-height: 350px;" id="thirdLineTitleTab" role="tabpanel">
                        @php($monthAttendances = \App\Models\ClassAttendance::whereIn('student_id',\App\Models\Student::where('organization_id',auth('organization')->user()->id)->pluck('id')->toArray())->whereBetween('created_at',[\Carbon\Carbon::now()->startOfMonth(),\Carbon\Carbon::now()->endOfMonth()])->where('attendance_status',0)->orderBy('created_at','desc')->get())
                        @if(count($monthAttendances) == 0)
                            <div class="alert alert-warning">Veri Bulunamadı</div>
                        @endif
                        @foreach($monthAttendances as $attendance)
                            @php($student = $attendance->student())
                            @php($lesson = $attendance->lesson())
                            <div class="card mb-2">
                                <div class="row g-0 sh-12">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0">
                                                    <a class="text-truncate" href="{{route('organizationAdmin.student.show',$student->id)}}">{{$student->name}}</a>
                                                    <div class="text-small text-muted text-truncate">
                                                        {{$lesson->name}} {{$attendance->created_at->format('d.m.Y H:i')}}
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                    <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                       type="button" href="{{route('organizationAdmin.student.show',$student->id)}}">
                                                        <i data-acorn-icon="eye" data-acorn-size="15"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-7"></div>
        </div>



    </div>
@endsection
