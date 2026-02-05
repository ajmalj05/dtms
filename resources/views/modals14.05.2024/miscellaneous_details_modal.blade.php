<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }

    #nav-tabContent textarea.form-control {
        height: 90px !important;
    }
</style>

<div id="get-miscellaneous-list-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

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
                <button type="button" class="btn btn-sm btn-primary my-2 pull-right" onclick="viewMiscellaneousModel()">Question Sheet</button>

                <div class="main-content" id="htmlContent">

                    <div class="page-content">
                        <div class="container-fluid">

                            <div class="text-center p-3 ">
                                <section id="tabs">
                                    <div class="container-fluid">

                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div class="table-responsive">
                                                            <table id="get_miscellaneous_data" class="display" style="min-width: 845px">
                                                                <thead>
                                                                <tr>
                                                                    <th>Sl No.</th>
                                                                    <th>Type</th>
                                                                    <th>Date</th>
                                                                    <th>Actions</th>

                                                                </tr>
                                                                </thead>
                                                                <tbody id="search_filter">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            </div>

                                        </div>
                                </section>
                                <!-- Tabs content -->
                            </div>

                        </div>


                        <!-- End Page-content -->
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
</div>
<script src="{{asset('/js/jquery-redirect.js')}}"></script>
<script>



    $('#get_miscellaneous_data tbody').on('click', '.miscellaneous-print', function() {
        var data = $('#get_miscellaneous_data').DataTable().row($(this).parents('tr')).data();
        $.redirect('{{url("miscellaneous-print-data")}}', { _token: "{{ csrf_token() }}",  patient_id: data.patient_id, patient_miscellaneous_answer_sheet_id: data.id}, 'POST', '_blank');
    });
</script>
<style>
    .content-body .container{
        margin-top:0px!important;
    }
    .table  tbody td {
        padding:2px 9px;
    }
    .table  tr td{
        text-align:left;

    }
    .table tbody tr td{

        font-size: 13px;
    }

    .form-control {
        height: 30px;
    }
    .btnexpand{
        width: 100%;
        padding: 0.2rem 1.5rem!important;
        border-radius: 5px;
    }
</style>

