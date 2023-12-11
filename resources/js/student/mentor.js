/**
 *
 * Calendar
 * A very basic and static implementation for the application mostly to show different layouts it has. Edit this class according to your project needs.
 * Implemented with the help of FullCalendar and with a static data from json/events.json file.
 *
 */

class Calendar {
    get options() {
        return {};
    }

    constructor(options = {}) {
        this.settings = Object.assign(this.options, options);
        this.calendar = null;
        this.eventStartTime = null;
        this.eventEndTime = null;
        this.currentEventId = null;
        this.events = CALENDAR_DATA;
        this._init();
        this._addListeners();

    }

    _init() {
        if (!document.getElementById('calendar') || !document.getElementById('calendarTitle') || typeof FullCalendar === 'undefined') {
            return;
        }
        this.calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            timeZone: 'local',
            locale:'tr',
            themeSystem: 'bootstrap',
            dayMaxEvents: true,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false,
                timeZone: 'Europe/Istanbul'
            },
            headerToolbar: {
                left: '',
                center: '',
                right: '',
            },
            viewDidMount: (args) => {
                this._updateTitle();
            },
            eventClick: this._eventClick.bind(this),
            events: this.events,
        });
        this.calendar.render();
    }

    _addListeners() {
        document.getElementById('goToday') &&
        document.getElementById('goToday').addEventListener('click', () => {
            this.calendar.today();
            this._updateTitle();
        });

        document.getElementById('goPrev') &&
        document.getElementById('goPrev').addEventListener('click', () => {
            this.calendar.prev();
            this._updateTitle();
        });

        document.getElementById('goNext') &&
        document.getElementById('goNext').addEventListener('click', () => {
            this.calendar.next();
            this._updateTitle();
        });

        document.getElementById('monthView') &&
        document.getElementById('monthView').addEventListener('click', () => {
            this.calendar.changeView('dayGridMonth');
            this._updateTitle();
        });

        document.getElementById('weekView') &&
        document.getElementById('weekView').addEventListener('click', () => {
            this.calendar.changeView('timeGridWeek');
            this._updateTitle();
        });

        document.getElementById('dayView') &&
        document.getElementById('dayView').addEventListener('click', () => {
            this.calendar.changeView('timeGridDay');
            this._updateTitle();
        });
        document.getElementById('openNewModal').addEventListener('click', () => {
            this._showAddModal();
        });
        document.getElementById('saveNewAgenta').addEventListener('click', () => {
           this._saveNewAgenta();
        });

    }

    // Updating title of the calendar, not event related
    _updateTitle() {
        document.getElementById('calendarTitle').innerHTML = this.calendar.view.title;
    }

    // Filling the event details modal for showing the event
    _eventClick(info) {
        const event = info.event;

        info.jsEvent.preventDefault();
        info.jsEvent.stopPropagation();
        if(event.extendedProps.send === false){

        }else{
            if (event.url != '') {
                $("#detailModal").find(".modal-title").html(event.title+" <b>"+event.start.toLocaleDateString()+"</b>");
                $('#detailModal').find('.modal-body img').attr('src', event.url);
                $("#detailModal").modal("show");
            }
        }

    }


    _showElement(selector) {
        document.getElementById(selector) && document.getElementById(selector).classList.add('d-inline-block');
        document.getElementById(selector) && document.getElementById(selector).classList.remove('d-none');
    }

    _hideElement(selector) {
        document.getElementById(selector) && document.getElementById(selector).classList.remove('d-inline-block');
        document.getElementById(selector) && document.getElementById(selector).classList.add('d-none');
    }
    _showAddModal() {
        $("#newModal").modal("show");
    }
    _saveNewAgenta() {
        let file = document.getElementById('fileAgenta').files[0];
        let formData = new FormData();
        formData.append('file', file);
        let url = $('#newModal').data('url');
        let button = document.getElementById('saveNewAgenta');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
              //loading
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Yükleniyor...';
            },
            success: function (response) {
                button.disabled = false;
                button.innerHTML = 'Kaydet';
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
                    document.getElementById('fileAgenta').value = '';
                }
            },
            error: function (xhr, status, error) {
                button.disabled = false;
                button.innerHTML = 'Kaydet';
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
