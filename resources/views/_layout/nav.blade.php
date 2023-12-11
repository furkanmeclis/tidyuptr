<div class="nav-content d-flex">
    <!-- Logo Start -->
    <div class="logo position-relative h3 font-weight-bold font-heading">
            TİDYUPTR
    </div>
    <!-- Logo End -->


    <!-- User Menu Start -->
    <div class="user-container d-flex">
        <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true"
           aria-expanded="false">
            <img class="profile" alt="profile" src="{{getAvatarUrl()}}" />
            <div class="name">{{getActiveUser()->name}}</div>
        </a>
        <div class="dropdown-menu dropdown-menu-end user-menu wide">

            <div class="row mb-1 ms-0 me-0">

                <div class="col-6 pe-1 ps-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{getLogoutUrl()}}">
                                <i data-acorn-icon="logout" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">Çıkış Yap</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- User Menu End -->

    <!-- Icons Menu Start -->
    <ul class="list-unstyled list-inline text-center menu-icons">
        <li class="list-inline-item">
            <a href="#" id="colorButton">
                <i data-acorn-icon="light-on" class="light" data-acorn-size="18"></i>
                <i data-acorn-icon="light-off" class="dark" data-acorn-size="18"></i>
            </a>
        </li>
    </ul>
    <!-- Icons Menu End -->
    <!-- Menu Start -->
    <div class="menu-container flex-grow-1">
        <ul id="menu" class="menu">

            @if (auth('admin')->check())
                <li>
                    <a href="{{ route('systemAdmin.index') }}"
                       class="{{ request()->routeIs('systemAdmin.index*') ? 'active' : '' }}">
                        <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                        <span class="label">Anasayfa</span>
                    </a>
                </li>
                <li>
                    <a href="#organizations_ul" data-href="{{ route('systemAdmin.organization.index') }}"
                       class="{{ request()->routeIs('systemAdmin.organization*') ? 'active' : '' }}">
                        <i data-acorn-icon="school" class="icon" data-acorn-size="18"></i>
                        <span class="label">Kurumlar</span>
                    </a>
                    <ul id="organizations_ul">
                        <li>
                            <a href="{{ route('systemAdmin.organization.index') }}"
                               class="{{ request()->routeIs('systemAdmin.organization.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('systemAdmin.organization.create') }}"
                               class="{{ request()->routeIs('systemAdmin.organization.create') ? 'active' : '' }}">
                                <span class="label">Yeni Kurum Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#teachers_ul" data-href="{{ route('systemAdmin.teacher.index') }}"
                       class="{{ request()->routeIs('systemAdmin.teacher*') ? 'active' : '' }}">
                        <i data-acorn-icon="lecture" class="icon" data-acorn-size="18"></i>
                        <span class="label">Öğretmenler</span>
                    </a>
                    <ul id="teachers_ul">
                        <li>
                            <a href="{{ route('systemAdmin.teacher.index') }}"
                               class="{{ request()->routeIs('systemAdmin.teacher.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('systemAdmin.teacher.create') }}"
                               class="{{ request()->routeIs('systemAdmin.teacher.create') ? 'active' : '' }}">
                                <span class="label">Yeni Öğretmen Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#lessons_ul" data-href="{{ route('systemAdmin.lesson.index') }}"
                       class="{{ request()->routeIs('systemAdmin.lesson*') ? 'active' : '' }}">
                        <i data-acorn-icon="books" class="icon" data-acorn-size="18"></i>
                        <span class="label">Dersler</span>
                    </a>
                    <ul id="lessons_ul">
                        <li>
                            <a href="{{ route('systemAdmin.lesson.index') }}"
                               class="{{ request()->routeIs('systemAdmin.lesson.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('systemAdmin.lesson.create') }}"
                               class="{{ request()->routeIs('systemAdmin.lesson.create') ? 'active' : '' }}">
                                <span class="label">Yeni Ders Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#students_ul" data-href="{{ route('systemAdmin.student.index') }}"
                       class="{{ request()->routeIs('systemAdmin.student*') ? 'active' : '' }}">
                        <i data-acorn-icon="online-class" class="icon" data-acorn-size="18"></i>
                        <span class="label">Öğrenciler</span>
                    </a>
                    <ul id="students_ul">
                        <li>
                            <a href="{{ route('systemAdmin.student.index') }}"
                               class="{{ request()->routeIs('systemAdmin.student.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('systemAdmin.student.create') }}"
                               class="{{ request()->routeIs('systemAdmin.student.create') ? 'active' : '' }}">
                                <span class="label">Yeni Öğrenci Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#optics_ul" data-href="{{ route('systemAdmin.optic.index') }}"
                       class="{{ request()->routeIs('systemAdmin.optic*') ? 'active' : '' }}">
                        <i data-acorn-icon="archive" class="icon" data-acorn-size="18"></i>
                        <span class="label">Optik Şemaları</span>
                    </a>
                    <ul id="optics_ul">
                        <li>
                            <a href="{{ route('systemAdmin.optic.index') }}"
                               class="{{ request()->routeIs('systemAdmin.optic.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('systemAdmin.optic.create') }}"
                               class="{{ request()->routeIs('systemAdmin.optic.create') ? 'active' : '' }}">
                                <span class="label">Yeni Optik Şeması Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('systemAdmin.exam.index') }}"
                       class="{{ request()->routeIs('systemAdmin.exam*') ? 'active' : '' }}">
                        <i data-acorn-icon="quiz" class="icon" data-acorn-size="18"></i>
                        <span class="label">Sınavlar</span>
                    </a>

                </li>

            @elseif (auth('organization')->check())
                <li>
                    <a href="{{ route('organizationAdmin.index') }}"
                       class="{{ request()->routeIs('organizationAdmin.index*') ? 'active' : '' }}">
                        <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                        <span class="label">Anasayfa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('organizationAdmin.analyzes') }}"
                       class="{{ request()->routeIs('organizationAdmin.analyzes*') ? 'active' : '' }}">
                        <i data-acorn-icon="chart-4" class="icon" data-acorn-size="18"></i>
                        <span class="label">Analizler</span>
                    </a>
                </li>
                <li>
                    <a href="#teachers_ul" data-href="{{ route('organizationAdmin.teacher.index') }}"
                       class="{{ request()->routeIs('organizationAdmin.teacher*') ? 'active' : '' }}">
                        <i data-acorn-icon="lecture" class="icon" data-acorn-size="18"></i>
                        <span class="label">Öğretmenler</span>
                    </a>
                    <ul id="teachers_ul">
                        <li>
                            <a href="{{ route('organizationAdmin.teacher.index') }}"
                               class="{{ request()->routeIs('organizationAdmin.teacher.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organizationAdmin.teacher.create') }}"
                               class="{{ request()->routeIs('organizationAdmin.teacher.create') ? 'active' : '' }}">
                                <span class="label">Yeni Öğretmen Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#students_ul" data-href="{{ route('organizationAdmin.student.index') }}"
                       class="{{ request()->routeIs('organizationAdmin.student*') ? 'active' : '' }}">
                        <i data-acorn-icon="online-class" class="icon" data-acorn-size="18"></i>
                        <span class="label">Öğrenciler</span>
                    </a>
                    <ul id="students_ul">
                        <li>
                            <a href="{{ route('organizationAdmin.student.index') }}"
                               class="{{ request()->routeIs('organizationAdmin.student.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organizationAdmin.student.create') }}"
                               class="{{ request()->routeIs('organizationAdmin.student.create') ? 'active' : '' }}">
                                <span class="label">Yeni Öğrenci Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#classes_ul" data-href="{{ route('organizationAdmin.class.index') }}"
                       class="{{ request()->routeIs('organizationAdmin.class*') ? 'active' : '' }}">
                        <i data-acorn-icon="home-garage" class="icon" data-acorn-size="18"></i>
                        <span class="label">Sınıflar</span>
                    </a>
                    <ul id="classes_ul">
                        <li>
                            <a href="{{ route('organizationAdmin.class.index') }}"
                               class="{{ request()->routeIs('organizationAdmin.class.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organizationAdmin.class.create') }}"
                               class="{{ request()->routeIs('organizationAdmin.class.create') ? 'active' : '' }}">
                                <span class="label">Yeni Sınıf Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#optics_ul" data-href="{{ route('organizationAdmin.optic.index') }}"
                       class="{{ request()->routeIs('organizationAdmin.optic*') ? 'active' : '' }}">
                        <i data-acorn-icon="archive" class="icon" data-acorn-size="18"></i>
                        <span class="label">Optik Şemaları</span>
                    </a>
                    <ul id="optics_ul">
                        <li>
                            <a href="{{ route('organizationAdmin.optic.index') }}"
                               class="{{ request()->routeIs('organizationAdmin.optic.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organizationAdmin.optic.create') }}"
                               class="{{ request()->routeIs('organizationAdmin.optic.create') ? 'active' : '' }}">
                                <span class="label">Yeni Optik Şeması Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#schemes_ul" data-href="{{ route('organizationAdmin.examScheme.index') }}"
                       class="{{ request()->routeIs('organizationAdmin.examScheme*') ? 'active' : '' }}">
                        <i data-acorn-icon="file-data" class="icon" data-acorn-size="18"></i>
                        <span class="label">Şemalar</span>
                    </a>
                    <ul id="schemes_ul">
                        <li>
                            <a href="{{ route('organizationAdmin.examScheme.index') }}"
                               class="{{ request()->routeIs('organizationAdmin.examScheme.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organizationAdmin.examScheme.create') }}"
                               class="{{ request()->routeIs('organizationAdmin.examScheme.create') ? 'active' : '' }}">
                                <span class="label">Yeni Şema Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#exams_ul" data-href="{{ route('organizationAdmin.batchExam.index') }}"
                       class="{{ request()->routeIs('organizationAdmin.batchExam*') ? 'active' : '' }}">
                        <i data-acorn-icon="quiz" class="icon" data-acorn-size="18"></i>
                        <span class="label">Sınavlar</span>
                    </a>
                    <ul id="exams_ul">
                        <li>
                            <a href="{{ route('organizationAdmin.batchExam.index') }}"
                               class="{{ request()->routeIs('organizationAdmin.batchExam.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organizationAdmin.batchExam.create') }}"
                               class="{{ request()->routeIs('organizationAdmin.batchExam.create') ? 'active' : '' }}">
                                <span class="label">Yeni Sınav Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @elseif (auth('teacher')->check())
                <li>
                    <a href="{{ route('teacher.index') }}"
                       class="{{ request()->routeIs('teacher.index*') ? 'active' : '' }}">
                        <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                        <span class="label">Anasayfa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.organization.index') }}"
                       class="{{ request()->routeIs('teacher.organization*') ? 'active' : '' }}">
                        <i data-acorn-icon="school" class="icon" data-acorn-size="18"></i>
                        <span class="label">Kurumlarım</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.student.index') }}"
                       class="{{ request()->routeIs('teacher.student*') ? 'active' : '' }}">
                        <i data-acorn-icon="online-class" class="icon" data-acorn-size="18"></i>
                        <span class="label">Öğrencilerim</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.lessonRequest.index') }}"
                       class="{{ request()->routeIs('teacher.lessonRequest*') ? 'active' : '' }}">
                        <i data-acorn-icon="calendar" class="icon" data-acorn-size="18"></i>
                        <span class="label">Ders Talepleri</span>
                    </a>

                </li>
                <li>
                    <a href="{{ route('teacher.announcement.index') }}"
                       class="{{ request()->routeIs('teacher.announcement*') ? 'active' : '' }}">
                        <i data-acorn-icon="notification" class="icon" data-acorn-size="18"></i>
                        <span class="label">Duyurularım</span>
                    </a>

                </li>
                <li>
                    <a href="#classes_ul" data-href="{{ route('teacher.class.index') }}"
                       class="{{ request()->routeIs('teacher.class*') ? 'active' : '' }}">
                        <i data-acorn-icon="quiz" class="icon" data-acorn-size="18"></i>
                        <span class="label">Sınıflar</span>
                    </a>
                    <ul id="classes_ul">
                        <li>
                            <a href="{{ route('teacher.class.all') }}"
                               class="{{ request()->routeIs('teacher.class.all') ? 'active' : '' }}">
                                <span class="label">Tüm Sınıflar</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('teacher.class.index') }}"
                               class="{{ request()->routeIs('teacher.class.index') ? 'active' : '' }}">
                                <span class="label">Sınıfım</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('teacher.class.announcement.index') }}"
                               class="{{ request()->routeIs('teacher.class.announcement.index') ? 'active' : '' }}">
                                <span class="label">Sınıf Duyuruları</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('teacher.mentor.index') }}"
                       class="{{ request()->routeIs('teacher.mentor*') ? 'active' : '' }}">
                        <i data-acorn-icon="book" class="icon" data-acorn-size="18"></i>
                        <span class="label">Ajandam</span>
                    </a>
                </li>
                <li>
                    <a href="#students_ul" data-href="{{ route('teacher.assignment.index') }}"
                       class="{{ request()->routeIs('teacher.assignment*') ? 'active' : '' }}">
                        <i data-acorn-icon="quiz" class="icon" data-acorn-size="18"></i>
                        <span class="label">Ödevlendirmelerim</span>
                    </a>
                    <ul id="students_ul">
                        <li>
                            <a href="{{ route('teacher.assignment.index') }}"
                               class="{{ request()->routeIs('teacher.assignment.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('teacher.assignment.create') }}"
                               class="{{ request()->routeIs('teacher.assignment.create') ? 'active' : '' }}">
                                <span class="label">Yeni Ödev Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('teacher.questionAnswer.index') }}"
                       class="{{ request()->routeIs('teacher.questionAnswer*') ? 'active' : '' }}">
                        <i data-acorn-icon="question-circle" class="icon" data-acorn-size="18"></i>
                        <span class="label">Soru & Cevap</span>
                    </a>

                </li>
            @elseif (auth('student')->check())
                @php($haveTeacher = \App\Models\StudentTeacher::where('student_id',auth('student')->user()->id)->first())
                <li>
                    <a href="{{ route('student.index') }}"
                       class="{{ request()->routeIs('student.index*') ? 'active' : '' }}">
                        <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                        <span class="label">Anasayfa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.ai.index') }}"
                       class="{{ request()->routeIs('student.ai*') ? 'active' : '' }}">
                        <i data-acorn-icon="crown" class="icon" data-acorn-size="18"></i>
                        <span class="label">Yol Arkadaşım</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.schedule.index') }}"
                       class="{{ request()->routeIs('student.schedule*') ? 'active' : '' }}">
                        <i data-acorn-icon="calendar" class="icon" data-acorn-size="18"></i>
                        <span class="label">Ders Programım</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.analyzes') }}"
                       class="{{ request()->routeIs('student.analyzes') ? 'active' : '' }}">
                        <i data-acorn-icon="chart-4" class="icon" data-acorn-size="18"></i>
                        <span class="label">Analizim</span>
                    </a>
                </li>
                <li>
                    <a href="#class_ul" data-href="{{ route('student.class.schedule') }}"
                       class="{{ request()->routeIs('student.class*') ? 'active' : '' }}">
                        <i data-acorn-icon="home-garage" class="icon" data-acorn-size="18"></i>
                        <span class="label">Sınıfım</span>
                    </a>
                    <ul id="class_ul">
                        <li>
                            <a href="{{ route('student.class.schedule') }}"
                               class="{{ request()->routeIs('student.class.schedule') ? 'active' : '' }}">
                                <span class="label">Sınıf Ders Programı</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.class.announcements') }}"
                               class="{{ request()->routeIs('student.class.announcements') ? 'active' : '' }}">
                                <span class="label">Duyurular</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @if($haveTeacher)
                    <li>
                        <a href="{{ route('student.mentor.index') }}"
                           class="{{ request()->routeIs('student.mentor*') ? 'active' : '' }}">
                            <i data-acorn-icon="book" class="icon" data-acorn-size="18"></i>
                            <span class="label">Ajandam</span>
                        </a>
                    </li>
                @endif
                @if($haveTeacher)
                    <li>
                        <a href="{{ route('student.assignment.index') }}"
                           class="{{ request()->routeIs('student.assignment*') ? 'active' : '' }}">
                            <i data-acorn-icon="quiz" class="icon" data-acorn-size="18"></i>
                            <span class="label">Ödevlerim</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('student.announcement.index') }}"
                       class="{{ request()->routeIs('student.announcement*') ? 'active' : '' }}">
                        <i data-acorn-icon="notification" class="icon" data-acorn-size="18"></i>
                        <span class="label">Duyurular</span>
                    </a>
                </li><li>
                    <a href="{{ route('student.preferenceRobot.index') }}"
                       class="{{ request()->routeIs('student.preferenceRobot*') ? 'active' : '' }}">
                        <i  class="icon bi-calculator" data-acorn-size="18"></i>
                        <span class="label">Sıralama Hesapla</span>
                    </a>
                </li>
                <li>
                    <a href="#exams_ul" data-href="{{ route('student.exam.index') }}"
                       class="{{ request()->routeIs('student.exam*') ? 'active' : '' }}">
                        <i data-acorn-icon="quiz" class="icon" data-acorn-size="18"></i>
                        <span class="label">Sınavlar</span>
                    </a>
                    <ul id="exams_ul">
                        <li>
                            <a href="{{ route('student.exam.index') }}"
                               class="{{ request()->routeIs('student.exam.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.exam.create') }}"
                               class="{{ request()->routeIs('student.exam.create') ? 'active' : '' }}">
                                <span class="label">Yeni Sınav Ekle</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#questions_ul" data-href="{{ route('student.questionAnswer.index') }}"
                       class="{{ request()->routeIs('student.questionAnswer*') ? 'active' : '' }}">
                        <i data-acorn-icon="question-circle" class="icon" data-acorn-size="18"></i>
                        <span class="label">Soru & Cevap</span>
                    </a>
                    <ul id="questions_ul">
                        <li>
                            <a href="{{ route('student.questionAnswer.index') }}"
                               class="{{ request()->routeIs('student.questionAnswer.index') ? 'active' : '' }}">
                                <span class="label">Tümünü Görüntüle</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.questionAnswer.create') }}"
                               class="{{ request()->routeIs('student.questionAnswer.create') ? 'active' : '' }}">
                                <span class="label">Yeni Soru Sor</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.questionAnswer.downloadAll') }}">
                                <span class="label">Pdf İndir</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
    <!-- Menu End -->

    <!-- Mobile Buttons Start -->
    <div class="mobile-buttons-container">
        <!-- Scrollspy Mobile Button Start -->
        <a href="#" id="scrollSpyButton" class="spy-button" data-bs-toggle="dropdown">
            <i data-acorn-icon="menu-dropdown"></i>
        </a>
        <!-- Scrollspy Mobile Button End -->

        <!-- Scrollspy Mobile Dropdown Start -->
        <div class="dropdown-menu dropdown-menu-end" id="scrollSpyDropdown"></div>
        <!-- Scrollspy Mobile Dropdown End -->

        <!-- Menu Button Start -->
        <a href="#" id="mobileMenuButton" class="menu-button">
            <i data-acorn-icon="menu"></i>
        </a>
        <!-- Menu Button End -->
    </div>
    <!-- Mobile Buttons End -->
</div>
<div class="nav-shadow"></div>
