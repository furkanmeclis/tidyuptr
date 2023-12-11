<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <style>
        body { font-family: DejaVu Sans, sans-serif !important; }
    </style>
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .results-table th,
        .results-table td {
            padding: 10px;
            text-align: left;
        }

        .results-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }

        .results-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .results-table td{
            vertical-align: middle;
        }
        .text-success {
            color: #28a745 !important;
        }
        .text-danger {
            color: #dc3545 !important;
        }
        .page_break { page-break-before: always; }
    </style>

    <link rel="stylesheet" href="{{base_path()}}/css/vendor/bootstrap.min.css" />
</head>
<body>
    <div class="container">
        <h1>{{$class->name}} Sınıfının Detayları</h1>
        <h3>Öğrenci Listesi</h3>
        <table class="results-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Öğrenci Adı</th>
                    <th>Öğrenci Mail Adresi</th>
                    <th>Öğrenci Telefon Numarası</th>
                </tr>
            </thead>
            <tbody>
            @foreach($students as $key => $student)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$student->name}}</td>
                    <td>{{$student->email}}</td>
                    <td>{{$student->phone}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="page_break"></div>
    <div class="container">
        <h3>Ortalama Netler</h3>

        @if(count($lessons) > 0)
        <table class="results-table">
            <thead>
            <tr>
                <th class="text-center">Ders Adı</th>
                <th class="text-center">Doğru</th>
                <th class="text-center">Yanlış</th>
                <th class="text-center">Net</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lessons as $lesson)
                <tr>
                    <td class="text-center">{{$lesson->lesson->name}}</td>
                    <td class="text-success text-center">{{$lesson->correct_answers}} D</td>
                    <td class="text-danger text-center">{{$lesson->wrong_answers}} Y</td>
                    <td class="font-weight-bold text-center">{{$lesson->total}} N</td>
                </tr>
            @endforeach
        </table>
        @else
            <p>Ortalama net bulunmamaktadır.</p>
        @endif
    </div>
    <div class="page_break"></div>
    <div class="container">
        <h3>Son Bir Haftada Derse Katılmayan Öğrenciler</h3>

        @if(count($classAttendances) > 0)
        <table class="results-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Öğrenci Adı</th>
                <th>Ders Adı</th>
                <th>Tarih</th>
            </tr>
            </thead>
            <tbody>
            @php($count = 1)
            @foreach ($classAttendances as $attendance)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$attendance->student()->name}}</td>
                    <td>{{$attendance->hour()->lesson()->name}}</td>
                    <td>{{$attendance->created_at->format('d.m.Y H:i')}}</td>
                </tr>
                @php($count++)
            @endforeach
            </tbody>
        </table>
        @else
            <p>Son bir haftada derse katılmayan öğrenci bulunmamaktadır.</p>
        @endif
    </div>
    <div class="page_break"></div>
    <div class="container">
        <h3>Sınıf Ders Programı</h3>
        @if($timetable)
            <div style="margin-bottom: 20px;">
                @foreach($timetable->days()->get() as $day)
                    @php($is_class = !($timetable->class_id == null))
                    <div style="margin-bottom: 10px;">
                        <p style="color: #007bff; font-size: 16px; font-weight: bold; margin-bottom: 10px;">
                            @if($is_class)
                                {{getDayName($day->day)}}
                            @endif
                        </p>
                        <div style="margin-bottom: 10px;">
                            @php($time = \Carbon\Carbon::createFromFormat('H:i:s', $day->start_time))
                            @php($hours = $day->hours()->get())
                            @foreach($hours as $key => $hour)
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
                                <div style="padding: 5px; border: 1px solid #dee2e6; margin-bottom: 10px; background-color: {{ $is_recess ? '#f8f9fa' : 'transparent' }}">
                                    @if($is_recess)
                                        <p style="color: #6c757d; font-size: 12px; margin: 0;">#{{$key+1}} Ara ({{$recess}} Dakika) - {{$start}} - {{$end}}</p>
                                    @else
                                        @php($lesson = $day_hour->lesson())
                                        @php($teacher = $day_hour->teacher())
                                        <p style=" margin: 0;">#{{$key+1}} {{$lesson->name}} @if($is_class)- {{$teacher->name}}@endif - {{$start}} - {{$end}}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>Ders programı bulunamadı.</p>
        @endif

    </div>
</body>
</html>
