$((text, reviver) => {
    function getPaperResize(ordinates, width, height) {
        var w = 297;
        var h = 421;
        var birimW = w / width;
        var birimH = h / height;
        return {
            x: ordinates.xs * birimW,
            y: ordinates.ys * birimH,
            width: ((ordinates.xe - ordinates.xs === 0) ? 1 : ordinates.xe - ordinates.xs) * birimW,
            height: ((ordinates.ye - ordinates.ys === 0) ? 1 : ordinates.ye - ordinates.ys) * birimH
        };
    }

    $('#uploadFmt').on('submit', function (e) {
        e.preventDefault();
        if($('#confirmArea').find('ul').children().length > 0){
            $('#uploadFmt').find('input[name="file"]').attr('disabled', false);
        }
        let data = new FormData(this);
        $.ajax({
            url: $('#uploadFmt').attr('action'),
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status) {
                    $('#uploadFmt').find('input[name="file"]').attr('disabled', true);
                    if($('#confirmArea').find('ul').children().length === 0){
                        $('#uploadFmt').attr('action',response.url);
                        $('#firstSubmit').attr('disabled', true);
                        let areas = response.data.areas;
                        let paper = response.data.paper;
                        let paperDiv = $('#paper');
                        let paperHtml = "";
                        Object.entries(areas).forEach(([key,area],index) => {
                            let shape = `<div class="shape" id="shape-id-${index}" style="`;
                            let ordinates = getPaperResize(area, paper.xl, paper.yl);
                            shape += '--left:' + ordinates.x + 'px;';
                            shape += '--top:' + ordinates.y + 'px;';
                            shape += '--width:' + ordinates.width + 'px;';
                            shape += '--height:' + ordinates.height + 'px;';
                            shape += '">'+(index+1)+'</div>';
                            paperHtml += shape;
                        });
                        paperDiv.html(paperHtml);
                        let listHtml = "";
                        Object.entries(areas).forEach(([key,area],index) => {
                            area.index = index;
                            let list = '<li class="list-group-item">';
                            list += '<span class="badge bg-danger ">'+(index+1)+'. Alan</span> &nbsp;';
                            list += area.name;
                            list += '</li>';
                            listHtml += list;
                        });
                        $('#confirmArea').find('ul').html(listHtml);
                        $('#confirmArea').show();
                        iziToast.success({
                            title: "Başarılı",
                            message: response.message
                        });

                    }else{
                        iziToast.success({
                            title: "Başarılı",
                            message: response.message,
                            onClosing: function () {
                                window.location.href = response.url;
                            }
                        });
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
    });


})

