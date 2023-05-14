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

            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            if(event.startStr === formattedDate) {
                let link = event.url;
                iziToast.warning({
                    title: "Uyarı",
                    message: "Öğrenciye Hatırlatma Maili Göndermek İstiyor Musunuz?",
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
                            "<button>Hatırlat</button>",
                            function (instance, toast) {
                                $.ajax({
                                    url: link,
                                    type: "POST",
                                    dataType: "json",
                                    data: {
                                        date: event.startStr
                                    },
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
            }else{
                iziToast.warning({
                    title: "Uyarı",
                    message: "Yalnızca Bugüne Ait Kayıtlar İçin Hatırlatma Maili Gönderebilirsiniz.",
                    position: "bottomRight",
                    timeout: 10000,
                });
            }
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

}
