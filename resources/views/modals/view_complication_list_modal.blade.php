<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="view-complication-modal" class="modal fade" role="dialog">
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
                                        <h4 class="card-title">Complication Listing</h4>
                                    </div>
                                    <div class="card-body">
                                        <table id="complication_lists" class="display table">
                                            <thead>
                                            <tr>

                                                <th>Complication Name</th>
                                                <th>Sub Complication Name</th>
                                                <th>Complication Year</th>
                                                <th>Examined Date</th>


                                            </tr>
                                            </thead>
                                            <tbody id="complication_data">

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



