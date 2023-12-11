@if($timetable)
    @php($role = auth('teacher')->check() ? 'teacher' : 'organizationAdmin')
    <div class="row g-2 mb-5">
        @foreach($timetable->days()->get() as $day)
            @php($is_class = !($timetable->class_id == null))
            <div class="col">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column align-items-lg-center text-center text-md-start text-lg-center">
                        <p class="text-primary heading mb-4 font-weight-bold" >
                        @if($is_class)
                            <div class="btn-group">
                                <div class="dropdown">
                                    <a class="dropdown-toggle mb-1" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{getDayName($day->day)}}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="">
                                        <a class="dropdown-item" target="_blank" href="{{route($role.'.class.attendanceDownloadAll',[
                                    'class' => $timetable->class_id,
                                    'day' => $day->id
                                ])}}">Yoklama Listesi Katılanlar</a>
                                        <a class="dropdown-item" target="_blank" href="{{route($role.'.class.attendanceDownload_1',[
                                    'class' => $timetable->class_id,
                                    'day' => $day->id
                                ])}}">Yoklama Listesi Katılmayanlar</a>
                                        <a class="dropdown-item" target="_blank" href="{{route($role.'.class.attendanceDownload_0',[
                                    'class' => $timetable->class_id,
                                    'day' => $day->id
                                ])}}">Yoklama Listesi Katılmayanlar</a>
                                    </div>
                                </div>
                            </div>
                            @else
                                {{getDayName($day->day)}}
                            @endif
                            </p>
                            <div
                                class="d-flex flex-column flex-md-row flex-lg-column align-items-center mb-n4 justify-content-md-between justify-content-center text-center text-md-start text-lg-center"
                            >
                                @php($time = \Carbon\Carbon::createFromFormat('H:i:s', $day->start_time))
                                @php($hours = $day->hours()->get())
                                @php($hoursCount = $hours->count())
                                @php($count = 1)
                                @foreach($hours as $hour)
                                    @php($start = $time->format('H:i'))
                                    @php($is_recess = $hour->is_recess)
                                    @if(!$is_recess)
                                        @php($time = $time->addMinutes($hour->duration))
                                    @else
                                        @php($time = $time->addMinutes($hour->recess))
                                    @endif
                                    @php($end = $time->format('H:i'))
                                    @if(!$is_recess)
                                        @php($time = $time->addMinutes($hour->recess))
                                    @endif
                                    @php($recess = $hour->recess)
                                    @php($day_hour = $hour->hour())
                                    <div class="{{$count == $hoursCount ? "mb-4" : "pb-2 border-bottom mb-2"}}"

                                         @if($is_class && !$is_recess)
                                             data-attendance-url="{{route($role.'.class.showAttendance', ['class' => $timetable->class_id, 'hour' => $day_hour->id])}}"
                                        @endif>
                                        @if($is_recess)
                                            <p class="mb-0 text-muted">Ara ({{$recess}} Dakika)</p>
                                        @else
                                            @php($lesson = $day_hour->lesson())
                                            @php($teacher = $day_hour->teacher())
                                            <p class="mb-0 font-weight-bold ">{{$lesson->name}}</p>
                                            @if($is_class)
                                                <p class="mb-0 text-muted">{{$teacher->name}}</p>
                                            @endif
                                        @endif
                                        <p class="mb-0 text-muted">{{$start}} - {{$end}}</p>
                                    </div>
                                    @php($count++)
                                @endforeach
                            </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-warning" role="alert">
        Ders programı bulunamadı.
    </div>
@endif
