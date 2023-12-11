$(() => {
    let deleteButons = document.querySelectorAll(".delete-exam-btn");
    if (deleteButons && deleteButons.length > 0) {
        deleteButons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                iziToast.error({
                    title: "Sınavı Silmek İstediğinize Emin misiniz?",
                    message: "",
                    overlay: true,
                    position: "bottomRight",
                    timeout: 10000,
                    buttons: [
                        [
                            "<button>Vazgeç</button>",
                            function (instance, toast) {
                                instance.hide(
                                    {
                                        transitionOut: "fadeOutUp",
                                    },
                                    toast,
                                    "buttonName"
                                );
                            },
                        ],
                        [
                            "<button>Sınavı Sil</button>",
                            function (instance, toast) {
                                $.ajax({
                                    url: link,
                                    type: "DELETE",
                                    dataType: "json",
                                    success: function (response) {
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOutUp",
                                            },
                                            toast,
                                            "buttonName"
                                        );

                                        if (response.status) {
                                            iziToast.success({
                                                title: "Başarılı",
                                                message: response.message,
                                                onClosing: () => {
                                                    window.location.reload();
                                                },
                                            });
                                        } else {
                                            iziToast.error({
                                                title: "Hata",
                                                message: response.message,
                                            });
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        instance.hide(
                                            {
                                                transitionOut: "fadeOutUp",
                                            },
                                            toast,
                                            "buttonName"
                                        );
                                        if (xhr.status == 419) {
                                            iziToast.error({
                                                title: "Hata",
                                                message:
                                                    "CSRF Doğrulama Hatası Lütfen Sayfayı Yenileyin.",
                                            });
                                        } else {
                                            iziToast.error({
                                                title: "Hata",
                                                message:
                                                    "Bir Hata Oluştu: " + error,
                                            });
                                        }
                                    },
                                });
                            },
                            true,
                        ],
                    ],
                });
            });
        });
    }
    let showExamButtons = document.querySelectorAll(".show-exam-btn");

    if(showExamButtons && showExamButtons.length > 0){
        showExamButtons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    url: link,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {
                        $('#examResultArea').html(response.html);
                        $('#xlExample').modal('show');
                    },
                    error: function (xhr, status, error) {

                    },
                });
            });
        });
    }
    let showAnalysisButtons = document.querySelectorAll(".show-exam-analysis-btn");
    if(showAnalysisButtons && showAnalysisButtons.length > 0){
        showAnalysisButtons.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    url: link,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {
                        $('#examAnalysisResultArea').html(response.html);
                        $('#examAnalysis').modal('show');
                    },
                    error: function (xhr, status, error) {

                    },
                });
            });
        });
    }
    let uploadExamUploadBtn = document.querySelectorAll(".batch-exam-upload-btn");

    if(uploadExamUploadBtn && uploadExamUploadBtn.length > 0){
        uploadExamUploadBtn.forEach((button) => {
            button.addEventListener("click", (e) => {
                let link = button.getAttribute("href");
                let action = button.getAttribute("data-form-action");
                e.preventDefault();
                e.stopPropagation();
                $('#uploadExam').find('#exampleSchemeLink').attr('href', link);
                $('#uploadExam').find('form').attr('action', action);
                $('#uploadExam').modal('show');
            });
        });
        $('#uploadExam').find('form').on('submit', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let form = $(this);
            let formData = new FormData(form[0]);
            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status) {
                        let lessons = response.data.lessons;
                        let thead = '<thead><tr><th colspan="2"></th>';
                        lessons.forEach((lesson) => {
                            thead += '<th class="text-center" colspan="3">'+lesson.name+'</th>';
                        });
                        thead+= '</tr><tr><th>#</th><th>Öğrenci No</th>';
                        lessons.forEach((lesson) => {
                            thead += '<th class="text-center">Doğru</th><th class="text-center">Yanlış</th><th class="text-center">Net</th>';
                        });
                        thead+= '</tr></thead>';
                        let tbody = '<tbody>';
                        response.data.data.forEach((row,index) =>{
                            tbody += '<tr><td class="text-left">'+(index+1)+'</td><td>'+row.student_name+'</td>';
                            row.lessons.forEach((lesson) => {
                                tbody += '<td class="text-center text-success">'+lesson.correct_answers+' D</td><td class="text-center text-danger">'+lesson.wrong_answers+' Y</td><td class="text-center text-info">'+(lesson.correct_answers - (lesson.wrong_answers / 4))+' N</td>';
                            });
                            tbody += '</tr>';
                        });
                        tbody += '</tbody>';
                        if(response.data?.unidentified?.length > 0){
                            tbody += '<tfoot><tr><td class="text-center text-medium" colspan="'+((lessons.length * 3) + 2)+'">Tanımlanamayan Öğrenciler</td></tr>';
                            response.data.unidentified.forEach((row,index) => {
                                tbody += '<tr><td class="text-left">'+(index+1)+'</td><td>'+row.student_name+'</td>';
                                row.lessons.forEach((lesson) => {
                                    tbody += '<td class="text-center text-success">'+lesson.correct_answers+' D</td><td class="text-center text-danger">'+lesson.wrong_answers+' Y</td><td class="text-center text-info">'+(lesson.correct_answers - (lesson.wrong_answers / 4))+' N</td>';
                                });
                                tbody += '</tr>';
                            });
                            tbody += '</tfoot>';
                        }
                        $('#confirmBtn').attr('href', response.storeUrl);
                        $('#confirmExam').find('table').html(thead+tbody);
                        $('#confirmExam').modal('show');
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
                            message:
                                "Bir Hata Oluştu: " + error,
                        });
                    }
                },
            });
        });
        $('#confirmBtn').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let link = $(this).attr('href');
            $.ajax({
                url: link,
                type: "POST",
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        iziToast.success({
                            title: "Başarılı",
                            message: response.message,
                            onClosing: () => {
                                window.location.href = response.url;
                            },
                        });
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
                            message:
                                "Bir Hata Oluştu: " + error,
                        });
                    }
                },
            });
        });
    }
    let uploadAnswerUploadForms = document.querySelectorAll(".upload-answers");
    if(uploadAnswerUploadForms && uploadAnswerUploadForms.length > 0){
        uploadAnswerUploadForms.forEach((form) => {
            form.addEventListener("submit", (e) => {
                e.preventDefault();
                e.stopPropagation();
                let formData = new FormData(form);
                $.ajax({
                    url: form.getAttribute('action'),
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status) {

                            if(response?.lessons){
                                let lessons = response.lessons;
                                const getLessonIndex = async (lesson_id) => {
                                    let index = 0;
                                    await lessons.forEach((lesson,i) => {
                                        if(lesson.id === lesson_id){
                                            index = i;
                                        }
                                    })
                                    return index;
                                }
                                let thead = '<thead><tr><th colspan="3"></th>';
                                lessons.forEach((lesson) => {
                                    thead += '<th class="text-center" colspan="3">'+lesson.name+'</th>';
                                });
                                thead+= '</tr><tr><th>Durum</th><th>Kimlik No</th><th>Öğrenci Adı</th>';
                                lessons.forEach((lesson) => {
                                    thead += '<th class="text-center">Doğru</th><th class="text-center">Yanlış</th><th class="text-center">Net</th>';
                                });
                                thead+= '</tr></thead>';
                                let tbody = '<tbody>';
                                response.importedExams.forEach((row,index) =>{
                                    tbody += '<tr><td  class="text-success">Kayıt Edildi</td><td>'+(row.identity_number)+'</td><td>'+row.name+'</td>';
                                    if(row.lessons.length > 0){
                                        row.lessons.forEach((lesson,i) => {
                                            if(lesson.lesson_id === 0){
                                                tbody += '<td></td><td></td><td></td>';
                                            }else{
                                                tbody += '<td class="text-center text-success">'+lesson.correct_answers+' D</td><td class="text-center text-danger">'+lesson.wrong_answers+' Y</td><td class="text-center text-info">'+(lesson.correct_answers - (lesson.wrong_answers / 4))+' N</td>';
                                            }
                                        });
                                    }
                                    tbody += '</tr>';
                                });
                                tbody += '</tbody>';
                                if(response?.unidentifiedExams?.length > 0){
                                    tbody += '<tfoot><tr><td class="text-center text-medium" colspan="'+((lessons.length * 3) + 3)+'">Tanımlanamayan Öğrenciler</td></tr>';
                                    response.unidentifiedExams.forEach((row,index) => {
                                        tbody += '<tr><td  class="text-danger">Kayıt Edilmedi</td><td>'+(row.identity_number)+'</td><td>'+row.name+'</td>';
                                        Object.entries(row.lessons).forEach(([key,lesson]) => {
                                            tbody += '<td colspan="3">'+lesson+'</td>';
                                        });
                                        tbody += '</tr>';
                                    });
                                    tbody += '</tfoot>';
                                }
                                $('#confirmOptic').find('table').html(thead+tbody);
                                $('#confirmOptic').modal('show');
                                iziToast.success({
                                    title: "Başarılı",
                                    message: response.message,
                                });
                            }else{
                                iziToast.success({
                                    title: "Başarılı",
                                    message: response.message,
                                    onClosing: () => {
                                        window.location.reload();
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
                                message:
                                    "Bir Hata Oluştu: " + error,
                            });
                        }
                    },
                });
            });
        });
    }

});
