<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3 !important;
    }

    #searchpatient_MDT tr:hover td {
        background-color: transparent !important;
        /* or #000 */
    }
</style>
<div id="newsletterpicture-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="frm" id="frm" action="#">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title">
                        <p>Images</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid pt-2">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card card-sm ">
                                    <div class="card-header">
                                        <h4 class="card-title">News Letter Image Listing</h4>
                                    </div>
                                    <div class="card-body">
                                        <table id="picture_data" class="display table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th> Sl No</th>
                                                    <th>Image</th>
                                                    <!-- <th>Action</th> -->

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
    </form>
</div>
</div>



<style>
    .modal-lg {
        max-width: 80% !important;
    }

    .search-btn {
        padding: 0.375rem 0.75rem !important;
    }
</style>