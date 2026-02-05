<!-- <style>

</style> -->
<div id="hypo-diary-modal" class="modal fade " role="dialog">
    <div class="modal-dialog  modal-lg">
        <form name="alert-form" id="alert-form" action="#">
            <div class="modal-content ">
                <div class="modal-header">
                    <div class="card-title">
                        <p>Hypo Diary</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid pt-2">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive pt-3">
                                            <table id="hypo_data_table" class="display">
                                                <thead>
                                                    <tr>
                                                        <th>Sl No.</th>
                                                        <th>Date</th>
                                                        <th>Test Name</th>
                                                        <th>Result Value</th>
                                                        <th>Level</th>

                                                    </tr>
                                                </thead>
                                                <tbody>


                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
</div>
</div>



<style>
.modal-xl {
    max-width: 90% !important;
}

.search-btn {
    padding: 0.375rem 0.75rem !important;
}
</style>

<script>
$(document).ready(function() {
    // var table = $('#hypo_data_table').DataTable();
});

function showhypomodalTable() {
    table.destroy();

    table = $('#hypo_data_table').DataTable({
        'ajax': {
            url: "{{ route('getHypoDiary') }}",
            type: 'POST',
        },
        "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "visit_date"
            },
            {
                "data": "TestName"
            },
            {
                "data": "ResultValue"
            },
            {
                "data":"hypo_level"
            }
        ]
    });

}



</script>
