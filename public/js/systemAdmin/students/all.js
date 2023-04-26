class EditableRows {
    constructor() {
        if (!jQuery().DataTable) {
            console.log("DataTable is null!");
            return;
        }

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
    }

    // Creating datatable instance
    _createInstance() {
        const _this = this;
        this._datatable = jQuery("#datatableRows").DataTable({
            scrollX: true,
            buttons: ["copy", "excel", "csv", "print", "pdf"],
            info: true,
            order: [], // Clearing default order
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>', // Hiding all other dom elements except table and pagination
            pageLength: 5,
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
        });
        _this._setInlineHeight();
    }

    // Extending with DatatableExtend to get search, select and export working
    _extend() {
        this._datatableExtend = new DatatableExtend({
            datatable: this._datatable,
        });
    }

    // Setting static height to datatable to prevent pagination movement when list is not full
    _setInlineHeight() {
        return;
    }
}
