@php
    $html_tag_data = [];
    $title = 'Kurum Sınavları';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.batchExam.index') => 'Sınavlar'
        ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2.min.css"/>
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css"/>
@endsection

@section('js_vendor')
    <script src="/js/vendor/bootstrap-submenu.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js"></script>
    <script src="/js/organizationAdmin/exams/all.js"></script>
    <script src="/js/organizationAdmin/exams/helper.js"></script>
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
                            <h1 class="mb-0 pb-0 display-4" id="title">{{$title}}</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                        <!-- Title End -->

                    </div>
                </div>
                <!-- Title and Top Buttons End -->

                <!-- Content Start -->
                <div class="data-table-rows slim">
                    <!-- Controls Start -->
                    <div class="row">
                        <!-- Search Start -->
                        <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
                            <div
                                class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                <input class="form-control datatable-search" placeholder="Ara"
                                       data-datatable="#datatableRows" />
                                <span class="search-magnifier-icon">
                                        <i data-acorn-icon="search"></i>
                                    </span>
                                <span class="search-delete-icon d-none">
                                        <i data-acorn-icon="close"></i>
                                    </span>
                            </div>
                        </div>
                        <!-- Search End -->

                        <div class="col-sm-12 col-md-7 col-lg-9 col-xxl-10 text-end mb-1">

                            <div class="d-inline-block">
                                <a href="{{ route('organizationAdmin.batchExam.create') }}"
                                   class="btn btn-icon btn-icon-only btn-foreground-alternate shadow "
                                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0"
                                   title="Yeni Sınav Ekle" type="button">
                                    <i data-acorn-icon="plus"></i>
                                </a>
                                <button
                                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                                    data-datatable="#datatableRows" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-delay="0" title="Yazdır" type="button">
                                    <i data-acorn-icon="print"></i>
                                </button>
                                <!-- Print Button End -->

                                <!-- Export Dropdown Start -->
                                <div class="d-inline-block datatable-export" data-datatable="#datatableRows">
                                    <button class="btn p-0" data-bs-toggle="dropdown" type="button"
                                            data-bs-offset="0,3">
                                            <span
                                                class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown"
                                                data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip"
                                                title="İndir">
                                                <i data-acorn-icon="download"></i>
                                            </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <button class="dropdown-item export-copy" type="button">Kopyala</button>
                                        <button class="dropdown-item export-excel" type="button">Excel</button>
                                        <button class="dropdown-item export-cvs" type="button">Csv</button>
                                    </div>
                                </div>
                                <!-- Export Dropdown End -->

                                <!-- Length Start -->
                                <div class="dropdown-as-select d-inline-block datatable-length"
                                     data-datatable="#datatableRows" data-childSelector="span">
                                    <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                            <span class="btn btn-foreground-alternate dropdown-toggle"
                                                  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0"
                                                  title="Satır Sayısı">
                                                5 Kayıt
                                            </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <a class="dropdown-item active" href="#">5 Kayıt</a>
                                        <a class="dropdown-item" href="#">10 Kayıt</a>
                                        <a class="dropdown-item" href="#">20 Kayıt</a>
                                    </div>
                                </div>
                                <!-- Length End -->
                            </div>
                        </div>
                    </div>
                    <!-- Controls End -->

                    <!-- Table Start -->
                    <div class="data-table-responsive-wrapper">
                        <table id="datatableRows" class="data-table">
                            <thead>
                            <tr>
                                <th class="text-muted text-uppercase">#ID</th>
                                <th class="text-muted text-uppercase">Sınav Adı</th>
                                <th class="text-muted text-uppercase">Katılım</th>
                                <th class="text-muted text-uppercase">Sınav Tarihi</th>
                                <th class="empty">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($exams as $exam)
                                <tr>
                                    <td>{{ $exam->id }}</td>
                                    <td>{{$exam->name}}</td>
                                    <td>{{ \App\Models\Exams::where('batch_exam_id',$exam->id)->count() }} Öğrenci</td>
                                    <td>{{$exam->date()}}</td>
                                    <td>

                                        <a
                                            href="{{ route('organizationAdmin.batchExam.show', $exam->id) }}"
                                            class="btn mb-1 btn-sm btn-icon btn-icon-only btn-success shadow"
                                            data-bs-toggle="tooltip" data-bs-placement="left" title="Görüntüle"
                                            type="button">
                                            <i data-acorn-icon="eye"></i>
                                        </a> <a
                                            href="{{ route('organizationAdmin.batchExam.download', $exam->id) }}"
                                            class="btn mb-1 btn-sm btn-icon btn-icon-only btn-info shadow"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Sonuç İndir"
                                            type="button">
                                            <i data-acorn-icon="file-chart"></i>
                                        </a> <a
                                            data-bs-toggle="modal" data-bs-target="#importFmt-{{ $exam->id }}"
                                            class="btn mb-1 btn-sm btn-icon btn-icon-only btn-gradient-secondary shadow"
                                            title="Toplu Yükle Optik Tarayıcıdan"
                                            type="button">
                                            <i data-acorn-icon="duplicate"></i>
                                        </a> <a
                                            href="{{ route('organizationAdmin.batchExam.downloadExampleScheme', $exam->id) }}"
                                            data-form-action="{{ route('organizationAdmin.batchExam.importResults', $exam->id) }}"
                                            class="btn mb-1 btn-sm btn-icon btn-icon-only btn-gradient-primary shadow batch-exam-upload-btn"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Toplu Yükle Excelden"
                                            type="button">
                                            <i data-acorn-icon="cloud-upload"></i>
                                        </a> <button

                                            data-form-action="{{ route('organizationAdmin.batchExam.uploadAnswers', $exam->id) }}"
                                            class="btn mb-1 btn-sm btn-icon btn-icon-only btn-gradient-primary shadow"
                                            data-bs-toggle="modal" data-bs-target="#importAnswers-{{ $exam->id }}" title="Cevap Anahtarı Yükle"
                                            type="button">
                                            <i data-acorn-icon="key"></i>
                                        </button> <a
                                            href="{{ route('organizationAdmin.batchExam.edit', $exam->id) }}"
                                            class="btn mb-1 btn-sm btn-icon btn-icon-only btn-warning shadow"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"
                                            type="button">
                                            <i data-acorn-icon="edit"></i>
                                        </a> <a href="{{ route('organizationAdmin.batchExam.destroy', $exam->id) }}"
                                                class="btn  mb-1 btn-sm btn-icon btn-icon-only btn-danger shadow delete-exam-btn"
                                                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-delay="0"
                                                title="Sil" type="button">
                                            <i data-acorn-icon="bin"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Table End -->
                </div>
                <div class="modal fade" id="xlExample" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Sınav Sonucu</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="examResultArea"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="uploadExam" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Toplu Sınav Yükle</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="">
                                <div class="modal-body">
                                    <div class="alert alert-info mb-3">
                                        <span class="alert-message">Toplu Sınav Yüklemesi Yaparken Alt Taraftaki Şablonu İndir Butonuna Basarak İndirmiş Olduğunuz <b>.xlsx</b> Uzantılı Dosya Üzerinde Veri Girişi Yapınız Kendinize Ait Excel Dosyaları Yüklemeniz Sınav Sonuçlarının Yüklenmemesine Neden Olacaktır.</span>
                                    </div>
                                    <div class="alert alert-warning mb-3">
                                        <span class="alert-message">Şablonda Hazır Gelen Verilerle Oynamayınız Toplu Sınava Tanımlamış Olduğunuz Dersler Otomatik Olarak Doldurulacaktır.</span>
                                    </div>
                                    <div class="mb-3">
                                        <a href="#" id="exampleSchemeLink" class="btn d-block btn-gradient-primary">Örnek Şablonu İndir</a>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="file" name="file" class="form-control" accept=".xlsx">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Kapat</button>
                                    <button type="submit" class="btn btn-gradient-primary">Yükle</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="confirmExam" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Sınav Sonucu Onay</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-striped table-hover table-bordered" id="confirmedTable">
                                    <thead></thead>
                                    <tbody></tbody>
                                </table>
                                <div id="unConfirmed" style="display: none">
                                    <hr>
                                    <h3>Tanımlanamayan Sonuçlar</h3>
                                    <table class="table table-striped table- table-hover table-bordered" id="unConfirmedTable">
                                        <thead></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Vazgeç</button>
                                <button type="submit" class="btn btn-gradient-primary" id="confirmBtn">Onayla</button>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($exams as $exam)
                    <div class="modal fade" id="importFmt-{{$exam->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">{{$exam->name}} Adlı Toplu Sınava Ait Optik Tarayıcıdan Sonuç Yükleme</h3>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="upload-answers" action="{{route('organizationAdmin.batchExam.readExam',$exam->id)}}">
                                    <div class="modal-body">
                                        <div class="mb-3 filled">
                                            <select name="fmt_id" class="form-select" id="fmt_select">
                                                @foreach(\App\Models\OpticalParameter::all() as $fmt)
                                                    <option value="{{$fmt->id}}">{{$fmt->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="file" name="file" class="form-control" accept=".txt">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Kapat</button>
                                        <button type="submit" class="btn btn-gradient-primary">Okut</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @php($answerKey = \App\Models\AnswerKey::where('batch_exam_id',$exam->id)->first())
                    <div class="modal fade" id="importAnswers-{{$exam->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">{{$exam->name}} Adlı Toplu Sınava Ait Cevap Anahtarı Yükleme</h3>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                @if(!$answerKey)<form action="{{route('organizationAdmin.batchExam.uploadAnswers',$exam->id)}}" class="upload-answers">@endif

                                    <div class="modal-body">
                                        @if(!$answerKey)
                                            <div class="alert alert-info mb-3">
                                                <span class="alert-message">Toplu Sınav Cevap Anahtarı Yüklemesi Yaparken Alt Taraftaki Şablonu İndir Butonuna Basarak İndirmiş Olduğunuz <b>.xlsx</b> Uzantılı Dosya Üzerinde Veri Girişi Yapınız Kendinize Ait Excel Dosyaları Yüklemeniz Cevap Anahtarınızın Yüklenmemesine Neden Olacaktır.</span>
                                            </div>
                                            <div class="alert alert-warning mb-3">
                                                <span class="alert-message">Şablonda Hazır Gelen Verilerle Oynamayınız.Kolonlarda Cevap Kolonuna A Kitapçığının Cevabını,B Kolonuna B Kitapçığındaki Aynı Sorunun Soru Numarasını Giriniz.</span>
                                            </div>
                                            <div class="mb-3">
                                                <a href="{{route('organizationAdmin.batchExam.downloadAnswerScheme')}}" class="btn d-block btn-gradient-primary">Örnek Şablonu İndir</a>
                                            </div>
                                            <div class="input-group mb-3">
                                                <input type="file" name="file" class="form-control" accept=".xlsx">
                                            </div>
                                        @else
                                            <div class="alert alert-info mb-3">
                                                <span class="alert-message">Cevap Anahtarınız Eklidir.</span>
                                            </div>

                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Kapat</button>
                                        @if(!$answerKey)<button type="submit" class="btn btn-gradient-primary">Yükle</button>@endif
                                    </div>
                                    @if(!$answerKey)</form>@endif
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="modal fade" id="confirmOptic" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Sınav Sonucu Onay</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-dark" onclick="window.location.reload()">Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
