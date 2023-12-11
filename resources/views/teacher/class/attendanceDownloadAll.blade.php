<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Yoklama Listesi - {{getDayName($day->day)}} - {{\Carbon\Carbon::now()->format('d.m.Y H:i')}}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif !important; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
<h1>Yoklama Listesi</h1>
<p>
    {{getDayName($day->day)}} Gününe Ait Yoklama Verileri
</p>
<p>{{\Carbon\Carbon::now()->format('d.m.Y H:i')}}</p>
@if(count($classAttendances) > 0)
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Öğrenci Adı</th>
            <th>Durum</th>
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
                <td>@if($attendance->attendance_status)
                        <span style="color:green">Katıldı</span>
                    @else
                        <span style="color:red">Katılmadı</span>
                    @endif
                </td>
                <td>{{$attendance->hour()->lesson()->name}}</td>
                <td>{{$attendance->created_at->format('d.m.Y H:i')}}</td>
            </tr>
            @php($count++)
        @endforeach
        </tbody>
    </table>
    @else
    <p style="color:#bbb700">Yoklama verisi bulunamadı.</p>
@endif
</body>
</html>
