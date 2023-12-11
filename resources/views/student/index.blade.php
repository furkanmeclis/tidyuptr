@php
    $html_tag_data = [];
    $title = 'Anasayfa';
    $description = 'Home screen that contains stats, charts, call to action buttons and various listing elements.';
    $breadcrumbs = ['/' => 'Home', '/Dashboards' => 'Dashboards'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/glide.core.min.css"/>
@endsection

@section('js_vendor')
    <script src="/js/vendor/Chart.bundle.min.js"></script>
    <script src="/js/vendor/chartjs-plugin-datalabels.js"></script>
    <script src="/js/vendor/chartjs-plugin-rounded-bar.min.js"></script>
    <script src="/js/vendor/glide.min.js"></script>
@endsection

@section('js_page')
    <script>
        const GetStatsUrl = "{{route('student.stats.getExamResults')}}";
    </script>
    <script src="/js/cs/glide.custom.js"></script>
    <script src="/js/cs/charts.extend.js"></script>
    <script src="/js/student/dashboard.js"></script>

@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-sm-6 d-flex align-items-start justify-content-end">
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <div class="row mb-3">
            <div id="examScoreArea" class="col-12 col-xl-6">
                <div class="mb-5">
                    <h2 class="small-title">Deneme Sonuçlarım</h2>
                    <div id="normalExamScore" class="card mb-2 h-auto sh-xl-24 ">
                        <div class="card-body ">
                            <div class="row g-0 h-100 chart-container">
                                <!-- Contents for below are provided from js -->
                                <div
                                        class="col-12 col-sm-auto d-flex flex-column justify-content-between custom-tooltip pe-0 pe-sm-4">
                                    <p class="heading title mb-1"></p>
                                    <div>
                                        <div>
                                            <div class="cta-2 text-primary value d-inline-block align-middle"></div>
                                            <i class="icon d-inline-block align-middle text-primary"
                                               data-acorn-size="15"></i>
                                        </div>
                                        <div class="text-small text-muted mb-1 text"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="cta-3 text-alternate average-score-normal"></div>
                                            <div class="text-small text-muted mb-1">Ortalama Net</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm sh-17">
                                    <canvas id="largeLineChart1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="batchExamScore" class="card h-auto sh-xl-24">
                        <div class="card-body">
                            <div class="row g-0 h-100 chart-container">
                                <!-- Contents for below are provided from js -->
                                <div
                                        class="col-12 col-sm-auto d-flex flex-column justify-content-between custom-tooltip pe-0 pe-sm-4">
                                    <p class="heading title"></p>
                                    <div>
                                        <div>
                                            <div class="cta-2 text-primary value d-inline-block align-middle"></div>
                                            <i class="icon d-inline-block align-middle text-primary"
                                               data-acorn-size="15"></i>
                                        </div>
                                        <div class="text-small text-muted mb-1 text"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="cta-3 text-alternate average-score-batch"></div>
                                            <div class="text-small text-muted mb-1">Ortalama Net</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm sh-17">
                                    <canvas id="largeLineChart2"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Products Start -->
            <div class="col-12 col-xl-6 mb-5">
                <div class="row">

                    @if(\App\Models\StudentTeacher::where('student_id',auth('student')->user()->id)->first())
                        <div class="col-md-6 mb-5">
                            <div class="d-flex justify-content-between">
                                <a href="{{route('student.assignment.index')}}"><h2 class="small-title">Ödevler</h2></a>
                            </div>

                            <div class="scroll-out">
                                <div class="scroll-by-count" data-count="3">
                                    @foreach(auth('student')->user()->assignments() as $assignment)
                                        <div class="card mb-2">
                                            <div class="row g-0 sh-12">
                                                <div class="col">
                                                    <div class="card-body pt-0 pb-0 h-100">
                                                        <div class="row g-0 h-100 align-content-center">
                                                            <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0 {{auth('student')->user()->assignmentResponse($assignment->id) ? "text-decoration-line-through":""}}">
                                                                <a href="{{route('student.assignment.show',$assignment->id)}}"
                                                                   class="text-truncate">{{$assignment->title}}</a>
                                                                <div class="text-small text-muted text-truncate">
                                                                    {{strip_tags($assignment->description)}}
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                                <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                                   type="button"
                                                                   href="{{route('student.assignment.show',$assignment->id)}}">
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
                    @endif
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <a href="{{route('student.questionAnswer.index')}}"><h2 class="small-title">Soru &
                                    Cevap</h2></a>
                        </div>

                        <div class="scroll-out">
                            <div class="scroll-by-count" data-count="3">
                                @foreach(auth('student')->user()->questions() as $question)
                                    <div class="card mb-2">
                                        <div class="row g-0 sh-12">
                                            <div class="col">
                                                <div class="card-body pt-0 pb-0 h-100">
                                                    <div class="row g-0 h-100 align-content-center">
                                                        <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0 {{$question->is_answered ? "text-decoration-line-through":""}}">
                                                            <a href="{{route('student.questionAnswer.index',$question->id)}}"
                                                               class="text-truncate">{{$question->question}}</a>
                                                            <div class="text-small text-muted text-truncate">
                                                                {{$question->teacher()->name}}
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end">
                                                            <a class="btn btn-sm btn-icon btn-icon-start btn-outline-primary ms-1"
                                                               type="button"
                                                               href="{{route('student.questionAnswer.index',$question->id)}}">
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
                    @php($lastExams = \App\Models\Exams::where('student_id',auth('student')->user()->id)->orderBy('id','desc')->take(3)->pluck('id')->toArray())
                    @if($lastExams > 0)
                        @php($wrongQuestions = \App\Models\ExamAnalysis::whereIn('exam_id',$lastExams)->where('status','wrong')->get())
                        @if(count($wrongQuestions) > 0)
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <h2 class="small-title">Son 3 Denemedeki Hatalı Sorularım</h2>
                                </div>

                                <div class="scroll-out">
                                    <div class="scroll-by-count" data-count="3">
                                        @foreach($wrongQuestions as $question)
                                            <div class="card mb-1">
                                                <div class="row g-0 sh-12">
                                                    <div class="col">
                                                        <div class="card-body pt-0 pb-0 h-100">
                                                            <div class="row g-0 h-100 align-content-center">
                                                                <div class="col-12 col-md-7 d-flex flex-column mb-2 mb-md-0 ">
                                                                    <a class="text-truncate">{{$question->topic->name}}</a>
                                                                    <div class="text-small text-muted text-truncate">
                                                                        {{$question->lesson->name}}
                                                                        - {{$question->question_number}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach,

                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                </div>
            </div>
            <!-- Products End -->
        </div>
        <div class="row">
            <div class="col-md-12">

                <h2 class="small-title">Ders Programım (Bireysel)</h2>
                @component('components.timetable',["timetable" => $timeTable])
                @endcomponent
            </div>
        </div>


    </div>
@endsection
