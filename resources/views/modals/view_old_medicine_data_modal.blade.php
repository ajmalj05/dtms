<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
Old Data

<div id="view-old-data-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="vaccination-form" id="vaccination-form" action="#" >
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title">
                        <p>{{$title}}</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid pt-2">
                        <div class="row">
                            <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table id="old_medicine_data" class="table table-bordered table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th>Sl No</th>
                                                        <th>Tablet Type</th>
                                                        <th>Tablet Name</th>
                                                        <th>Qty</th>
                                                        <th>Dose</th>
                                                        <th>Remarks</th>
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

    .modal-lg {
        max-width: 80% !important;
    }
    .search-btn{
        padding:0.375rem 0.75rem!important;
    }
</style>



