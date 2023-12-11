<div class="row">
    <div class="col-md-6">
        <div class="card border-light">
            <div class="card-header">
                Derse Katılan Öğrenciler
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
                Derse Katılmayan Öğrenciler
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
