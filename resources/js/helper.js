$(() => {
    let chartTemplateHtml =`<div class="col-12 col-xl-6">
                            <section class="scroll-section">
                                <h2 class="small-title">{title}</h2>
                                <div class="card mb-5">
                                    <div class="card-body">
                                        <div class="sh-35">
                                            <canvas id="{id}"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>`;
    if(GET_STATS_URL !== undefined){
        $.ajax({
            url: GET_STATS_URL,
            type: 'POST',
            dataType: 'json',
            success: (response) => {
                if(response.status){
                    $('#descriptionForCharts').html(response.description);
                    let area = $('#chartArea');
                    response.data.forEach((item, index) => {
                        if(item.visible === true){
                            let chartTemplate = chartTemplateHtml.replace('{id}', item.id).replace('{title}', item.title);
                            area.append(chartTemplate);
                        }
                    });
                    $("#countes").html("Grafikler,")
                    Object.entries(response.count).forEach(([key,item], index) => {
                        $("#countes").html($("#countes").html()+"<br>"+item)
                    });

                    $("#countes").html($("#countes").html()+" verilerini içermektedir.")
                    response.data.forEach((item, index) => {
                        if(item.visible === true){
                            let chart = new Chart($('#' + item.id), {
                                type: 'line',
                                data: {
                                    labels: item.labels,
                                    datasets: [{
                                        label: item.title,
                                        data: item.data,
                                        backgroundColor: 'rgba(0, 255, 255, 0.2)', // Cyan renk
                                        borderColor: 'rgba(0, 255, 255, 1)', // Cyan renk
                                        borderWidth: 2,
                                        pointRadius: 4,
                                        pointBackgroundColor: 'rgba(0, 255, 255, 1)', // Cyan renk
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 2,
                                        pointHoverRadius: 6,
                                        pointHoverBackgroundColor: 'rgba(0, 255, 255, 1)', // Cyan renk
                                        pointHoverBorderColor: '#fff',
                                        pointHoverBorderWidth: 2,
                                        fill: true, // Altını doldur
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            grid: {
                                                display: true,
                                                color: 'rgba(0, 0, 0, 0.1)',
                                                lineWidth: 1
                                            },
                                            ticks: {
                                                fontColor: 'rgba(0, 0, 0, 0.7)',
                                                padding: 10,
                                                precision: 0
                                            }
                                        },
                                        x: {
                                            grid: {
                                                display: false
                                            },
                                            ticks: {
                                                fontColor: 'rgba(0, 0, 0, 0.7)',
                                                padding: 10
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                            titleFont: {
                                                size: 14,
                                                weight: 'bold'
                                            },
                                            bodyFont: {
                                                size: 12
                                            },
                                            caretSize: 5,
                                            cornerRadius: 4,
                                            displayColors: false
                                        },
                                        crosshair: {
                                            line: {
                                                color: 'rgb(0,0,0)', // Kırmızı renk
                                                width: 1,
                                                dashPattern: [5, 5]
                                            },
                                            sync: {
                                                enabled: false
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    });
                }else{

                }
            }
        });
    }
})
