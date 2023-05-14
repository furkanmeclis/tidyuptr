@php
    $html_tag_data = [];
    $title = 'Ajanda';
    $description = 'Implementation for a basic events and schedule application that built on top of Full Calendar plugin.';
    $breadcrumbs = ["/"=>"Home","/Apps"=>"Apps"]
@endphp
@extends('layout',[
'html_tag_data'=>$html_tag_data,
'title'=>$title ,
'description'=>$description,
])

@section('css')
    <link rel="stylesheet" href="/css/vendor/fullcalendar.min.css"/>
@endsection

@section('js_vendor')
    <script src="/js/vendor/fullcalendar/main.min.js"></script>
@endsection

@section('js_page')
    <script>
        let CALENDAR_DATA = <?=json_encode($data)?>;
        CALENDAR_DATA = JSON.parse(JSON.stringify(CALENDAR_DATA).replace(/"\s+|\s+"/g, '"'));
    </script>
    <script src="/js/apps/calendar.js"></script>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row g-0">
                <div class="col-auto mb-2 mb-md-0 me-auto">
                    <div class="w-auto sw-md-30">
                        <h1 class="mb-0 pb-0 display-4" id="title">Ajanda</h1>
                        @include('_layout.breadcrumb',['breadcrumbs'=>$breadcrumbs])
                    </div>
                </div>
                <div class="w-100 d-md-none"></div>
                <div class="col-auto d-flex align-items-start justify-content-end">
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-only ms-1" id="goPrev">
                        <i data-acorn-icon="chevron-left"></i>
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-only ms-1" id="goNext">
                        <i data-acorn-icon="chevron-right"></i>
                    </button>
                </div>

            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <!-- Calendar Title Start -->
        <div class="d-flex justify-content-between">
            <h2 class="small-title" id="calendarTitle">Ajanda</h2>
            <button
                class="btn btn-sm btn-icon btn-icon-only btn-foreground shadow align-top mt-n2"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                aria-haspopup="true"
            >
                <i data-acorn-icon="more-horizontal" data-acorn-size="15"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end shadow">
                <a class="dropdown-item" href="#" id="monthView">Ay</a>
                <a class="dropdown-item" href="#" id="weekView">Hafta</a>
                <a class="dropdown-item" href="#" id="dayView">Gün</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" id="goToday">Bugün</a>
            </div>
        </div>
        <!-- Calendar Title End -->

        <!-- Calendar Content Start -->
        <div class="card">
            <div class="card-body">
                <div id="calendar" ></div>
            </div>
        </div>
        <!-- Calendar Content End -->

        <!-- Delete Confirm Modal Start -->
        <div class="modal fade modal-close-out" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="" style=" max-width: 100%;
  height: auto;" alt="Ajanda">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Kapat</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delete Confirm Modal End -->
    </div>
@endsection
