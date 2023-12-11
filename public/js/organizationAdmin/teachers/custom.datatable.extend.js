class CustomDataTable {
    get options() {
        return {
            datatable: null,
            singleSelectCallback: null,
            anySelectCallback: null,
            noneSelectCallback: null,
            multipleSelectCallback: null,
            lengthChangeCallback: null,
        };
    }

    constructor(options = {}) {
        this.settings = Object.assign(this.options, options);
        this.datatable = this.settings.datatable; // Datatable instance passed via settings
        if (this.datatable) {
            this.element = this.datatable.table().container();
        }
        this._init();
    }

    _init() {
        this._addListeners();
        this._addShortcuts();
    }

    _addListeners() {
        // Check all button change listener
        if (document.getElementById("datatableCheckAll")) {
            document
                .getElementById("datatableCheckAll")
                .addEventListener("change", this._onCheckAllChange.bind(this));
        }

        // Listener for top right check all
        if (document.getElementById("datatableCheckAllButton")) {
            document
                .getElementById("datatableCheckAllButton")
                .addEventListener(
                    "click",
                    this._onCheckAllButtonClick.bind(this)
                );
        }

        // Click listeners for rows to make them selected or show the edit modal
        if (this.element) {
            this.element
                .querySelectorAll("tbody")
                .forEach((el) =>
                    el.addEventListener("click", this._onRowClick.bind(this))
                );
        }

        // Search listeners
        document.querySelectorAll(".datatable-search").forEach((el) => {
            el.addEventListener("keyup", this._onSearchKeyup.bind(this));
        });
        document.querySelectorAll(".search-delete-icon").forEach((el) => {
            el.addEventListener("click", this._onSearchDelete.bind(this));
        });
    }

    _addShortcuts() {
        // Shortcust for ctrl+a and ctrl+d to select all and deselect all
        if (typeof Mousetrap !== "undefined") {
            Mousetrap.bind("mod+a", (event) => {
                event.preventDefault();
                if (!this.datatable.data().any()) {
                    // Only no data warning row available at this point
                    return;
                }
                this.checkAllRows();
                this.controlCheckAll();
            });
            Mousetrap.bind("mod+d", (event) => {
                event.preventDefault();
                if (!this.datatable.data().any()) {
                    // Only no data warning row available at this point
                    return;
                }
                this.unCheckAllRows();
                this.controlCheckAll();
            });
        }
    }

    _onRowClick(event) {
        event.preventDefault();
        if (!this.datatable.data().any()) {
            // Only no data warning row available at this point
            return;
        }
        const currentTarget = event.target.closest("tr");

        currentTarget.classList.toggle("selected");
        const checkbox = currentTarget.querySelector(".form-check input");
        checkbox.checked = !checkbox.checked;
        checkbox.dispatchEvent(new Event("change"));
        this.controlCheckAll();
    }

    _onCheckAllChange(event) {
        const isCheckedAll =
            document.getElementById("datatableCheckAll").checked;
        if (isCheckedAll) {
            this.checkAllRows();
        } else {
            this.unCheckAllRows();
        }
        this.controlCheckAll();
    }

    _onCheckAllButtonClick(event) {
        if (!this.datatable.data().any()) {
            // Only no data warning row available at this point
            return;
        }
        const target = event.target;
        const currentTarget = event.currentTarget;
        if (!target.classList.contains("form-check-input")) {
            currentTarget.querySelector("input").click(); // Firing click event on the checkbox via the button click
        }
    }

    controlCheckAll() {
        if (!document.getElementById("datatableCheckAll")) {
            return;
        }
        let anyChecked = false;
        let allChecked = true;
        this.element
            .querySelectorAll("tbody tr .form-check input")
            .forEach((el) => {
                if (el.checked) {
                    anyChecked = true;
                } else {
                    allChecked = false;
                }
            });
        if (this.datatable && !this.datatable.data().any()) {
            allChecked = false;
            anyChecked = false;
        }
        if (anyChecked) {
            document.getElementById("datatableCheckAll").indeterminate =
                anyChecked;
            this.settings.anySelectCallback &&
                this.settings.anySelectCallback();
        } else {
            document.getElementById("datatableCheckAll").indeterminate =
                anyChecked;
            document.getElementById("datatableCheckAll").checked = anyChecked;
            this.settings.noneSelectCallback &&
                this.settings.noneSelectCallback();
        }
        if (allChecked) {
            document.getElementById("datatableCheckAll").indeterminate = false;
            document.getElementById("datatableCheckAll").checked = allChecked;
        }

        if (
            this.element.querySelectorAll("tbody tr .form-check input:checked")
                .length === 1
        ) {
            this.settings.singleSelectCallback &&
                this.settings.singleSelectCallback();
        } else {
            this.settings.multipleSelectCallback &&
                this.settings.multipleSelectCallback();
        }
    }

    unCheckAllRows() {
        if (!this.element) {
            return;
        }
        this.element
            .querySelectorAll("tbody tr")
            .forEach((el) => el.classList.remove("selected"));
        this.element
            .querySelectorAll("tbody tr .form-check input")
            .forEach((el) => {
                el.checked = false;
            });
    }

    checkAllRows() {
        if (!this.element) {
            return;
        }
        this.element
            .querySelectorAll("tbody tr")
            .forEach((el) => el.classList.add("selected"));
        this.element
            .querySelectorAll("tbody tr .form-check input")
            .forEach((el) => {
                el.checked = true;
            });
    }

    getSelectedRows() {
        return this.datatable.rows(".selected");
    }

    _getDatatable(target) {
        const selector = target.getAttribute("data-datatable");
        return jQuery(selector).DataTable();
    }

    _onSearchKeyup(event) {
        this._getDatatable(event.currentTarget)
            .search(event.currentTarget.value)
            .draw();
        const searchIcon = event.currentTarget
            .closest("div")
            .querySelector(".search-magnifier-icon");
        const deleteIcon = event.currentTarget
            .closest("div")
            .querySelector(".search-delete-icon");
        if (event.currentTarget.value !== "") {
            deleteIcon.classList.remove("d-none");
            searchIcon.classList.add("d-none");
        } else {
            deleteIcon.classList.add("d-none");
            searchIcon.classList.remove("d-none");
        }
        this.controlCheckAll();
    }

    _onSearchDelete(event) {
        const container = event.currentTarget.closest("div");
        const searchIcon = container.querySelector(".search-magnifier-icon");
        const deleteIcon = container.querySelector(".search-delete-icon");
        container.querySelector("input").value = "";
        this._getDatatable(container.querySelector("input")).search("").draw();
        deleteIcon.classList.add("d-none");
        searchIcon.classList.remove("d-none");
        this.controlCheckAll();
    }
}
class EditableRows {
    constructor() {
        if (!jQuery().DataTable) {
            console.log("DataTable is null!");
            return;
        }

        // Selected single row which will be edited
        this._rowToEdit;

        // Datatable instance
        this._datatable;

        // Edit or add state of the modal
        this._currentState;

        // Controls and select helper
        this._datatableExtend;

        // Datatable single item height
        this._staticHeight = 62;

        this._createInstance();
        this._extend();

        document
            .getElementById("saveButton")
            .addEventListener("click", this._saveSettings);
    }

    // Creating datatable instance
    _createInstance() {
        const _this = this;
        this._datatable = jQuery("#datatableRows").DataTable({
            scrollX: true,
            buttons: ["copy", "excel", "csv", "print"],
            info: false,
            order: [], // Clearing default order
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>', // Hiding all other dom elements except table and pagination
            pageLength: 10,
            columns: [
                { data: "id", width: "10%" },
                { data: "name", width: "80%" },
                { data: "check", width: "10%" },
            ],
            language: {
                paginate: {
                    previous: '<i class="cs-chevron-left"></i>',
                    next: '<i class="cs-chevron-right"></i>',
                },
            },
            initComplete: function (settings, json) {
                _this._setInlineHeight();
            },
            drawCallback: function (settings) {
                _this._setInlineHeight();
            },
            columnDefs: [
                {
                    targets: 2,
                    render: function (data, type, row, meta) {
                        if (data === "selected") {
                            return '<div class="form-check float-end mt-1"><input type="checkbox" checked class="form-check-input"></div>';
                        } else {
                            return '<div class="form-check float-end mt-1"><input type="checkbox" class="form-check-input"></div>';
                        }
                    },
                },
            ],
        });
        _this._setInlineHeight();
    }

    // Extending with DatatableExtend to get search, select and export working
    _extend() {
        this._datatableExtend = new CustomDataTable({
            datatable: this._datatable,
        });
    }
    // Setting static height to datatable to prevent pagination movement when list is not full
    _setInlineHeight() {
        return;
    }
    _saveSettings = async (event) => {
        let selectedOrganizations = [];
        const selected = this._datatableExtend.getSelectedRows();
        await selected.every(function (rowIdx, tableLoop, rowLoop) {
            const data = this.data();
            selectedOrganizations.push(data.id);
        });
        $.ajax({
            url: document.getElementById("saveButton").getAttribute("action"),
            type: "PUT",
            data: { organizationsId: selectedOrganizations },
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
    }
}
