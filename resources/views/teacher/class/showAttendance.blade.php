@if(count($attendances_1) == 0 && count($attendances_0) == 0)
    @if($hour->teacher_id == auth('teacher')->user()->id)
        <form action="{{route('teacher.class.initAttendance',["class" => $classId,"hour"=>$hour->id])}}" id="classAttendanceInit">
            <div class="scroll-out">
                <div class="scroll-by-count mb-n2 os-host os-theme-dark os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition" data-count="4" style="height: 439.875px; margin-bottom: -8px;">
                    <div class="os-padding">
                        <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                            <div class="os-content" style="height: 100%; width: 100%;">
                                @foreach($students as $student)
                                    <div class="card border-light mb-2">
                                        <div class="card-body p-3">
                                            <label class="form-check custom-icon mb-0 checked-opacity-75">
                                                <input type="checkbox" name="student_id[]" value="{{$student->id}}" class="form-check-input">
                                                <span class="form-check-label">
                                                <span class="content">
                                                    <span class="heading mb-0">{{$student->name}}</span>
                                                </span>
                                            </span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
             </div>
        </form>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="card border-light">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="display-1 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-warning-hexagon d-inline-block text-warning"><path d="M9 2.57735C9.6188 2.22008 10.3812 2.22008 11 2.57735L15.9282 5.42265C16.547 5.77992 16.9282 6.44017 16.9282 7.1547V12.8453C16.9282 13.5598 16.547 14.2201 15.9282 14.5774L11 17.4226C10.3812 17.7799 9.6188 17.7799 9 17.4226L4.0718 14.5774C3.45299 14.2201 3.0718 13.5598 3.0718 12.8453V7.1547C3.0718 6.44017 3.45299 5.77992 4.0718 5.42265L9 2.57735Z"></path><path d="M10 6V10.5M10 13V14"></path></svg></div>
                            <h1 class="h3 mb-3">Yoklama Verisi Girilmemiş</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="row">
        <div class="col-md-6">
            <div class="card border-light">
                <div class="card-header">
                    Derse Katılan Öğrenciler ({{count($attendances_1)}})
                </div>
                <div class="card-body mb-n2 scroll-out">
                    <div class="scroll-by-count os-host os-theme-dark os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition" data-count="4" data-childselector="a" data-subtractmargin="false" style="height: 320px;">
                        <div class="os-resize-observer-host observed">
                            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
                        </div>
                        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
                            <div class="os-resize-observer"></div>
                        </div>
                        <div class="os-content-glue" style="margin: 0px -15px; width: 844px; height: 319px;"></div>
                        <div class="os-padding">
                            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                                <div class="os-content" style="padding: 0px 15px; height: 100%; width: 100%;">
                                    @foreach($attendances_1 as $attendance)
                                        <a class="row g-0 sh-9 mb-2">
                                            <div class="col">
                                                <div class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-0 h-100 justify-content-center">
                                                    <div class="d-flex flex-column">
                                                        <div>{{$attendance->student()->name}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
                            <div class="os-scrollbar-track os-scrollbar-track-off">
                                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
                            </div>
                        </div>
                        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden" style="height: calc(100% - 8px);">
                            <div class="os-scrollbar-track os-scrollbar-track-off">
                                <div class="os-scrollbar-handle" style="height: 57.971%; transform: translate(0px, 0px);"></div>
                            </div>
                        </div>
                        <div class="os-scrollbar-corner"></div></div>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header">
                    Derse Katılmayan Öğrenciler ({{count($attendances_0)}})
                </div>
                <div class="card-body mb-n2 scroll-out">
                    <div class="scroll-by-count os-host os-theme-dark os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition" data-count="4" data-childselector="a" data-subtractmargin="false" style="height: 320px;">
                        <div class="os-resize-observer-host observed">
                            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
                        </div>
                        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
                            <div class="os-resize-observer"></div>
                        </div>
                        <div class="os-content-glue" style="margin: 0px -15px; width: 844px; height: 319px;"></div>
                        <div class="os-padding">
                            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                                <div class="os-content" style="padding: 0px 15px; height: 100%; width: 100%;">
                                    @foreach($attendances_0 as $attendance)
                                        <a class="row g-0 sh-9 mb-2">
                                            <div class="col">
                                                <div class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-0 h-100 justify-content-center">
                                                    <div class="d-flex flex-column">
                                                        <div>{{$attendance->student()->name}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
                            <div class="os-scrollbar-track os-scrollbar-track-off">
                                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
                            </div>
                        </div>
                        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden" style="height: calc(100% - 8px);">
                            <div class="os-scrollbar-track os-scrollbar-track-off">
                                <div class="os-scrollbar-handle" style="height: 57.971%; transform: translate(0px, 0px);"></div>
                            </div>
                        </div>
                        <div class="os-scrollbar-corner"></div></div>
                </div>
            </div>

        </div>
    </div>
@endif
