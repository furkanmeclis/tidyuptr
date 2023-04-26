class DatatableExtend {
    get options() {
        return {
            datatable: null,
            editRowCallback: null,
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

        // Export listeners
        document
            .querySelectorAll(".datatable-export .dropdown-item")
            .forEach((el) => {
                el.addEventListener("click", this._onExportClick.bind(this));
            });

        // Print listeners
        document.querySelectorAll(".datatable-print").forEach((el) => {
            el.addEventListener("click", this._onPrintClick.bind(this));
        });

        // Length listeners
        document
            .querySelectorAll(".datatable-length .dropdown-item")
            .forEach((el) => {
                el.addEventListener("click", this._onLengthClick.bind(this));
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
        if (event.target.tagName === "A") {
            // Title clicked. Showing the edit view.
            this.unCheckAllRows();
            this.settings.editRowCallback(this.datatable.row(currentTarget));
            return true;
        }
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
        this.unCheckAllRows();
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
        this.unCheckAllRows();
        this.controlCheckAll();
    }

    _onPrintClick(event) {
        event.preventDefault();
        try {
            this._getDatatable(event.currentTarget).buttons(3).trigger();
        } catch (error) {
            console.log("Trigger button is not found");
        }
    }

    _onExportClick(event) {
        event.preventDefault();
        const selector = event.currentTarget
            .closest(".datatable-export")
            .getAttribute("data-datatable");
        if (event.currentTarget.classList.contains("export-copy")) {
            try {
                this._getDatatable(
                    event.currentTarget.closest(".datatable-export")
                )
                    .buttons(0)
                    .trigger();
            } catch (error) {
                console.log("Trigger button is not found");
            }
        }
        if (event.currentTarget.classList.contains("export-excel")) {
            try {
                this._getDatatable(
                    event.currentTarget.closest(".datatable-export")
                )
                    .buttons(1)
                    .trigger();
            } catch (error) {
                console.log("Trigger button is not found");
            }
        }
        if (event.currentTarget.classList.contains("export-cvs")) {
            try {
                this._getDatatable(
                    event.currentTarget.closest(".datatable-export")
                )
                    .buttons(2)
                    .trigger();
            } catch (error) {
                console.log("Trigger button is not found");
            }
        }
    }

    _onLengthClick(event) {
        event.preventDefault();
        const length = parseInt(event.currentTarget.innerHTML);
        this._getDatatable(event.currentTarget.closest(".datatable-length"))
            .page.len(length)
            .draw();
        this.unCheckAllRows();
        this.controlCheckAll();
        this.settings.lengthChangeCallback &&
            this.settings.lengthChangeCallback();
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

        // Add or edit modal
        this._addEditModal;

        // Datatable single item height
        this._staticHeight = 62;

        this._createInstance();
        this._addListeners();
        this._extend();
        this._initBootstrapModal();
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
            language: {
                paginate: {
                    previous: '<i class="cs-chevron-left"></i>',
                    next: '<i class="cs-chevron-right"></i>',
                },
            },
            columns: [
                { data: "id" },
                { data: "name" },
                { data: "coefficient" },
                { data: "check" },
            ],
            columnDefs: [
                // Adding Name content as an anchor with a target #
                {
                    targets: 1,
                    render: function (data, type, row, meta) {
                        return (
                            '<a class="list-item-heading body" href="#">' +
                            data +
                            "</a>"
                        );
                    },
                },
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        return (
                            '<a class="list-item-heading body" href="#">' +
                            data +
                            "</a>"
                        );
                    },
                },

                {
                    targets: 3,
                    render: function (data, type, row, meta) {
                        return (
                            data +
                            '<div class="form-check float-end mt-1"><input type="checkbox" class="form-check-input"></div>'
                        );
                    },
                },
            ],
        });
    }

    _addListeners() {
        // Listener for confirm button on the edit/add modal
        document
            .getElementById("addEditConfirmButton")
            .addEventListener("click", this._addEditFromModalClick.bind(this));

        // Listener for add buttons
        document
            .querySelectorAll(".add-datatable")
            .forEach((el) =>
                el.addEventListener("click", this._onAddRowClick.bind(this))
            );

        // Listener for delete buttons
        document
            .querySelectorAll(".delete-datatable")
            .forEach((el) =>
                el.addEventListener("click", this._onDeleteClick.bind(this))
            );

        // Listener for edit button
        document
            .querySelectorAll(".edit-datatable")
            .forEach((el) =>
                el.addEventListener("click", this._onEditButtonClick.bind(this))
            );

        // Calling a function to update tags on click
        // Calling clear form when modal is closed
        document
            .getElementById("addEditModal")
            .addEventListener("hidden.bs.modal", this._clearModalForm);

        $(document).on("blur", 'input[name="coefficient"]', function (e) {
            let value = e.target.value;
            if (!value || value === "0") {
                return "0.00";
            }
            const floatValue = parseFloat(value.replace(",", ".")).toFixed(2);
            e.target.value = floatValue;
        });
    }

    // Extending with DatatableExtend to get search, select and export working
    _extend() {
        this._datatableExtend = new DatatableExtend({
            datatable: this._datatable,
            editRowCallback: this._onEditRowClick.bind(this),
            singleSelectCallback: this._onSingleSelect.bind(this),
            multipleSelectCallback: this._onMultipleSelect.bind(this),
            anySelectCallback: this._onAnySelect.bind(this),
            noneSelectCallback: this._onNoneSelect.bind(this),
        });
    }

    // Keeping a reference to add/edit modal
    _initBootstrapModal() {
        this._addEditModal = new bootstrap.Modal(
            document.getElementById("addEditModal")
        );
    }

    // Setting static height to datatable to prevent pagination movement when list is not full

    // Add or edit button inside the modal click
    _addEditFromModalClick(event) {
        if (this._currentState === "add") {
            this._addNewRowFromModal();
        } else {
            this._editRowFromModal();
        }
        this._addEditModal.hide();
    }

    // Top side edit icon click
    _onEditButtonClick(event) {
        if (event.currentTarget.classList.contains("disabled")) {
            return;
        }
        const selected = this._datatableExtend.getSelectedRows();
        this._onEditRowClick(this._datatable.row(selected[0][0]));
    }

    // Direct click from row title
    _onEditRowClick(rowToEdit) {
        this._rowToEdit = rowToEdit; // Passed from DatatableExtend via callback from settings
        this._showModal("edit", "Konuyu Düzenle", "Düzenle");
        this._setForm();
    }

    // Edit button inside th modal click
    _editRowFromModal() {
        const data = this._rowToEdit.data();
        const _this = this;
        const formData = this._getFormData();
        $.ajax({
            url: $("#title").data("edit-url").replace("randomTopicId", data.id),
            type: "PUT",
            data: formData,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    iziToast.success({
                        title: "Başarılı",
                        message: response.message,
                    });

                    _this._datatable
                        .row(_this._rowToEdit)
                        .data(response.editedData)
                        .draw();
                    _this._datatableExtend.unCheckAllRows();
                    _this._datatableExtend.controlCheckAll();
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

    // Add button inside th modal click
    _addNewRowFromModal() {
        const data = this._getFormData();
        const _this = this;
        $.ajax({
            url: $("#title").data("add-url"),
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    iziToast.success({
                        title: "Başarılı",
                        message: response.message,
                    });
                    _this._datatable.row.add(response.newData).draw();
                    _this._datatableExtend.unCheckAllRows();
                    _this._datatableExtend.controlCheckAll();
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

    // Delete icon click
    _onDeleteClick() {
        const selected = this._datatableExtend.getSelectedRows();
        let tmp = [];
        let error = false;
        selected.every(function (rowIdx, tableLoop, rowLoop) {
            tmp[data.id] = data.id;
            const data = this.data();
            const _this = this;
            $.ajax({
                url: $("#title")
                    .data("trash-url")
                    .replace("randomTopicId", data.id),
                type: "DELETE",
                dataType: "json",
                success: function (response) {
                    if (!response.status) error = response.message;
                },
                error: function (xhr, status, errorM) {
                    if (xhr.status == 419) {
                        error =
                            "CSRF Doğrulama Hatası Lütfen Sayfayı Yenileyin.";
                    } else {
                        error = errorM;
                    }
                },
            });
        });
        if (error) {
            iziToast.error({
                title: "Hata",
                message: error,
            });
        } else {
            selected.remove().draw();
            iziToast.success({
                title: "Başarılı",
                message:
                    "Seçili " +
                    (tmp.length > 1 ? "Konular" : "Konu") +
                    " Silindi",
            });
            this._datatableExtend.unCheckAllRows();
            this._datatableExtend.controlCheckAll();
        }
    }

    // + Add New or just + button from top side click
    _onAddRowClick() {
        this._showModal("add", "Yeni Konu Ekle", "Ekle");
    }

    // Showing modal for an objective, add or edit
    _showModal(objective, title, button) {
        this._addEditModal.show();
        this._currentState = objective;
        document.getElementById("modalTitle").innerHTML = title;
        document.getElementById("addEditConfirmButton").innerHTML = button;
    }

    // Filling the modal form data
    _setForm() {
        const data = this._rowToEdit.data();
        document.querySelector("#addEditModal input[name=name]").value =
            data.name;
        document.querySelector("#addEditModal input[name=coefficient]").value =
            data.coefficient;
    }

    // Getting form values from the fields to pass to datatable
    _getFormData() {
        const data = {};
        data.name = document.querySelector(
            "#addEditModal input[name=name]"
        ).value;
        data.coefficient = document.querySelector(
            "#addEditModal input[name=coefficient]"
        ).value;
        data.Check = "";
        return data;
    }

    // Clearing modal form
    _clearModalForm() {
        document.querySelector("#addEditModal form").reset();
    }

    // Update tag from top side dropdown
    _updateTag(tag) {
        const selected = this._datatableExtend.getSelectedRows();
        const _this = this;
        selected.every(function (rowIdx, tableLoop, rowLoop) {
            const data = this.data();
            data.Tag = tag;
            _this._datatable.row(this).data(data).draw();
        });
        this._datatableExtend.unCheckAllRows();
        this._datatableExtend.controlCheckAll();
    }

    // Single item select callback from DatatableExtend
    _onSingleSelect() {
        document
            .querySelectorAll(".edit-datatable")
            .forEach((el) => el.classList.remove("disabled"));
    }

    // Multiple item select callback from DatatableExtend
    _onMultipleSelect() {
        document
            .querySelectorAll(".edit-datatable")
            .forEach((el) => el.classList.add("disabled"));
    }

    // One or more item select callback from DatatableExtend
    _onAnySelect() {
        document
            .querySelectorAll(".delete-datatable")
            .forEach((el) => el.classList.remove("disabled"));
        document
            .querySelectorAll(".tag-datatable")
            .forEach((el) => el.classList.remove("disabled"));
    }

    // Deselect callback from DatatableExtend
    _onNoneSelect() {
        document
            .querySelectorAll(".delete-datatable")
            .forEach((el) => el.classList.add("disabled"));
        document
            .querySelectorAll(".tag-datatable")
            .forEach((el) => el.classList.add("disabled"));
    }
}
