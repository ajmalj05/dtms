<div class="content-body">
    <div class="container-fluid pt-2">
        <form name="frm" id="frm" action="#">
            <div class="profile-tab pb-2">
                <div class="custom-tab-1">
                  <ul class="nav nav-tabs">
                    <li class="nav-item"><a href="#Bp_status" data-toggle="tab" class="nav-link active show">BP STATUS</a>
                    </li>
                    <li class="nav-item"><a href="#Smbg" data-toggle="tab" class="nav-link">SMBG</a>
                    </li>
                    </li>
                  </ul>
                </div>
              </div>
        </form>

        <div class="row">
            <div class="col-md-12">

                <div id="product-reference" class="tab-pane fade active show">

                    <div class="row">

                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive pt-3">
                                        <table id="smbgverification" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Gender</th>
                                                    <th>Uhid number</th>
                                                    <th>Phone number</th>
                                                    <th>Verify</th>
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
</div>

@include('frames/footer');
<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>
<script>

//------------------------------Data table-----------------//
$(document).ready(function() {
    table_1 = $('#bp_status').DataTable({
        'ajax': {
            url: "<?php echo url('/') ?>/appManagement/smbg",
            type: 'POST',
            // "data": function(d) {
            //     d.from_date = $('#from_date').val();
            //     d.to_date = $('#to_date').val();
            // }
        },
        columns[{

        }
        {
                "data": "name"
            },
            {
                "data": "name"
            },
    ]
    {
            "data": "id",
            "render": function(display_status, type, full, meta) {
                return '<button id="verify" class="btn btn-primary">Verify</button>';
            }
        },
    });
});






//------------------------SMBG---------------//
$('#smbgverification').on('click','#verify', function(){

});
