@php
    $html_tag_data = [];
    $title = 'Sınıfı Görüntüle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.student.index') => 'Sınıflar',
        route('organizationAdmin.student.create') => 'Sınıfı Görüntüle',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/glide.core.min.css"/>
@endsection
@section('js_vendor')
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/glide.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/cs/glide.custom.js"></script>
    <script src="/js/plugins/carousels.js"></script>
    <script src="/js/organizationAdmin/class/helper.js"></script>
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
                    perView:3,
                    breakpoints: {
                        600: {perView: 1},
                        1400: {perView: 2},
                        1900: {perView: 4},
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
        <div class="row">
            <section class="scroll-section" id="title">
                <div class="page-title-container">
                    <h1 class="mb-0 pb-0 display-4">{{ $title }}</h1>
                    @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                </div>
            </section>
            @if($class)

                @php($students = $class->students())
                @php($student_ids = $students->pluck('student_id')->toArray())
                <h3 class="small-title h1 mb-3">{{$class->name}}</h3>
                <h3 class="h3 mb-3">Öğrenciler ({{count($student_ids)}})</h3>
                <div class="row">
                    <div class="col-12 p-0 mb-5">
                        <div class="glide" id="glideBasic">
                            <div class="glide__track" data-glide-el="track">
                                <div class="glide__slides">
                                    @foreach($students->get() as $student)
                                        @php($student = $student->student())
                                        <div class="glide__slide">
                                            <div class="card mb-5">
                                                <div class="d-flex justify-content-center mt-3"><img src="{{getAvatarUrl($student->email)}}" width="60px" height="60px" class="rounded-circle" alt="{{$student->name}}" />
                                                </div>
                                                <div class="card-body text-center">
                                                    <h5 class="card-title">{{$student->name}}</h5>
                                                    @if($exam = $student->lastExam())
                                                        <hr>
                                                        @php($score = $exam->score())
                                                        <div href="{{route('organizationAdmin.class.showExam',$exam->id)}}" class="show-exam-btn cursor-pointer">
                                                            <p>Son Bireysel Deneme Sonucu</p>
                                                            <p class="d-flex justify-content-center">
                                                                <span class="font-weight-bold">{{$score->total}} Net</span>
                                                                <span class="ms-1 text-success">{{$score->correct_answers}} D</span>
                                                                <span class="ms-1 text-danger">{{$score->wrong_answers}} Y</span>
                                                            </p>
                                                        </div>
                                                    @endif
                                                    @if($exam = $student->lastBatchExam())
                                                        <hr>
                                                        @php($score = $exam->score())
                                                        <div href="{{route('organizationAdmin.class.showExam',$exam->id)}}" class="show-exam-btn cursor-pointer">
                                                            <p>Son Toplu Deneme Sonucu</p>
                                                            <p class="d-flex justify-content-center">
                                                                <span class="font-weight-bold">{{$score->total}} Net</span>
                                                                <span class="ms-1 text-success">{{$score->correct_answers}} D</span>
                                                                <span class="ms-1 text-danger">{{$score->wrong_answers}} Y</span>
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                @php($exam_ids = \App\Models\Exams::whereIn('student_id',$student_ids)->pluck('id')->toArray())
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
                @foreach($lessons as $lesson_id => $scores)
                        <?php
                      $std = new \stdClass();
                      $std->lesson = \App\Models\Lesson::find($lesson_id);
                      $std->correct_answers = $scores['correct_answers'] / $exam_count;
                      $std->wrong_answers = round($scores['wrong_answers'] / $exam_count);
                      $std->total = round($scores['correct_answers'] / $exam_count) - (round($scores['wrong_answers'] / $exam_count) / 4);
                      $lastData[] = $std;
                    ?>
                @endforeach
                <h3 class="h3 mb-3">Sınıf Başarı Ortalaması ({{$exam_count}} Sınavda , {{count($lessons)}} Ayrı Derste)</h3>
                <div class="row gx-2">
                    <div class="col-12 p-0">
                        <div class="glide" id="glideAchievements">
                            <div class="glide__track" data-glide-el="track">
                                <div class="glide__slides">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="h3 mb-3">Sınıf Ders Programı</h3>
                @component('components.timetable',["timetable" => \App\Models\TimeTable::where('class_id',$class->id)->first()])@endcomponent
                <div class="modal fade" id="xlExample" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Sınav Sonucu</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="examResultArea"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                <div class="alert alert-warning">Görüntülenecek Kayıt Bulunamadı Sınıflar Sayfası İçin <a
                        href="{{ route('organizationAdmin.class.index') }}">Tıklayın</a>.</div>
            @endif
        </div>
    </div>
@endsection
