@php
    $html_tag_data = [];
    $title = 'Sıralama Hesapla';
    $description = 'Home screen that contains stats, charts, call to action buttons and various listing elements.';
    $breadcrumbs = ['/' => 'Home', '/Dashboards' => 'Dashboards'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')

@endsection

@section('js_vendor')
@endsection

@section('js_page')
    <script>
        const BASE_URL = '{{ URL::to('/') }}/';
    </script>
    <script src="/js/student/preferenceRobot.js"></script>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-sm-6 d-flex align-items-start justify-content-end">
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                                <strong><label class="form-label">Diploma Puanı</label></strong>
                                <input name="diploma" class="form-control">
                            </div><div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <strong><label class="form-check-label" for="flexCheckDefault">Geçen sene bir bölüme yerleştim. </label></strong>
                            </div>

                    </div>
                    <div class="card-body">
                        <h4>Temel Yeterlilik Testi(TYT)</h4>
                        <table class="table table-sm mt-3 table-borderless">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Doğru</th>
                                <th scope="col">Yanlış</th>
                                <th scope="col">Net</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">Türkçe(40)</th>
                                <td><input type="number" class="form-control" name="tyt-t-d" id="tyt-t-d" size="3"></td>
                                <td><input type="number" class="form-control" name="tyt-t-y" id="tyt-t-y" size="3"></td>
                                <td><input type="number" class="form-control" name="tyt-t-n" id="tyt-t-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Sosyal(20)</th>
                                <td><input type="number" class="form-control" name="tyt-s-d" id="tyt-s-d" size="3"></td>
                                <td><input type="number" class="form-control" name="tyt-s-y" id="tyt-s-y" size="3"></td>
                                <td><input type="number" class="form-control" name="tyt-s-n" id="tyt-s-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Matematik(40)</th>
                                <td><input type="number" class="form-control" name="tyt-m-d" id="tyt-m-d" size="3"></td>
                                <td><input type="number" class="form-control" name="tyt-m-y" id="tyt-m-y" size="3"></td>
                                <td><input type="number" class="form-control" name="tyt-m-n" id="tyt-m-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Fen(20)</th>
                                <td><input type="number" class="form-control" name="tyt-f-d" id="tyt-f-d" size="3"></td>
                                <td><input type="number" class="form-control" name="tyt-f-y" id="tyt-f-y" size="3"></td>
                                <td><input type="number" class="form-control" name="tyt-f-n" id="tyt-f-n" size="3" readonly=""></td>
                            </tr>
                            </tbody>
                        </table>
                        <hr>
                        <h4>Alan Yeterlilik Testi(AYT)</h4>
                        <table class="table table-sm mt-3 table-borderless">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Doğru</th>
                                <th scope="col">Yanlış</th>
                                <th scope="col">Net</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">Matematik(40)</th>
                                <td><input type="number" class="form-control" name="yks-m-d" id="yks-m-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-m-y" id="yks-m-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-m-n" id="yks-m-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Fizik(14)</th>
                                <td><input type="number" class="form-control" name="yks-f-d" id="yks-f-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-f-y" id="yks-f-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-f-n" id="yks-f-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Kimya(13)</th>
                                <td><input type="number" class="form-control" name="yks-k-d" id="yks-k-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-k-y" id="yks-k-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-k-n" id="yks-k-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Biyoloji(13)</th>
                                <td><input type="number" class="form-control" name="yks-b-d" id="yks-b-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-b-y" id="yks-b-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-b-n" id="yks-b-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Edebiyat(24)</th>
                                <td><input type="number" class="form-control" name="yks-e-d" id="yks-e-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-e-y" id="yks-e-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-e-n" id="yks-e-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Tarih-1(10)</th>
                                <td><input type="number" class="form-control" name="yks-t-d" id="yks-t-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-t-y" id="yks-t-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-t-n" id="yks-t-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Coğrafya-1(6)</th>
                                <td><input type="number" class="form-control" name="yks-c-d" id="yks-c-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-c-y" id="yks-c-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-c-n" id="yks-c-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Tarih-2(11)</th>
                                <td><input type="number" class="form-control" name="yks-t2-d" id="yks-t2-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-t2-y" id="yks-t2-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-t2-n" id="yks-t2-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Coğrafya-2(11)</th>
                                <td><input type="number" class="form-control" name="yks-c2-d" id="yks-c2-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-c2-y" id="yks-c2-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-c2-n" id="yks-c2-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Felsefe(12)</th>
                                <td><input type="number" class="form-control" name="yks-fe-d" id="yks-fe-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-fe-y" id="yks-fe-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-fe-n" id="yks-fe-n" size="3" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Din / Felsefe(6)</th>
                                <td><input type="number" class="form-control" name="yks-d-d" id="yks-d-d" size="3" maxlength="1"></td>
                                <td><input type="number" class="form-control" name="yks-d-y" id="yks-d-y" size="3" maxlength="1"></td>
                                <td><input type="number" class="form-control" name="yks-d-n" id="yks-d-n" size="3" maxlength="1" readonly=""></td>
                            </tr>
                            <tr>
                                <th scope="row">Dil(80)</th>
                                <td><input type="number" class="form-control" name="yks-di-d" id="yks-di-d" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-di-y" id="yks-di-y" size="3"></td>
                                <td><input type="number" class="form-control" name="yks-di-n" id="yks-di-n" size="3" readonly=""></td>
                            </tr>
                            </tbody>
                        </table>
                        <hr>
                        <h4>Hesaplama Sonuçları</h4>
                        <table class="table table-sm mt-3 table-borderless">
                            <thead>
                            <tr>
                                <th>Puan Türü</th>
                                <th>Ham Puanı</th>
                                <th>Yerleştirme Puanı</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>TYT</td>
                                <td><input type="number" readonly="" class="form-control" name="tyt-ham" id="tyt-ham"></td>
                                <td><input type="number" readonly="" class="form-control" name="tyt-yer" id="tyt-yer"></td>

                            </tr>
                            <tr>
                                <td>Sayısal</td>
                                <td><input type="number" readonly="" class="form-control" id="yks-say-ham" name="yks-say-ham"> </td>
                                <td><input type="number" readonly="" class="form-control" id="yks-say-yer" name="yks-say-yer"></td>

                            </tr>
                            <tr>
                                <td>Sözel</td>
                                <td><input type="number" readonly="" class="form-control" id="yks-soz-ham" name="yks-soz-ham"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-soz-yer" name="yks-soz-yer"></td>

                            </tr>
                            <tr>
                                <td>Eşit Ağırlık</td>
                                <td><input type="number" readonly="" class="form-control" id="yks-ea-ham" name="yks-ea-ham"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-ea-yer" name="yks-ea-yer"></td>

                            </tr>
                            <tr>
                                <td>Yabancı Dil</td>
                                <td><input type="number" readonly="" class="form-control" id="yks-dil-ham" name="yks-dil-ham"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-dil-yer" name="yks-dil-yer"></td>

                            </tr>
                            </tbody>
                        </table>
                        <hr>
                        <h4>Sıralama Sonuçları</h4>
                        <table class="table mt-3 table-sm table-borderless">
                            <thead>
                            <tr>
                                <th>Puan Türü</th>
                                <th>2020</th>
                                <th>2021</th>
                                <th>2022</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>TYT</td>
                                <td><input type="number" readonly="" class="form-control" name="tyt-2020" id="tyt-2020"></td>
                                <td><input type="number" readonly="" class="form-control" name="tyt-2021" id="tyt-2021"></td>
                                <td><input type="number" readonly="" class="form-control" name="tyt-2022" id="tyt-2022"></td>
                            </tr>
                            <tr>
                                <td>Sayısal</td>
                                <td><input type="number" readonly="" class="form-control" id="yks-say-2020" name="yks-say-2020"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-say-2021" name="yks-say-2021"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-say-2022" name="yks-say-2022"></td>
                            </tr>
                            <tr>
                                <td>Sözel</td>
                                <td><input type="number" readonly="" class="form-control" id="yks-soz-2020" name="yks-soz-2020"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-soz-2021" name="yks-soz-2021"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-soz-2022" name="yks-soz-2022"></td>
                            </tr>
                            <tr>
                                <td>Eşit Ağırlık</td>
                                <td><input type="number" readonly="" class="form-control" id="yks-ea-2020" name="yks-ea-2020"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-ea-2021" name="yks-ea-2021"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-ea-2022" name="yks-ea-2022"></td>
                            </tr>
                            <tr>
                                <td>Yabancı Dil</td>
                                <td><input type="number" readonly="" class="form-control" id="yks-dil-2020" name="yks-dil-2020"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-dil-2021" name="yks-dil-2021"></td>
                                <td><input type="number" readonly="" class="form-control" id="yks-dil-2022" name="yks-dil-2022"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




    </div>

@endsection
