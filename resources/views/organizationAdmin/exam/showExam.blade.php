@if($exam)
    @php
        $student = $exam->student();
        $results = $exam->results();
        $wrongQuestions = $exam->getWrongQuestions();
    @endphp
    @if(count($results) > 0)
        <div>
            <h5 class="mb-3">{{$student->name}} - {{$exam->date()}}</h5>
            <table class="table table-primary table-bordered">
                <thead>
                <tr>
                    @foreach($results as $result)
                        <th class="text-center">{{$result->lesson()->name}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                <tr>
                    @foreach($results as $result)
                        <td class="p-0">
                            <table class="table m-0 table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">Doğru</th>
                                    <th class="text-center">Yanlış</th>
                                    <th class="text-center">Net</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="table-active">
                                    <td class="text-center text-success">{{$result->correct_answers}}</td>
                                    <td class="text-center text-danger"  >{{$result->wrong_answers}}</td>
                                    <td class="text-center font-weight-bold">{{$result->correct_answers - ($result->wrong_answers /4)}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach($results as $result)
                        <td class="p-0">
                            <table class="table m-0 table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">Başarı Yüzdesi(%)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="table-active">
                                    <td class="text-center">{{round(($result->correct_answers / ($result->correct_answers + $result->wrong_answers)) * 100,2)}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    @endforeach
                </tr>
                <tr>

                </tr>
                </tbody>
            </table>
            @if($wrongQuestions)
                <hr>
                <h5 class="mb-3">Yanlış Cevaplanan Sorular</h5>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">Soru Numarası</th>
                        <th class="text-center">Ders</th>
                        <th class="text-center">Konusu</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($wrongQuestions as $wrongQuestion)
                        <tr>
                            <td class="text-center">{{$wrongQuestion->question_number}}</td>
                            <td class="text-center">{{\App\Models\Lesson::find($wrongQuestion->lesson_id)->name}}</td>
                            <td class="text-center">{{$wrongQuestion->topic}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @else
        <div class="alert alert-warning">Öğrenciye Ait Sonuç Listesi Bulunamadı</div>
    @endif
@else
    <div class="alert alert-warning">Sınav Bulunamadı</div>
@endif
