<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif !important; }
        .container {
            display: flex;
            justify-content: space-between;
        }

        .row {
            flex: 1;
        }

        .col {
            flex: 1;
            padding: 10px;
            position: relative;
        }
        img {
            max-width: 200px;
            max-height: 280px;
        }
        .number{
            position: absolute;
            top: 0;
            left: 0;
            font-size: 20px;
            font-weight: bold;
            color: #fff;
            background-color: #000;
            padding: 5px;
        }
    </style>
</head>
<body>
<h3>Yapamadığım Sorular</h3>
    <div class="container">
            @php($key = 0)
            @foreach($quest as $q)
                    @if($key % 2 == 0)
                        <div class="row">
                            <div class="col">
                                <div class="number">{{$key}}</div>
                                <img src="{{\Illuminate\Support\Facades\Storage::url($q->file)}}" alt="">
                            </div>
                    @else
                            <div class="col">
                                <div class="number">{{$key}}</div>
                                <img src="{{\Illuminate\Support\Facades\Storage::url($q->file)}}" alt="">
                            </div>
                        </div>
                    @endif
                @php($key++)
            @endforeach
    </div>
</body>
</html>
