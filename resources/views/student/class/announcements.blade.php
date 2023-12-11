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
    <script src="/js/organizationAdmin/class/announcement.js"></script>
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
                        <h4>Duyurular {{$class->name}}</h4>
                    </div>
                    <div class="card-body" id="content_card_body" style="max-height: 500px;overflow-y:auto;">
                        @if(count($announcements) == 0)
                            <div class="alert alert-warning mb-0">Mesaj Bulunamadı</div>
                        @else
                            @foreach($announcements as $content)
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
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>
        </div>
@endsection
