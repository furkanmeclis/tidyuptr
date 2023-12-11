@php
    $html_tag_data = [];
    $title = 'Yeni Sınav Ekle';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.batchExam.index') => 'Sınavlar',
        route('organizationAdmin.batchExam.create') => 'Yeni Sınav Ekle'
        ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css"/>
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css"/>
@endsection
@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/student/exams/create.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-0 pb-0 display-4">{{ $title }}</h1>
                        @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                    </div>
                </section>
                <!-- Title End -->

                <!-- Content Start -->
                <div>
                    @php($examSchemes = \App\Models\ExamSchemes::where('organization_id',auth('student')->user()->organization_id)->where('grade',auth('student')->user()->grade)->get())
                    @if(count($examSchemes) != 0)
                        @php($examScheme = $examScheme == false ? $examSchemes[0] : $examScheme)
                        <div class="card mb-5">
                            <div class="card-body">
                                <select class="form-select" onchange="window.location.href = this.value;">
                                    @foreach($examSchemes as $examSchem)
                                        <option value="{{route('student.exam.create',$examSchem->id)}}" {{$examSchem->id == $examScheme->id ? "selected" :""}}>{{$examSchem->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <!-- Filled Start -->
                        <section class="scroll-section" id="filled">
                            <div class="card mb-5">
                                <div class="card-body">
                                    @if(count($examSchemes) == 0)
                                        <div class="alert alert-warning">
                                            Sınav Şeması Bulunamadı.Kurum Yöneticinizle İletişime Geçiniz.Sınıfınıza Ait Sınav Şeması Eklemesini Talep Ediniz.
                                        </div>
                                    @else
                                    <form id="createExam" action="{{ route('student.exam.store') }}"
                                          method="POST" class="tooltip-end-bottom" novalidate>
                                        <p class="text-end">
                                        <div class="row">
                                            @foreach($examScheme->lessons() as $lesson)
                                                <div class="col-md-12" id="lessonscore-{{$lesson->id}}">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="filled mb-3 w-100 me-3"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-lecture undefined"><path d="M10 18C9.06812 18 8.60218 18 8.23463 17.8478C7.74458 17.6448 7.35523 17.2554 7.15224 16.7654C7 16.3978 7 15.9319 7 15L7 13C7 12.0681 7 11.6022 7.15224 11.2346C7.35523 10.7446 7.74458 10.3552 8.23463 10.1522C8.60218 10 9.06812 10 10 10V10C10.9319 10 11.3978 10 11.7654 10.1522C12.2554 10.3552 12.6448 10.7446 12.8478 11.2346C13 11.6022 13 12.0681 13 13L13 15C13 15.9319 13 16.3978 12.8478 16.7654C12.6448 17.2554 12.2554 17.6448 11.7654 17.8478C11.3978 18 10.9319 18 10 18V18Z"></path><path d="M10 15L10 12"></path><path d="M7.05489 10L12.9451 10C13.1276 10 13.2189 10 13.3063 9.98435C13.4229 9.96348 13.5348 9.92208 13.6369 9.86206C13.7134 9.81705 13.7827 9.75766 13.9213 9.63888C14.7643 8.91629 15.1858 8.555 15.2647 8.25121C15.3698 7.84615 15.2116 7.41847 14.8682 7.17935C14.6106 7 14.0554 7 12.9451 7L7.05489 7C5.94456 7 5.3894 7 5.13183 7.17935C4.78841 7.41847 4.6302 7.84615 4.73532 8.25121C4.81417 8.555 5.23568 8.9163 6.0787 9.63889C6.21727 9.75766 6.28656 9.81705 6.36312 9.86206C6.4652 9.92208 6.57713 9.96348 6.69369 9.98435C6.78111 10 6.87237 10 7.05489 10Z"></path><path d="M14 18H6M6.49998 6.99999 6.89461 4.63219C6.96314 4.221 7.15845 3.84149 7.45323 3.54673L8 3"></path><circle cx="8" cy="3" r="0.5"></circle><path d="M13.5 7L13.1054 4.6322C13.0368 4.22101 12.8415 3.8415 12.5467 3.54674L12 3.00001"></path><circle r="0.5" transform="matrix(-1 0 0 1 12 3)"></circle></svg>
                                                                <input required class="form-control" type="text" min="0" name="data[{{$lesson->id}}][lesson_name]" placeholder="Ders İsmi" value="{{$lesson->name}}" readonly disabled>
                                                            </div>
                                                            <input required type="hidden" name="data[{{$lesson->id}}][lesson_id]" value="{{$lesson->id}}">
                                                        </div>
                                                        <div class="col">
                                                            <div class="mb-3 filled me-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-check mb-3 d-inline-block text-success"><path d="M16 5L7.7051 14.2166C7.32183 14.6424 6.65982 14.6598 6.2547 14.2547L3 11"></path></svg>
                                                                <input required class="form-control" type="number" min="0" name="data[{{$lesson->id}}][correct_answers]" placeholder="Doğru Sayısı">
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="mb-3 filled me-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-check mb-3 d-inline-block text-danger"><path d="M16 5L7.7051 14.2166C7.32183 14.6424 6.65982 14.6598 6.2547 14.2547L3 11"></path></svg>
                                                                <input required class="form-control" type="number"  min="0" name="data[{{$lesson->id}}][wrong_answers]" placeholder="Yanlış Sayısı">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                       <p class="text-center"><button class="btn btn-primary mt-3" type="submit">Kaydet</button></p>
                                    </form>
                                        @endif
                                </div>
                            </div>
                        </section>
                </div>
                <!-- Content End -->
            </div>
        </div>
    </div>
@endsection

