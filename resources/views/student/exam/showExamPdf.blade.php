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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$student->name}} {{$exam->created_at->format('d.m.Y H:i')}} Sınav Sonucu</title>
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

        .results-table td:first-child {
            font-weight: bold;
        }

        .results-table td:last-child {
            font-weight: bold;
            color: #007bff;
        }
        .text-success {
            color: #28a745 !important;
        }
        .text-danger {
            color: #dc3545 !important;
        }
    </style>
</head>
<body>
    @if(count($results) > 0)
        <div class="container">
            <h1>Sınav Sonuçları</h1>
            <!-- Öğrenci Bilgileri -->
            <h4>{{$student->name}} {{$exam->created_at->format('d.m.Y H:i')}}</h4>
            <table class="results-table">
                <thead>
                <tr>
                    <th>Ders</th>
                    <th>Doğru Sayısı</th>
                    <th>Yanlış Sayısı</th>
                    <th>Net</th>
                    <th>Ortalama</th>
                </tr>
                </thead>
                <tbody>
                @foreach($results as $result)
                    <tr>
                        <td>{{$result->lesson()->name}}</td>
                        <td class="text-success">{{$result->correct_answers}} D</td>
                        <td class="text-danger">{{$result->wrong_answers}} Y</td>
                        <td>{{$result->correct_answers - ($result->wrong_answers /4)}} Net</td>
                        <td>{{round($average[$result->lesson_id]["net"] / $average[$result->lesson_id]["sayi"],2)}} Net</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</body>
</html>
