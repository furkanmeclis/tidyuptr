<div class="nav-content d-flex">
    <!-- Logo Start -->
    <div class="logo position-relative">
        <a href="/">
            <!-- Logo can be added directly -->
            <!-- <img src="/img/logo/logo-white.svg" alt="logo" /> -->
            <!-- Or added via css to provide different ones for different color themes -->
            <div class="img"></div>
        </a>
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
            <div class="row mb-3 ms-0 me-0">
                <div class="col-12 ps-1 mb-2">
                    <div class="text-extra-small text-primary">ACCOUNT</div>
                </div>
                <div class="col-6 ps-1 pe-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">User Info</a>
                        </li>
                        <li>
                            <a href="#">Preferences</a>
                        </li>
                        <li>
                            <a href="#">Calendar</a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 pe-1 ps-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">Security</a>
                        </li>
                        <li>
                            <a href="#">Billing</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mb-1 ms-0 me-0">
                <div class="col-12 p-1 mb-2 pt-2">
                    <div class="text-extra-small text-primary">APPLICATION</div>
                </div>
                <div class="col-6 ps-1 pe-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">Themes</a>
                        </li>
                        <li>
                            <a href="#">Language</a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 pe-1 ps-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">Devices</a>
                        </li>
                        <li>
                            <a href="#">Storage</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mb-1 ms-0 me-0">
                <div class="col-12 p-1 mb-3 pt-3">
                    <div class="separator-light"></div>
                </div>
                <div class="col-6 ps-1 pe-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">
                                <i data-acorn-icon="help" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">Help</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i data-acorn-icon="file-text" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">Docs</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 pe-1 ps-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">
                                <i data-acorn-icon="gear" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">Settings</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i data-acorn-icon="logout" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">Logout</span>
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
            <a href="#" data-bs-toggle="modal" data-bs-target="#searchPagesModal">
                <i data-acorn-icon="search" data-acorn-size="18"></i>
            </a>
        </li>
        <li class="list-inline-item">
            <a href="#" id="colorButton">
                <i data-acorn-icon="light-on" class="light" data-acorn-size="18"></i>
                <i data-acorn-icon="light-off" class="dark" data-acorn-size="18"></i>
            </a>
        </li>
        <li class="list-inline-item">
            <a href="#" data-bs-toggle="dropdown" data-bs-target="#notifications" aria-haspopup="true"
                aria-expanded="false" class="notification-button">
                <div class="position-relative d-inline-flex">
                    <i data-acorn-icon="bell" data-acorn-size="18"></i>
                    <span class="position-absolute notification-dot rounded-xl"></span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end wide notification-dropdown scroll-out" id="notifications">
                <div class="scroll">
                    <ul class="list-unstyled border-last-none">
                        <li class="mb-3 pb-3 border-bottom border-separator-light d-flex">
                            <img src="/img/profile/profile-1.webp" class="me-3 sw-4 sh-4 rounded-xl align-self-center"
                                alt="..." />
                            <div class="align-self-center">
                                <a href="#">Joisse Kaycee just sent a new comment!</a>
                            </div>
                        </li>
                        <li class="mb-3 pb-3 border-bottom border-separator-light d-flex">
                            <img src="/img/profile/profile-2.webp" class="me-3 sw-4 sh-4 rounded-xl align-self-center"
                                alt="..." />
                            <div class="align-self-center">
                                <a href="#">New order received! It is total $147,20.</a>
                            </div>
                        </li>
                        <li class="mb-3 pb-3 border-bottom border-separator-light d-flex">
                            <img src="/img/profile/profile-3.webp" class="me-3 sw-4 sh-4 rounded-xl align-self-center"
                                alt="..." />
                            <div class="align-self-center">
                                <a href="#">3 items just added to wish list by a user!</a>
                            </div>
                        </li>
                        <li class="pb-3 pb-3 border-bottom border-separator-light d-flex">
                            <img src="/img/profile/profile-6.webp" class="me-3 sw-4 sh-4 rounded-xl align-self-center"
                                alt="..." />
                            <div class="align-self-center">
                                <a href="#">Kirby Peters just sent a new message!</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
    <!-- Icons Menu End -->
    <!-- Menu Start -->
    <div class="menu-container flex-grow-1">
        <ul id="menu" class="menu">

            @if (auth('admin')->check())
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
                    <a href="{{ route('systemAdmin.exam.index') }}"
                       class="{{ request()->routeIs('systemAdmin.exam*') ? 'active' : '' }}">
                        <i data-acorn-icon="quiz" class="icon" data-acorn-size="18"></i>
                        <span class="label">Sınavlar</span>
                    </a>

                </li>
            @elseif (auth('organization')->check())
                <li>
                    <a href="{{ route('organizationAdmin.teacher.index') }}"
                       class="{{ request()->routeIs('organizationAdmin.teacher*') ? 'active' : '' }}">
                        <i data-acorn-icon="lecture" class="icon" data-acorn-size="18"></i>
                        <span class="label">Öğretmenler</span>
                    </a>

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
                        <a href="{{ route('organizationAdmin.batchExam.index') }}"
                           class="{{ request()->routeIs('organizationAdmin.batchExam*') ? 'active' : '' }}">
                            <i data-acorn-icon="quiz" class="icon" data-acorn-size="18"></i>
                            <span class="label">Sınavlar</span>
                        </a>

                    </li>
            @elseif (auth('teacher')->check())

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
                    <a href="{{ route('teacher.announcement.index') }}"
                       class="{{ request()->routeIs('teacher.announcement*') ? 'active' : '' }}">
                        <i data-acorn-icon="notification" class="icon" data-acorn-size="18"></i>
                        <span class="label">Duyurularım</span>
                    </a>

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
                <li>
                    <a href="{{ route('student.ai.index') }}"
                       class="{{ request()->routeIs('student.ai*') ? 'active' : '' }}">
                        <i data-acorn-icon="quiz" class="icon" data-acorn-size="18"></i>
                        <span class="label">Sınavlar</span>
                    </a>

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
