@php
    $html_tag_data = [];
    $title = 'Soru & Cevap';
    $description = 'Implementation for a basic events and schedule application that built on top of Full Calendar plugin.';
    $breadcrumbs = [route('student.index')=>"Anasayfa",route('student.ai.index')=>"Yol Arkadaşım"]
@endphp
@extends('layout',[
'html_tag_data'=>$html_tag_data,
'title'=>$title ,
'description'=>$description,
])

@section('css')
    <link rel="stylesheet" href="/css/vendor/baguetteBox.min.css"/>
@endsection

@section('js_vendor')
    <script src="/js/vendor/baguetteBox.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/student/ai/all.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="page-title-container">
            <div class="row g-0">
                <div class="col-auto mb-2 mb-md-0 me-auto">
                    <div class="w-auto sw-md-30">
                        <h1 class="mb-0 pb-0 display-4" id="title">Yol Arkadaşım</h1>
                        @include('_layout.breadcrumb',['breadcrumbs'=>$breadcrumbs])
                    </div>
                </div>
                <div class="col-12 col-md d-flex align-items-start justify-content-md-end">
                    <button type="button" class="btn btn-icon btn-icon-only btn-outline-primary ms-1 d-md-none" id="backButton">
                        <i data-acorn-icon="arrow-left"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row d-flex flex-grow-1 overflow-hidden pb-2 h-100">
                @php($student = auth('student')->user())
                <div class="col h-100" id="chatView">
                    <!-- Chat View Start -->
                    <div class="flex-column h-100 w-100" id="chatMode">
                        <div class="card h-100 mb-2">
                            <div class="card-body d-flex flex-column h-100 w-100 position-relative" >
                                <!-- User Start -->
                                <div class="d-flex flex-row align-items-center mb-3" >
                                    <div class="row g-0 sh-6 align-self-start" id="contactTitle" >
                                        <div class="col-auto">
                                            <div class="sh-6 sw-6 d-inline-block position-relative">
                                                <img src="{{getAvatarUrl($student->name)}}" class="img-fluid rounded-xl border border-2 border-foreground profile" alt="thumb" />
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card-body d-flex flex-row pt-0 pb-0 pe-0 pe-0 ps-2 h-100 align-items-center justify-content-between">
                                                <div class="d-flex flex-column">
                                                    <div class="name">Yol Arkadaşım</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    @if($messages->count() > 0)
                                        <a
                                            type="button"
                                            class="btn btn-outline-primary btn-icon btn-icon-only ms-1 ms-auto"
                                            href="{{route('student.ai.download')}}"
                                            target="_blank"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="bottom"
                                            title="Sohbet Kaydını İndir"
                                            data-delay='{"show":"250", "hide":"0"}'
                                        >
                                            <i data-acorn-icon="cloud-download"></i>
                                        </a>
                                    @endif
                                </div>

                                <div class="separator-light mb-3"></div>
                                <!-- User End -->
                                <div style="max-height: 500px;overflow-y: auto" class="scroll px-2" id="content_card_body">
                                    @if($messages->count() > 0)
                                        @foreach($messages as $message)
                                                    <div class="mb-2 card-content">
                                                        <div class="row g-2">
                                                            <div class="col-md-4"></div>
                                                            <div class="col-md-8 d-flex justify-content-end align-items-end content-container">
                                                                <div class="bg-gradient-light d-inline-block rounded-md py-3 px-3 ps-7 text-white position-relative">
                                                                    <span class="text">{!!  nl2br($message->question) !!}</span>
                                                                    <span class="position-absolute text-extra-small text-white opacity-75 b-2 s-2 time">{{$message->created_at->format('H:i')}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-2 card-content">
                                                        <div class="row g-2">

                                                            <div class="col-md-8 d-flex align-items-end content-container"><div class="bg-separator-light d-inline-block rounded-md py-3 px-3 pe-7 position-relative text-alternate">
                                                                    <span class="text math">{!! $message->answer() !!}</span>
                                                                    <span class="position-absolute text-extra-small text-alternate opacity-75 b-2 e-2 time">{{$message->created_at->format('H:i')}}</span>
                                                                </div></div>
                                                        </div>
                                                    </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-warning">İçerik Bulunamadı</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                            <form id="sendQuestion" method="POST" action="{{route('student.ai.store')}}">
                                <div class="card">
                                    <div class="card-body p-0 d-flex flex-row align-items-center px-3 py-3">
                                        <textarea class="form-control me-3 border-0 ps-2 py-2" required placeholder="Sorunuz" name="message" rows="2" id="chatInput"></textarea>
                                        <div class="d-flex flex-row ">
                                            <button class="btn btn-icon btn-icon-only btn-primary mb-1 rounded-xl ms-1" type="submit">
                                                <i data-acorn-icon="chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
        </div>


    </div>
@endsection
