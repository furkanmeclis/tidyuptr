<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>
        @isset($batch)
            {{$batch->name}} İsimli Toplu Sınav Sonuçları
        @endisset
        @isset($class)
            {{$class->name}} İsimli Toplu Sınav Sonuçları
        @endisset
        @isset($student)
            {{$student->name}} İsimli Öğrencinin Sınav Sonuçları
        @endisset
    </title>
    <style>
        body { font-family: DejaVu Sans, sans-serif !important; }
    </style>
    <style>
        body { font-family: DejaVu Sans, sans-serif !important;
            font-size: 16px;
        }
        .text-success {
            color: #28a745 !important;
        }
        .text-danger {
            color: #dc3545 !important;
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
@if(isset($batch) || isset($class) || isset($student))
        <h1>Sınav Sonuçları</h1>
        <h4>Sınav Tarihi {{$batch->created_at->format('d.m.Y H:i')}}</h4>
        <table class="student-info-table">
            <thead>
            <tr>
                @if(!isset($student))<th>Öğrenci Adı</th>@endif
                <th>Doğru Sayısı</th>
                <th>Yanlış Sayısı</th>
                <th>Net</th>
            </tr>
            </thead>
            <tbody>
            @foreach($examArray as $exam)
                <tr>
                    @if(!isset($student))<td>{{$exam->student}}</td>@endif
                    <td class="text-success">{{$exam->correct_answers}} D</td>
                    <td class="text-danger">{{$exam->wrong_answers}} Y</td>
                    <td class="{{$exam->higher ? "text-success":"text-danger"}}">{{$exam->total}} Net {{$exam->higher ? "↑":"↓"}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    <div class="page_break"></div>
@endif
@foreach($examArray as $exam)
    @component("components.examResultPdf",["exam" => \App\Models\Exams::find($exam->id),"topicHistory"=>true,"examsHistory" => true])
    @endcomponent
@endforeach
</body>
</html>
