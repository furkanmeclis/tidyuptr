@php
    $html_tag_data = [];
    $title = 'Duyurular';
    $description = 'Sınıfım';
    $breadcrumbs = [route('teacher.index') => 'Anasayfa', route('teacher.announcement.index') => 'Duyurular'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/quill.bubble.css"/>
@endsection

@section('js_vendor')
    <script src="/js/vendor/quill.min.js"></script>
    <script src="/js/vendor/quill.active.js"></script>
@endsection

@section('js_page')
    <script src="/js/teacher/class/announcement.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title and Top Buttons Start -->
                <div class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-12">
                            <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                        <!-- Title End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->



            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Duyurular</h4>
                    </div>
                    <div class="card-body" id="content_card_body" style="max-height: 500px;overflow-y:auto;">
                        @if(count($contents) == 0)
                            <div class="alert alert-warning mb-0">Mesaj Bulunamadı</div>
                        @else
                            @foreach($contents as $content)
                                <div class="border-dark rounded-2 px-3 py-2 mb-3">

                                    <div class="my-2">
                                        {!! $content->content !!}
                                    </div>
                                    @if($content->file != null)
                                        <hr>
                                        <div class="d-flex justify-content-end">
                                            <div title="Ek İçerik" class="sw-30 my-2">
                                                <div class="row g-0 rounded-sm sh-8 border">
                                                    <div class="col-auto">
                                                        <div class="sw-10 d-flex justify-content-center align-items-center h-100">
                                                            <i data-acorn-icon="{{$content->getIcon()}}"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col rounded-sm-end d-flex flex-column justify-content-center pe-3">
                                                        <div class="d-flex justify-content-between">
                                                            <p class="mb-0 clamp-line" data-line="1" style="overflow: hidden; text-overflow: ellipsis; -webkit-box-orient: vertical; display: -webkit-box; -webkit-line-clamp: 1;" title="{{$content->getFileName()}}">{{$content->getFileName()}}</p>
                                                            <a href="{{$content->getFileUrl()}}" download="{{$content->getFileName()}}" data-bs-toggle="tooltip" data-bs-placement="top" title="İndir" data-delay="{&quot;show&quot;:&quot;1000&quot;, &quot;hide&quot;:&quot;0&quot;}" data-bs-original-title="İndir">
                                                                <i data-acorn-icon="cloud-download"></i>
                                                            </a>
                                                        </div>
                                                        <div class="text-small text-primary" title="Boyut">{{$content->getFileSize()}}</div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class=" text-end">
                                        <time datetime="{{ $content->created_at }}" class="text-small text-muted" title="Oluşturulma Tarihi">{{ $content->created_at->format('d.m.Y H:i') }}</time>
    @if($content->teacher_id != null)
                                        <span class="badge bg-primary text-white">{{$content->teacher()->name}}</span>
    @endif
                                        @if($content->teacher_id != null && $content->teacher_id == auth('teacher')->user()->id)
                                            <div class="dropdown">
                                                <a class="dropdown-toggle mb-1" href="#" role="button" id="dropdownMenuLink-{{$content->id}}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    Detaylar
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink-{{$content->id}}" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 21px);" data-popper-placement="bottom-start">
                                                    <a class="dropdown-item text-danger delete-content-btn" href="{{route('teacher.class.announcement.destroy',[
                                                "class" => $class_id,
                                                "ann" => $content->id
                                                ])}}">
                                                        Sil
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="filled custom-control-container editor-container">
                                    <div class="html-editor sh-20" id="quillEditorFilled"></div>
                                    <i data-acorn-icon="message"></i>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <form id="createMessage" action="{{route('teacher.class.announcement.store',$class_id)}}">
                                    <input type="file" name="file" class="d-none" id="fileInput">
                                    <button class="btn btn-warning m-3 btn-block" id="addFile" type="button">Dosya Ekle</button>
                                    <button class="btn btn-primary mx-3 btn-block" type="submit">Gönder</button>
                                    <div id="fileSelected" style="display: none">
                                        <hr>
                                        <p id="fileName">Dosya Seçilmedi</p>
                                        <button class="btn mb-1 btn-sm btn-icon btn-icon-only btn-danger shadow" type="button" id="removeFile">
                                            <i data-acorn-icon="bin"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
@endsection
