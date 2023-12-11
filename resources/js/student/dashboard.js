class StudentDashboard {
    constructor() {
        this._largeLineChart1 = null;
        this._initSalesStocksCharts();
    }
    _initSalesStocksCharts() {
        //getData
        $.ajax({
            url: GetStatsUrl,
            type: "POST",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: async function(response) {
                if (response.status) {
                    if (response.normal.length > 0) {
                        if (document.getElementById('largeLineChart1')) {
                            let labels = [];
                            let data = [];
                            let average = 0;
                            await response.normal.forEach(function (item) {
                                labels.push(item.date);
                                data.push(item.total);
                                average += item.total;
                            });
                            average = average / response.normal.length;
                            this._largeLineChart1 = ChartsExtend.LargeLineChart('largeLineChart1', {
                                labels: labels,
                                datasets: [
                                    {
                                        label: 'Deneme Sonuçlarım (Bireysel)',
                                        data: data,
                                        icons: ['arrow-top', 'arrow-top', 'arrow-top', 'arrow-top', 'arrow-bottom'],
                                        borderColor: Globals.primary,
                                        pointBackgroundColor: Globals.primary,
                                        pointBorderColor: Globals.primary,
                                        pointHoverBackgroundColor: Globals.foreground,
                                        pointHoverBorderColor: Globals.primary,
                                        borderWidth: 2,
                                        pointRadius: 2,
                                        pointBorderWidth: 2,
                                        pointHoverBorderWidth: 2,
                                        pointHoverRadius: 5,
                                        fill: false,
                                        datalabels: {
                                            align: 'end',
                                            anchor: 'end',
                                        },
                                    },
                                ],
                            });
                            document.querySelector('.average-score-normal').innerHTML = average.toFixed(2);

                        }
                    }else{
                        document.querySelector('#normalExamScore').style.display = 'none';
                    }
                    if (response.batch.length > 0) {
                        if (document.getElementById('largeLineChart2')) {
                            let labels = [];
                            let data = [];
                            let average = 0;
                            await response.batch.forEach(function (item) {
                                labels.push(item.date);
                                data.push(item.total);
                                average += item.total;
                            });
                            average = average / response.batch.length;
                            this._largeLineChart2 = ChartsExtend.LargeLineChart('largeLineChart2', {
                                labels: labels,
                                datasets: [
                                    {
                                        label: 'Deneme Sonuçlarım (Toplu)',
                                        data: data,
                                        icons: ['arrow-top', 'arrow-top', 'arrow-bottom', 'arrow-bottom', 'arrow-top'],
                                        borderColor: Globals.secondary,
                                        pointBackgroundColor: Globals.secondary,
                                        pointBorderColor: Globals.secondary,
                                        pointHoverBackgroundColor: Globals.foreground,
                                        pointHoverBorderColor: Globals.secondary,
                                        borderWidth: 2,
                                        pointRadius: 2,
                                        pointBorderWidth: 2,
                                        pointHoverBorderWidth: 2,
                                        pointHoverRadius: 5,
                                        fill: false,
                                        datalabels: {
                                            align: 'end',
                                            anchor: 'end',
                                        },
                                    },
                                ],
                            });
                            document.querySelector('.average-score-batch').innerHTML = average.toFixed(2);

                        }
                    }else{
                        document.querySelector('#batchExamScore').style.display = 'none';
                    }
                    if(response.count === 0){
                        document.querySelector('#examScoreArea').style.display = 'none';
                    }
                } else {
                    iziToast.error({
                        title: "Hata",
                        message: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status == 419) {
                    iziToast.error({
                        title: "Hata",
                        message:
                            "CSRF Doğrulama Hatası Lütfen Sayfayı Yenileyin.",
                    });
                } else {
                    iziToast.error({
                        title: "Hata",
                        message: "Bir Hata Oluştu: " + error,
                    });
                }
            },
        });
    }

}
new StudentDashboard();
