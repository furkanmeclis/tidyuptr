@php
    $html_tag_data = [];
    $title = 'Analizim';
    $description = '';
    $breadcrumbs = [];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                    <section class="scroll-section" id="title">
                        <div class="page-title-container">
                                <h1 class="mb-0 pb-0 display-4" id="descriptionForCharts">Analizim
                            </h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                    </section>
                    <div class="row">
                        <div class="col-md-12">
                            @component('components.chart',["url"=> route('student.getStatsStudent')])
                            @endcomponent
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
