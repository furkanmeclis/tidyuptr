@php
    $html_tag_data = [];
    $title = 'Öğrenciler';
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
    <script src="/js/organizationAdmin/students/helper.js"></script>
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
                                <a href="{{ route('organizationAdmin.student.create') }}"
                                   class="btn btn-icon btn-icon-only btn-foreground-alternate shadow "
                                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0"
                                   title="Yeni Öğrenci Ekle" type="button">
                                    <i data-acorn-icon="plus"></i>
                                </a>
                                <button data-bs-toggle="modal" data-bs-target="#uploadStudent" href="#"
                                   class="btn btn-icon btn-icon-only btn-foreground-alternate shadow"
                                   title="Toplu Öğrenci Yükle" type="button">
                                    <i data-acorn-icon="cloud-upload"></i>
                                </button>
                                <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                                        data-datatable="#datatableRows" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-delay="0" title="Yazdır" type="button">
                                    <i data-acorn-icon="print"></i>
                                </button>
                                <!-- Print Button End -->

                                <!-- Export Dropdown Start -->
                                <div class="d-inline-block" data-datatable="#datatableRows">
                                    <button class="btn p-0" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                        <span class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown"
                                              data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip"
                                              title="İndir">
                                            <i data-acorn-icon="download"></i>
                                        </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <a class="dropdown-item" href="{{route('organizationAdmin.student.download','xlsx')}}" type="button">Excel</a>
                                        <a class="dropdown-item" href="{{route('organizationAdmin.student.download','csv')}}" type="button">Csv</a>
                                        <a class="dropdown-item" href="{{route('organizationAdmin.student.download','html')}}" type="button">HTML</a>
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
                                            5 Öğrenci
                                        </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <a class="dropdown-item active" href="#">5 Öğrenci</a>
                                        <a class="dropdown-item" href="#">10 Öğrenci</a>
                                        <a class="dropdown-item" href="#">20 Öğrenci</a>
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
                                <th class="text-muted text-uppercase">Öğrenci Adı</th>
                                <th class="text-muted text-uppercase">Email Adresi</th>
                                <th class="text-muted text-uppercase">Öğretmen Adı</th>
                                <th class="empty">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->getTeacher()->name }}</td>
                                    <td><a href="{{ route('organizationAdmin.student.show', $student->id) }}"
                                           class="btn mb-1 btn-sm btn-icon btn-icon-only btn-success shadow "
                                           data-bs-toggle="tooltip" data-bs-placement="left" data-bs-delay="0"
                                           title="Görüntüle" type="button">
                                            <i data-acorn-icon="eye"></i>
                                        </a> <button
                                            class="btn mb-1 btn-sm btn-icon btn-icon-only btn-gradient-primary shadow"
                                            data-bs-toggle="modal" data-bs-target="#parentDetails-{{ $student->id }}" title="Veli Bilgileri"
                                            type="button">
                                            <i class="bi-people"></i>
                                        </button> <a href="{{ route('organizationAdmin.student.exam.index', $student->id) }}"
                                                class="btn mb-1 btn-sm btn-icon btn-icon-only btn-info shadow "
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Sınavları Görüntüle"
                                                type="button">
                                            <i data-acorn-icon="quiz"></i>
                                        </a> <a href="{{ route('organizationAdmin.student.edit', $student->id) }}"
                                                class="btn mb-1 btn-sm btn-icon btn-icon-only btn-warning shadow "
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"
                                                type="button">
                                            <i data-acorn-icon="edit"></i>
                                        </a> <a href="{{ route('organizationAdmin.student.destroy', $student->id) }}"
                                                class="btn  mb-1 btn-sm btn-icon btn-icon-only btn-danger shadow delete-student-btn"
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
                <div class="modal fade" id="uploadStudent" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Toplu Öğrenci Yükle</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{route('organizationAdmin.student.importStudents')}}">
                                <div class="modal-body">
                                    <div class="alert alert-info mb-3">
                                        <span class="alert-message">
                                            Öğrenci Aktarımı İçin Dosyanız İçerisinde Bulunması Gereken Veriler Listelenmiştir.
                                            <ul>
                                                <li>name : <code>Ad Soyad</code>, <code>Adı Soyadı</code>, <code>Ad</code>,<code>name</code></li>
                                                <li>email : <code>E-Posta</code>, <code>Email</code>, <code>Mail Adresi</code>, <code>Mail</code>, <code>email</code></li>
                                                <li>identity_number : <code>Kimlik Numarası </code>, <code>Kimlik No </code>, <code>TC Kimlik No </code>, <code>TC</code>, <code>identity_number</code></li>
                                                <li>phone : <code>Telefon</code>, <code>Telefonu</code>, <code>Telefon Numarası</code>, <code>phone</code></li>
                                                <li>address : <code>Adres</code>, <code>Adresi</code>, <code>Adres Bilgisi</code>, <code>address</code></li>
                                            </ul>
                                            <b>Yalnızca Yukarıda Listelenen İsimleri Kolon İsmi Olarak Kullanın</b>
                                        </span>
                                    </div>
                                    <div class="alert alert-warning mb-3">
                                        <span class="alert-message">Yalnızca <b>.xlsx</b> Formatlı Dosyalar İşleme Alınır.Yukarıda Listelenen Veriler Haricindeki Bilgiler İşlenmeyecektir.</span>
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
                <div class="modal fade" id="confirmStudents" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Öğrenci Listesi</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-success mb-3">
                                    <span class="alert-message">Aktarılan Öğrencilerin Şifreleri Kimlik Numaralarının İlk <b>8</b> Hanesidir.</span>
                                </div>
                                <div class="alert alert-info mb-3">
                                    <span class="alert-message">Yüklemiş Olduğunuz Excel Dosyasında Toplam <b class="totalCount"></b> Öğrenci Kaydı Bulundu.
                                    </span>
                                </div>
                                <div class="alert alert-success successCount mb-3">
                                    <b></b> Adet Öğrenci Başarıyla Kaydedilmiştir.
                                </div>
                                <div class="alert alert-danger errorCount mb-3">
                                    <b></b> Adet Öğrencinin Kaydı Gerçekleştirilememiştir.
                                </div>
                                <table class="table table-striped table-hover table-bordered" id="confirmedTable">
                                    <thead>
                                        <tr>
                                            <th>Kayıt Durumu</th>
                                            <th>Öğrenci Adı</th>
                                            <th>Öğrenci Mail Adresi</th>
                                            <th>Öğrenci Kimlik No</th>
                                            <th>Öğrenci Telefon No</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" onclick="window.location.reload()">Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($students as $student)
                    <div class="modal fade" id="parentDetails-{{$student->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">{{$student->name}} Adlı Öğrencinin Veli Bilgileri</h3>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="save-parent-details" action="{{route('organizationAdmin.student.saveParents',$student->id)}}">
                                    <div class="modal-body" id="parentDetailContainer-{{$student->id}}">
                                        @php($i = 1)
                                        @php($parents = $student->getParentDetails())
                                        @foreach($parents as $parent)
                                            <label class="form-label">{{$i}}. Veli</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name="parents[{{$i}}][name]" required class="form-control" value="{{$parent->name}}" placeholder="Adı Soyadı">
                                                <input type="email" name="parents[{{$i}}][email]" required class="form-control" value="{{$parent->email}}" placeholder="Mail Adresi">
                                                <input type="tel" name="parents[{{$i}}][phone]" required class="form-control" value="{{$parent->phone}}" placeholder="Telefon Numarası">
                                            </div>
                                            @php($i++)
                                        @endforeach
                                        @if(count($parents) == 0)
                                            <div class="alert alert-warning">
                                                Veli Bilgisi Bulunmamaktadır Lütfen Ekleyiniz.
                                            </div>
                                        @endif
                                        <template id="parentDetailTemplate-{{$student->id}}" data-count="{{$i}}">
                                            <label class="form-label">{count}. Veli</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name="parents[{count}][name]" required class="form-control" placeholder="Adı Soyadı">
                                                <input type="email" name="parents[{count}][email]" required class="form-control" placeholder="Mail Adresi">
                                                <input type="tel" name="parents[{count}][phone]" required class="form-control" placeholder="Telefon Numarası">
                                            </div>
                                        </template>
                                    </div>
                                    <div class="modal-footer">
                                        <button
                                            type="button"
                                            class="btn btn-warning new-parent-btn"
                                            data-count="{{$i}}"
                                            data-template-selector="#parentDetailTemplate-{{$student->id}}"
                                            data-container-selector="#parentDetailContainer-{{$student->id}}"
                                        >Yeni Veli Ekle</button>
                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Kapat</button>
                                        <button type="submit" class="btn btn-primary">Kaydet</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
