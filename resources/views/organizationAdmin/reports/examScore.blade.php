@php
    $student = $exam->student();
    $results = $exam->results();
    $average = [];
    $allExams = \App\Models\ExamResults::whereIn('exam_id',\App\Models\Exams::where('student_id',$student->id)->pluck('id'))->get();
    foreach($allExams as $result){
       $lessonId = $result->lesson_id;

if (!isset($average[$lessonId])) {
    $average[$lessonId] = [
        'net' => 0,
        'sayi' => 0,
    ];
}

$average[$lessonId]["net"] += $result->correct_answers - ($result->wrong_answers / 4);
$average[$lessonId]["sayi"] += 1;
    }
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>{{$student->name}} {{$exam->created_at->format('d.m.Y H:i')}} Sınav Sonucu</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif !important;
        font-size: 16px;
        }
        .student-info-table{
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1em;
        }
        .student-info-table th, .student-info-table td{
            padding: 8px;
            border: 1px solid #ccc;
        }
        .student-info-table th{
            background-color: #ffd47b;
            font-weight: bold;
        }
        .student-info-table td.title{
            background: rgba(255, 212, 123, 0.3);
            font-weight: bold;

        }
        .bold{
            font-weight: bold;
        }
        .student-info-table td.title-name{
            width:13%;
        }
        .student-info-table td.value-name{
            width:35%;
        }
        .student-info-table td.title-booklet{
            width:13%;
        }
        .student-info-table td.value-booklet{
            width:13%;
        }
        .student-info-table td.logo-td{
            row-span: 3;
            width:26%;
        }
        .student-info-table td.logo-td img{
            max-width: 90%;
            max-height: 90%;
            width: auto;
            height: auto;
        }
        .lessons-score-info-table{
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1em;
        }
        .lessons-score-info-table th, .lessons-score-info-table td{
            padding: 8px;
            border: 1px solid #ccc;
        }
        .lessons-score-info-table th,.lessons-score-info-table tfoot tr{
            background-color: #ffd47b;
            font-weight: bold;
        }
        .lessons-score-info-table td.lesson-title{
            width:52%;
        }
        .lessons-score-info-table td.value{
            width:8%;
            text-align: center;
        }
        .lessons-score-info-table tbody tr:nth-child(odd){
            background-color: #f2f2f2;
        }
        .lessons-score-info-table tbody tr:nth-child(even){
            background-color: #fff;
        }
        .lessons-score-info-table tbody tr.end{
            background-color: rgba(255, 212, 123, 0.3);
        }
        .chart {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: flex-end;
            width: 100%;
            padding: 10px;
            overflow: visible;
        }
        .chart-item{
            width:30px;
            height: 100%;
            position: relative;
            flex-shrink: 0;
            text-align: center;
            margin-right: 10px;
            background: #eee;
            border-radius: 10px;
        }
        .chart-item .bar{
            height: 90%;
            width: 20px;
            border-radius: 10px;
            background-color: rgba(255, 212, 123, 0.3);
            display: flex;
            align-items: flex-end;
        }
        .chart-item .active-bar {
            width: 20px;
            border-radius: 10px;
            background-color: #ffd47b;
            margin-right: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .chart-item .active-bar span{
            color: black;
            font-size: 15px;
            font-weight: 600;
            transform: rotate(90deg);
            line-height: 20px;
            white-space: nowrap;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chart-item .exam-name{
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #000;
            padding: 5px 5px;
            border-radius: 10px;
        }
        .page_break { page-break-before: always; }
    </style>
</head>
<body>
<table class="student-info-table">
    <thead>
    <tr>
        <th colspan="4">Sınav Bilgileri</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="title title-name">Ad Soyad</td>
        <td class="value-name">{{$student->name}}</td>
        <td class="title title-booklet">Sınav Tarihi</td>
        <td class="value-booklet">{{$exam->created_at->format('d.m.Y')}}</td>
    </tr>
    <tr>
        <td class="title">Sınav Adı</td>
        <td>@if($batch =$exam->batchName()){{$batch}}@else{{"Bireysel Sınav"}}@endif</td>
        <td class="title">Sınıf</td>
        <td>{{$student->getClassName()}}</td>
    </tr>
    </tbody>
</table>
<table class="lessons-score-info-table">
    <thead>
    <tr>
        <th colspan="7">Sonuç Listesi</th>
    </tr>
    </thead>
    <tbody>
    <tr class="bold">
        <td class="lesson-title">Ders</td>
        <td class="value">Soru</td>
        <td class="value">Doğru</td>
        <td class="value">Yanlış</td>
        <td class="value">Boş</td>
        <td class="value">Net</td>
        <td class="value">Ortalama</td>
    </tr>
    @foreach($results as $result)
        <tr>
            <td>{{$result->lesson()->name}}</td>
            <td>{{$result->correct_answers + $result->wrong_answers + $result->empty_answers}} S</td>
            <td class="text-success">{{$result->correct_answers}} D</td>
            <td class="text-danger">{{$result->wrong_answers}} Y</td>
            <td class="text-info">{{$result->empty_answers}} Y</td>
            <td>{{$result->correct_answers - ($result->wrong_answers /4)}} Net</td>
            <td>{{round($average[$result->lesson_id]["net"] / $average[$result->lesson_id]["sayi"],2)}} Net</td>
        </tr>
    @endforeach
    </tbody>
</table>
@php($examScores = [])
@foreach($student->lastExams(7) as $sxc)
    @php($examScores[] = $sxc->score())
@endforeach
@php($lessons = [])
@foreach($examScores as $examScore)
    @foreach($examScore->lessons as $lessonI => $l)
        @if(!isset($lessons[$lessonI]))
            @php($lessons[$lessonI] = count($lessons) - 1)
        @endif
    @endforeach
@endforeach
{{--<div class="chart" style="height: 250px; width: 100%; overflow-x: hidden;">--}}
{{--    @foreach($examScores as $examScore)--}}
{{--        <div class="chart-item">--}}
{{--            <div class="bar">--}}
{{--                <div class="active-bar" style="height: {{$examScore->height}}%;">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="exam-name">{{$examScore->name}}</div>--}}
{{--        </div>--}}
{{--    @endforeach--}}
{{--</div>--}}

@php($analizler = $exam->topicAnalysis())
@if($analizler)
    <div style="width: 100%;font-size:10px !important;">
        @php($index = 0)
        @foreach($analizler as $analiz)
            <div style="width: 25%; float: left;">
                <table class="lessons-score-info-table">
                    <thead>
                    <tr>
                        <th colspan="4">{{$analiz->name}}</th>
                    </tr>
                    <tr>
                        <th>Konu</th>
                        <th>D</th>
                        <th>Y</th>
                        <th>B</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($analiz->topics as $topic)
                        <tr>
                            <td>{{$topic->name}}</td>
                            <td>{{$topic->correct}}</td>
                            <td>{{$topic->wrong}}</td>
                            <td>{{$topic->empty}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>Toplam</td>
                        <td>{{$analiz->total->correct}}</td>
                        <td>{{$analiz->total->wrong}}</td>
                        <td>{{$analiz->total->empty}}</td>
                    </tfoot>
                </table>
            </div>
            @if(($index + 1) % 4 === 0)
                <div style="clear: both;"></div>
            @endif
            @php($index++)
        @endforeach
        @if(count(json_decode(json_encode($analizler),true)) % 4 !== 0)
            <div style="clear: both;"></div>
        @endif
    </div>
@endif
<table class="lessons-score-info-table">
    <thead>

    <tr>
        <th width="5%">No</th>
        <th width="10%">Sınav Tarihi</th>
        <th width="45%">Sınav Adı</th>
        @foreach($lessons as $lesson => $l)
            <th width="8%">{{\App\Models\Lesson::find($lesson)->name}}</th>
        @endforeach
        <th width="8%">Toplam</th>
    </tr>
    </thead>
    <tbody>
    @php($i = 1)
    @foreach($examScores as $score)
        <tr>
            <td>{{$i}}</td>
            <td>{{$score->date}}</td>
            <td>{{$score->name}}</td>
            @foreach($lessons as $lesson => $l)
                @if(isset($score->lessons[$lesson]))
                    <td>{{$score->lessons[$lesson]}} N</td>
                @else
                    <td></td>
                @endif
            @endforeach
            <td>{{$score->total}} N</td>
        </tr>
        @php($i++)
    @endforeach
    </tbody>
</table>
</body>
</html>
