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
@endsection

@section('js_vendor')
    <script src="/js/vendor/baguetteBox.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/student/questions/all.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="page-title-container">
            <div class="row g-0">
                <div class="col-auto mb-2 mb-md-0 me-auto">
                    <div class="w-auto sw-md-30">
                        <h1 class="mb-0 pb-0 display-4" id="title">Soru & Cevap</h1>
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
            <div class="col-auto w-100 w-md-auto h-100" id="contactView">
                <div class="sw-md-30 sw-lg-40 w-100 d-flex flex-column h-100">
                    <div class="card h-100">
                        <div class="card-header border-0 pb-0">
                            <ul class="nav nav-tabs nav-tabs-line card-header-tabs" role="tablist">
                                <li class="nav-item w-100 text-center" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#messages" role="tab" aria-selected="true">Sorular</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body h-100-card">
                            <div class="tab-content h-100">
                                <!-- Messages Start -->
                                <div class="tab-pane fade active show h-100 scroll-out" id="messages" role="tabpanel">
                                    <div class="h-100 nav py-0" >
                                        @foreach($questions as $question)
                                            <a href="{{route('student.questionAnswer.index',$question->id)}}" class="row w-100 d-flex flex-row g-0 sh-5 mb-2 nav-link p-0 contact-list-item border-bottom {{$activeQuestion->id == $question->id ? "active":""}}" id="{{$activeQuestion->id == $question->id ? "activeQuestion":""}}">

                                                <div class="col">
                                                    <div class="card-body d-flex flex-row pt-0 pb-0 ps-3 pe-0 h-100 align-items-center justify-content-between">
                                                        <div class="d-flex flex-column">
                                                            <div class="mb-1" style="{{$question->is_answered ? 'text-decoration: line-through;':""}}" title="{{$question->question}}">{{\Illuminate\Support\Str::words($question->question,3,"...")}}</div>
                                                            <div class="text-small text-muted clamp-line" data-line="1" title="{{$question->created_at}}">
                                                                {{$question->created_at->diffForHumans()}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>

                                        @endforeach

                                    </div>
                                </div>
                                <!-- Messages End -->
                            </div>
                        </div>

                        <div class="card-footer px-3 py-2 d-flex justify-content-center align-items-center">
                            {{ $questions->links() }}
                        </div>
                    </div>
                </div>
            </div>

            @if($activeQuestion)
                @php($student = $activeQuestion->student())
                @php($teacher = $activeQuestion->teacher())
                @php($answers = $activeQuestion->answers())
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
                                                <img src="{{getAvatarUrl($teacher->name)}}" class="img-fluid rounded-xl border border-2 border-foreground profile" alt="thumb" />
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card-body d-flex flex-row pt-0 pb-0 pe-0 pe-0 ps-2 h-100 align-items-center justify-content-between">
                                                <div class="d-flex flex-column">
                                                    <div class="name">{{$activeQuestion->question}}</div>
                                                    <a href="#" class="text-small text-muted last">{{$teacher->name}}</a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    @if($answers->count() > 0)
                                        <div class="ms-auto ms-1">
                                            @if(!$activeQuestion->is_answered)
                                                <a
                                                    type="button"
                                                    class="btn btn-outline-primary btn-icon btn-icon-only is-answered"
                                                    href="{{route('student.questionAnswer.complete',$activeQuestion->id)}}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom"
                                                    title="Sorum Cevaplandı"
                                                    data-delay='{"show":"250", "hide":"0"}'
                                                >
                                                    <i data-acorn-icon="check-circle"></i>
                                                </a>
                                            @endif
                                        <a
                                            type="button"
                                            class="btn btn-outline-primary btn-icon btn-icon-only "
                                            href="{{route('student.questionAnswer.download',$activeQuestion->id)}}"
                                            target="_blank"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="bottom"
                                            title="Sohbet Kaydını İndir"
                                            data-delay='{"show":"250", "hide":"0"}'
                                        >
                                            <i data-acorn-icon="cloud-download"></i>
                                        </a>

                                        </div>
                                    @endif
                                </div>

                                <div class="separator-light mb-3"></div>
                                <!-- User End -->
                                <div style="max-height: 500px;overflow-y: auto" class="scroll px-2" id="content_card_body">
                                @if($answers->count() > 0)
                                @foreach($answers as $answer)
                                    @if(!$answer->is_teacher)
                                        @if($answer->file != null)
                                            <div class="mb-2 card-content">
                                                <div class="row g-2">
                                                    <div class="col d-flex justify-content-end align-items-end content-container">
                                                        <div class="d-inline-block sh-11 ms-2 position-relative pb-4 bg-primary rounded-md">
                                                            <a href="{{$answer->getFileUrl()}}" data-caption="{{$answer->getFileName()}}" class="lightbox h-100 attachment">
                                                                <img src="{{$answer->getFileUrl()}}" class="h-100 rounded-md-top">
                                                            </a>
                                                            <span class="position-absolute text-extra-small text-white opacity-75 b-2 s-2 time">{{$answer->created_at->format('H:i')}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                        <div class="mb-2 card-content">
                                            <div class="row g-2">
                                                <div class="col d-flex justify-content-end align-items-end content-container">
                                                    <div class="bg-gradient-light d-inline-block rounded-md py-3 px-3 ps-7 text-white position-relative">
                                                        <span class="text">{{$answer->answer}}</span>
                                                        <span class="position-absolute text-extra-small text-white opacity-75 b-2 s-2 time">{{$answer->created_at->format('H:i')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @else
                                        @if($answer->file != null)
                                            <div class="mb-2 card-content">
                                                <div class="row g-2">

                                                    <div class="col d-flex align-items-end content-container"><div class="d-inline-block sh-11 me-2 position-relative pb-4 rounded-md bg-separator-light text-alternate">
                                                            <a href="{{$answer->getFileUrl()}}" data-caption="{{$answer->getFileName()}}" class="lightbox h-100 attachment">
                                                                <img src="{{$answer->getFileUrl()}}" class="h-100 rounded-md-top">
                                                            </a>
                                                            <span class="position-absolute text-extra-small text-alternate opacity-75 b-2 e-2 time">{{$answer->created_at->format('H:i')}}</span>
                                                        </div></div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mb-2 card-content">
                                                <div class="row g-2">

                                                    <div class="col d-flex align-items-end content-container"><div class="bg-separator-light d-inline-block rounded-md py-3 px-3 pe-7 position-relative text-alternate">
                                                            <span class="text">{{$answer->answer}}</span>
                                                            <span class="position-absolute text-extra-small text-alternate opacity-75 b-2 e-2 time">{{$answer->created_at->format('H:i')}}</span>
                                                        </div></div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                                @else
                                    <div class="alert alert-warning">İçerik Bulunamadı</div>
                                @endif
                            </div>
                            </div>
                        </div>
                        @if(!$activeQuestion->is_answered)
                            <form id="sendAnswer" method="POST" action="{{route('student.questionAnswer.answer',$activeQuestion->id)}}">
                                <div class="card">
                                    <div class="card-body p-0 d-flex flex-row align-items-center px-3 py-3">
                                        <textarea class="form-control me-3 border-0 ps-2 py-2" required placeholder="Mesajınız" name="message" rows="2" id="chatInput"></textarea>
                                        <div class="d-flex flex-row ">
                                            <input class="file-upload d-none" type="file" accept="image/*" id="chatAttachmentInput" name="file" />
                                            <button class="btn btn-icon btn-icon-only btn-outline-primary mb-1 rounded-xl  tooltip-center-top" id="chatAttachButton" type="button" title="Seçilmedi">
                                            <span id="unselectedFiles" class="">
                                                <i data-acorn-icon="file-image"></i>
                                            </span>
                                                <span id="selectedFiles" class="d-none">
                                                <i data-acorn-icon="check"></i>
                                            </span>
                                            </button>
                                            <button class="btn btn-icon btn-icon-only btn-primary mb-1 rounded-xl ms-1" type="submit">
                                                <i data-acorn-icon="chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                        <!-- Message Input End -->
                    </div>
                </div>
            @endif
        </div>


    </div>
@endsection
