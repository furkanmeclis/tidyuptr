@if($exam)
    @php
        $analysies = $exam->topicAnalysis();
    @endphp
    @if($analysies != false)
        <div class="row">
            @foreach($analysies as $analysis)
                <div class="col-6 mb-3">
                    <div class="card border-light">
                        <div class="card-body">
                            <h4>{{$analysis->name}}</h4>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped ">
                                    <thead>
                                    <tr>
                                        <th>Konu</th>
                                        <th>Doğru</th>
                                        <th>Yanlış</th>
                                        <th>Boş</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($analysis->topics as $topic)
                                        <tr>
                                            <td>{{$topic->name}}</td>
                                            <td>{{$topic->correct}}</td>
                                            <td>{{$topic->wrong}}</td>
                                            <td>{{$topic->empty}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning">Sınav Konu Analiz Kaydı Bulunamadı</div>
    @endif
@else
    <div class="alert alert-warning">Sınav Sonucu Bulunamadı</div>
@endif
