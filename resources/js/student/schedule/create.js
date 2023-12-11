
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
    selectedHours = [];

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
                _this.selectedDays = [];
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
                validateOptions.rules['days['+item+'][hours]'] = {
                    required: true,
                }
                validateOptions.messages['days['+item+'][hours]'] = {
                    required: "Ders Sayısı Girmelisiniz.",
                }
            });
            if(this._step1Validation(form,validateOptions)){
                let data = jQuery(form).serializeArray();
                let count = 0;
                data.forEach((item) => {
                    if(item.name.includes('hours')){
                        count += parseInt(item.value);
                    }
                });
                this.totalCount = count;
                $('#totalHour').html(count);
                this.wizardValidation.gotoNext();
            }
        }
    }
    _step2Submit(event){

        event.preventDefault();
        event.stopPropagation();
        let totalHours = 0;
        let hourInputs = [];
        const hourInputs2 = document.querySelectorAll('input[data-lesson-hour-select="true"]:checked');
        hourInputs2.forEach(input => {
            hourInputs.push(document.querySelector('input[name="'+input.getAttribute('data-selector-name')+'"]'))
        });

        hourInputs.forEach(input => {
            const hours = parseFloat(input.value) || 0;
            totalHours += hours;
        });
        if (totalHours > parseInt($('#totalHour').html())) {
            iziToast.info({
                title: "Hata",
                message: "Belirlediğiniz Süre Aşıldı Tekrardan Deneyin.",
            });
            return
        }
        let form = event.target;
        let validateOptions2 = {
            errorElement: "div",
        };
        jQuery(form).validate(validateOptions2);
        if (jQuery(form).valid()) {

            const formValues = $(form).serializeArray();

            $.ajax({
                url: form.getAttribute("action"),
                type: "POST",
                data: [...formValues,...$("#step1Form").serializeArray()],
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
