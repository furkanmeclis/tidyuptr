@php
    $html_tag_data = [];
    $title = 'Sınavı Düzenle';
    $description = '';
    $breadcrumbs = [
        route('systemAdmin.index') => 'Anasayfa',
        route('systemAdmin.exam.index') => 'Sınavlar',
        $exam ? route('systemAdmin.exam.edit', $exam->id) : route('systemAdmin.exam.index') => 'Sınavı Düzenle',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
@endsection
@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/systemAdmin/exams/edit.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                @if ($exam)
                    @php
                        $student = $exam->student();
                        $results = $exam->results();
                    @endphp
                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                            <h1 class="mb-0 pb-0 display-4">{{$student->name}} - {{$exam->date()}} Sınavı Düzenle</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <div>
                        <form id="updateExamResult"
                              action="{{ route('systemAdmin.exam.update', $exam->id) }}"
                              method="POST" class="tooltip-end-bottom" novalidate autocomplete="off">
                            <div class="row">
                            @foreach($results as $result)
                                @php
                                    $lesson = $result->lesson();
                                @endphp
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
                                                       name="lessons[{{$lesson->id}}][correct_answers]"  value="{{$result->correct_answers}}" />
                                            </div>
                                            <div class="mb-3 filled">
                                                <i data-acorn-icon="multiply" class="text-danger"></i>
                                                <input type="number" class="form-control required"
                                                       placeholder="Yanlış Sayısı"
                                                       autocomplete="off"
                                                       name="lessons[{{$lesson->id}}][wrong_answers]" value="{{$result->wrong_answers}}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                            <div class="col-md-12 text-center mt-3">
                                <button class="btn btn-primary" type="submit">Güncelle</button>
                            </div>
                            </div>
                        </form>
                    </div>
                    <!-- Content End -->

                @else
                    <h2 class="small-title h1 mb-5">Kayıt Bulunamadı</h2>
                    <div class="alert alert-warning">Sınav Bulunamadı Sınavlar Sayfası İçin <a
                            href="{{ route('systemAdmin.exam.index') }}">Tıklayın</a>.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
