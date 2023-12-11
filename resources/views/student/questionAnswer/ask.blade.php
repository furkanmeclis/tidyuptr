@php
    $html_tag_data = [];
    $title = 'Soru & Cevap';
    $description = 'Implementation for a basic events and schedule application that built on top of Full Calendar plugin.';
    $breadcrumbs = ["/"=>"Home","/Apps"=>"Apps"]
@endphp
@extends('layout',[
'html_tag_data'=>$html_tag_data,
'title'=>$title ,
'description'=>$description,
])

@section('css')
    <link rel="stylesheet" href="/css/vendor/baguetteBox.min.css"/>
    <link rel="stylesheet" href="/css/vendor/select2.min.css"/>
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css"/>
@endsection

@section('js_vendor')
    <script src="/js/vendor/baguetteBox.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/student/questions/ask.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="page-title-container">
            <div class="row g-0">
                <div class="col-auto mb-2 mb-md-0 me-auto">
                    <div class="w-auto sw-md-30">
                        <h1 class="mb-0 pb-0 display-4" id="title">Soru Sor</h1>
                        @include('_layout.breadcrumb',['breadcrumbs'=>$breadcrumbs])
                    </div>
                </div>

            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form id="askTeacher" action="{{ route('student.questionAnswer.store') }}"
                      method="POST" class="tooltip-end-bottom" novalidate>
                    <div class="filled mb-3 w-100">
                        <i data-acorn-icon="lecture"></i>
                        <select id="selectFilled" data-placeholder="Öğretmen Seçimi" name="teacher_id">
                            <option label="&nbsp;"></option>
                            @foreach($teachers as $teacher)
                                <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 filled">
                        <textarea placeholder="Sorunuz" name="question" class="form-control" rows="3"></textarea>
                        <i data-acorn-icon="question-circle"></i>
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" name="file" accept="image/*">
                    </div>
                    <button class="btn btn-primary" type="submit">Ekle</button>
                </form>

            </div>
        </div>
    </div>
@endsection
