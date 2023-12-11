@php
    $html_tag_data = [];
    $title = 'Optik Form Şemaları';
    $description = '';
    $breadcrumbs = [
        route('organizationAdmin.index') => 'Anasayfa',
        route('organizationAdmin.student.index') => 'Öğrenciler',
    ];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/bootstrap-submenu.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js"></script>
    <script src="/js/organizationAdmin/students/all.js"></script>
    <script src="/js/organizationAdmin/schemes/helper.js"></script>
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
                                <!-- Print Button Start -->
                                <a href="{{ route('organizationAdmin.optic.create') }}"
                                   class="btn btn-icon btn-icon-only btn-foreground-alternate shadow "
                                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0"
                                   title="Yeni Şema Ekle" type="button">
                                    <i data-acorn-icon="plus"></i>
                                </a>

                                <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                                        data-datatable="#datatableRows" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-delay="0" title="Yazdır" type="button">
                                    <i data-acorn-icon="print"></i>
                                </button>
                                <!-- Print Button End -->

                                <!-- Export Dropdown Start -->
                                <div class="d-inline-block datatable-export" data-datatable="#datatableRows">
                                    <button class="btn p-0" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                        <span class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown"
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
                                        <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip"
                                              data-bs-placement="top" data-bs-delay="0" title="Satır Sayısı">
                                            5 Şema
                                        </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <a class="dropdown-item active" href="#">5 Şema</a>
                                        <a class="dropdown-item" href="#">10 Şema</a>
                                        <a class="dropdown-item" href="#">20 Şema</a>
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
                                <th class="text-muted text-uppercase">Şema Adı</th>
                                <th class="text-muted text-uppercase">Veri Sayısı</th>
                                <th class="empty">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($schemes as $scheme)
                                <tr>
                                    <td>{{ $scheme->name }}</td>
                                    <td>{{ $scheme->areas()->count() }} Adet Veri</td>
                                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#showScheme-{{ $scheme->id }}"
                                           class="btn mb-1 btn-sm btn-icon btn-icon-only btn-success shadow "
                                           title="Görüntüle" type="button">
                                            <i data-acorn-icon="eye"></i>
                                        </a> <a onclick="iziToast.info({title:'Üzgünüz',message:'Bu Özellik Üzerinde Çalışmalarımızı Devam Ettiriyoruz'})"
                                                class="btn mb-1 btn-sm btn-icon btn-icon-only btn-warning shadow "
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"
                                                type="button">
                                            <i data-acorn-icon="edit"></i>
                                        </a> <a href="{{ route('organizationAdmin.optic.destroy', $scheme->id) }}"
                                                class="btn  mb-1 btn-sm btn-icon btn-icon-only btn-danger shadow delete-scheme-btn"
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
                    @foreach($schemes as $scheme)
                        <div class="modal fade" id="showScheme-{{$scheme->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title">{{$scheme->name}} Adlı Şemaya Görünüm</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body ">
                                        <div class="d-flex justify-content-center">
                                            <div class="paper-for-fmt">
                                                @php($paperX = json_decode($scheme->values)->xl)
                                                @php($paperY = json_decode($scheme->values)->yl)
                                                @php($list = [])
                                                @foreach($scheme->areas()->get() as $key => $shape)
                                                    @php($coordinates = json_decode($shape->coordinates,true))
                                                    @php($info = getPaperResize($coordinates,$paperX,$paperY))
                                                    @php($list[$key]['name'] = $shape->type == 'empty' ? $shape->name : ($shape->type == 'lesson' ? 'Ders Eşlemesi' : $shape->name))
                                                    @php($list[$key]['matched'] = $shape->type != 'empty')
                                                    <div class="shape {{$shape->type != 'empty' ? 'bg-success':'bg-warning'}}" style="--top:{{$info->y}}px;--left:{{$info->x}}px;--width:{{$info->width}}px;--height:{{$info->height}}px">
                                                        {{ $key+1 }}
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                        <hr>
                                        <ul>
                                            @foreach($list as $key => $item)
                                                <li><span class="badge bg-{{$item['matched'] ? 'success':'warning'}}">{{$key+1}}</span> {{$item['name']}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Kapat</button>
                                        <a href="{{route('organizationAdmin.optic.downloadFmt',$scheme->id)}}?reader=sekonic" type="button" class="btn btn-gradient-primary">İndir(Sekonic)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
