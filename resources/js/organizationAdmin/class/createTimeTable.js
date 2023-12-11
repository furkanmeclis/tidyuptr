
class CreateTimeTable {
    constructor() {
        // Initialization of the page plugins
        if (typeof Wizard === "undefined") {
            console.log("Wizard is undefined!");
            return;
        }
        this._initWizard();
    }
    selectedDays = [];
    selectedHours = {};

    _initWizard() {
        if (document.getElementById("classWizard") !== null) {
            this.wizardValidation = new Wizard(
                document.getElementById("classWizard"),
                {
                    topNav: false,
                    handleButtonClicks: false,
                    onNextClick: this._validationNext.bind(this),
                    onPrevClick: this._validationPrev.bind(this),
                }
            );
        }
        if(document.getElementById("step2Form")){
            document.getElementById("step2Form").addEventListener('submit',this._step2Submit);
        }
    }
    _getDayName(index){
        let days = ["Pazartesi", "Salı", "Çarşamba", "Perşembe", "Cuma","Cumartesi","Pazar"];
        return days[index];
    }
    _validationNext() {
        let _this = this;
        let current = this.wizardValidation.getCurrentIndex();
        let randomStr = Math.random().toString(36).substring(7);
        if(current === 0){
            let form = document.getElementById('step0Form');
            if(this._step0Validation(form)){
                let formData = jQuery(form).serializeArray();
                let contentCard = document.getElementById('hourTemplateArea');
                formData.forEach((item) => {
                    if(item.name === 'day[]'){
                        _this.selectedDays.push(item.value);
                        let templateHtml = document.getElementById('hourTemplate').innerHTML;
                        templateHtml = templateHtml.replaceAll('{day_name}', _this._getDayName(item.value));
                        templateHtml = templateHtml.replaceAll('{day_index}', item.value);
                        contentCard.innerHTML += templateHtml;
                    }
                });
                this.wizardValidation.gotoNext();
            }
        }else if(current === 1){
            let form = document.getElementById('step1Form');
            let validateOptions = {
                rules: {},
                messages: {}
            }
            _this.selectedDays.forEach((item) => {
                validateOptions.rules['days['+item+']hours'] = {
                    required: true,
                }
                validateOptions.messages['days['+item+']hours'] = {
                    required: "Ders Sayısı Girmelisiniz.",
                }
            });
            if(this._step1Validation(form,validateOptions)){
                let inputs = form.querySelectorAll('input[data-step-2="true"]');
                let data = {};

                inputs.forEach((item) => {
                    let dayIndex = item.getAttribute('data-day-index');
                    let name = item.getAttribute('data-name');
                    let value = item.value;

                    if (!data[dayIndex]) {
                        data[dayIndex] = {};
                    }

                    data[dayIndex][name] = value;
                });
                _this.selectedHours = data;
                let contentCard = document.getElementById('lessonSelectTemplateArea');
                contentCard.innerHTML = "";
                Object.entries(data).forEach(([key,value]) => {
                    let templateHtml = document.getElementById('lessonAccordion').innerHTML;
                    templateHtml = templateHtml.replaceAll('{day_name}', _this._getDayName(key));
                    templateHtml = templateHtml.replaceAll('{day_index}', key);
                    let hour_lesson_template = "";
                    for(let i = 1; i <= value.hours; i++){
                        let cacheTemplate = document.getElementById('teacherAndLessonTemplate').innerHTML;
                        cacheTemplate = cacheTemplate.replaceAll('{day_index}', key);
                        cacheTemplate = cacheTemplate.replaceAll('{hour_index}', i);
                        hour_lesson_template += cacheTemplate;
                    }
                    templateHtml = templateHtml.replaceAll('{hour_lesson_template}', hour_lesson_template);
                    contentCard.innerHTML += templateHtml;
                });
                jQuery('.select2').select2().on('select2:select',_this._recessListener);
                this.wizardValidation.gotoNext();
            }
        }
    }
    _recessListener(event){
        let selected = event.params.data;
        if(selected.id === "recess"){
            let option = selected.element;
            $('#'+option.getAttribute('data-teacher')).find('select').removeAttr('required');
            $('#'+option.getAttribute('data-recess')).find('select').attr('required',true);
            $('#'+option.getAttribute('data-teacher')).hide();
            $('#'+option.getAttribute('data-recess')).show();
        }else{
            let option = selected.element;
            $('#'+option.getAttribute('data-recess')).find('select').removeAttr('required');
            $('#'+option.getAttribute('data-teacher')).find('select').attr('required',true);
            $('#'+option.getAttribute('data-teacher')).show();
            $('#'+option.getAttribute('data-recess')).hide();
        }
    }
    _step2Submit(event){
        let form = event.target;
        let validateOptions2 = {
            errorElement: "div",
        };
        jQuery(form).validate(validateOptions2);
        event.preventDefault();
        event.stopPropagation();
        if (jQuery(form).valid()) {

                const formValues = $(form).serializeArray();
                $.ajax({
                    url: form.getAttribute("action"),
                    type: "POST",
                    data: formValues,
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
                                message: "Bir Hata Oluştu: " + error,
                            });
                        }
                    },
                });
                return;
            }
    }
    _validationPrev() {
        this.wizardValidation.gotoPrev();
    }

    _checkValidation(form) {
        if (jQuery().validate) {
            if (jQuery(form).valid()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    _step0Validation(form){
        let validateOptions = {
            rules: {
                'day[]':{
                    required: true,
                }
            },
            messages: {
                'day[]':{
                    required: "En az bir günü seçmelisiniz.",
                }
            }
        }
        jQuery(form).validate(validateOptions);
        if(jQuery(form).valid()){
            return true;
        }else{
            return false;
        }
    }
    _step1Validation(form,validateOptions){
        jQuery(form).validate(validateOptions);
        if(jQuery(form).valid()){
            return true;
        }else{
            return false;
        }
    }
}

$(() => {
    new CreateTimeTable();
});
