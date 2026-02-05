<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="view-diagnosis-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="complication-list" id="complication-list" action="#" >
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title">
                        <p>{{$title}}</p>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid pt-2">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card card-sm ">
                                    <div class="card-header">
                                        <h4 class="card-title">Diagnosis Listing</h4>
                                    </div>
                                    <div class="card-body">
                                        <table id="diagnosis_lists" class="display table">
                                            <thead>
                                            <tr>
                                                <th>Diagnosis Name</th>
                                                <th>Diagnosis Year</th>
                                                <th>Examined Date</th>


                                            </tr>
                                            </thead>
                                            <tbody id="diagnosis_data">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnclose" data-dismiss="modal">Close</button>
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



