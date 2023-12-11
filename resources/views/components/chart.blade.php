@php($title = $title ?? '')
@php($description = $description ?? '')
@php($html_tag_data = $html_tag_data ?? [])


@section('js_components')
    <script>const GET_STATS_URL = "{{$url}}";</script>
    <script src="/js/vendor/moment-with-locales.min.js"></script>
    <script src="/js/vendor/Chart.bundle.min.js"></script>
    <script src="/js/vendor/chartjs-plugin-rounded-bar.min.js"></script>
    <script src="/js/vendor/chartjs-plugin-crosshair.js"></script>
    <script src="/js/vendor/chartjs-plugin-datalabels.js"></script>
    <script src="/js/cs/charts.extend.js"></script>
@endsection
@section('js_end')
    <script src="/js/helper.js"></script>
@endsection
<div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info" id="countes">

            </div>
        </div>
    </div>
    <div class="row" id="chartArea">
    </div>
</div>
