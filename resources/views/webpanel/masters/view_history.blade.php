<style>
    table.dataTable
    {
        width: 100% !important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="content-body">
  <div class="container-fluid pt-2">



                            {{-- modal --}}
                            <div class="modal fade" id="queryD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Query Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">

                                     <div id="details_Q"></div>

                                  </div>
                                  <div class="modal-footer">
                                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button> --}}
                                  </div>
                                </div>
                              </div>
                            </div>
                              {{-- modal --}}

                            <div class="row">
                              <div class="col-md-12">


                                            <div class="card">
                                                <div class="card-body">

                                                    <div class="form-row">

                                                        <div class="form-group col-md-2">
                                                            <label> From Date<span class="required">*</span></label>
                                                            <input type="text" class="form-control" id="from_date" readonly placeholder="Select Date" value="<?= date('d-m-Y')?>" >

                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label> To Date<span class="required">*</span></label>
                                                            <input type="text" class="form-control" id="to_date" readonly placeholder="Select Date" value="<?=date('d-m-Y');?>" >

                                                        </div>

                                                        <div class="form-group col-md-2">
                                                            <label> UHID Number</label>
                                                            <input type="text" class="form-control" id="uhid_no" value="">
                                                        </div>

                                                        <div class="form-group col-md-2">
                                                            <label> Table Name</label>
                                                            <select class="form-control" id="tbl_name">
                                                                <option value="">Choose</option>
                                                                @foreach ($tables as $tableName)
                                                                <option value="{{ $tableName }}">{{ $tableName }}</option>
                                                              @endforeach
                                                            </select>
                                                        </div>




                                                        <div id="crud">
                                                        <div class="form-group col-md-2 align-items-center justify-content-sm-center">
                                                            <br>
                                                            <a id="search_btn" class="btn btn-primary inline-flex items-center px-4 py-2" style="color:white">Search</a>
                                                        </div>
                                                    </div>

                                                    </div>

                                                    <div class="form-row">




                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                </div>

       {{-- table start --}}
                                <div class="row">

                                  <div class="col-xl-12">
                                    <div class="card">
                                      <div class="card-body">
                                        <div class="table-responsive pt-3">
                                          <table id="department-table" class="display">
                                            <thead>
                                              <tr>
                                                <th>Sl No.</th>
                                                <th>Date</th>
                                                <th>Action by</th>
                                                <th>Description</th>
                                                <th>Table Name</th>
                                                {{-- <th>Qury_log</th> --}}
                                                <th>View Query</th>

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
                {{-- table End --}}


              </div>
  </div>


</div>
</div>

@include('frames/footer');


<script>


$(document).ready(function() {
    table = $('#department-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
             'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/masterData/getHistoryDetails",
            type: 'POST',
            data: function(d) {
              d._token= "{{ csrf_token() }}";
              d.from_date= $('#from_date').val();
              d.to_date= $('#to_date').val();
              d.uhid_no=$("#uhid_no").val();
              d.tbl_name=$("#tbl_name").val();
            }
        },
        "columns": [
            {
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            // {
            //     "data": "created_date",
            //     render: function(data, type, row) {
            //         if (type === 'display' || type === 'filter') {
            //             var parsedDate = new Date(data);
            //             var formattedDate = parsedDate.toLocaleString('en-GB', {
            //                 day: 'numeric',
            //                 month: 'numeric',
            //                 year: 'numeric',
            //                 hour: 'numeric',
            //                 minute: 'numeric',
            //                 hour12: true
            //             });
            //             return formattedDate;
            //         }
            //         return data;
            //     }
            // },
             {
                  "data": "created_date",
                  render: function (data, type, row) {
                      if (type === 'display' || type === 'filter') {
                          var parsedDate = new Date(data);
                          var hours = parsedDate.getHours();
                          var minutes = parsedDate.getMinutes();
                          var ampm = hours >= 12 ? 'PM' : 'AM';
                          hours = hours % 12;
                          hours = hours ? hours : 12; // Handle midnight (0 AM)

                          var formattedDate = parsedDate.toLocaleString('en-GB', {
                              day: 'numeric',
                              month: 'numeric',
                              year: 'numeric',
                          });
                          var formattedTime = hours + ':' + (minutes < 10 ? '0' : '') + minutes + ' ' + ampm;

                          return formattedDate + ' ' + formattedTime;
                      }
                      return data;
                  }
              },

            {
                "data": "name"
            },
            {
                "data": "description"
            },
            {
                "data": "table_name"
            },
            // {
            //   "data": "qury_log"
            // },
            {
               "data": "id",
               "render": function(display_status, type, full, meta) {
                return '<div class="d-flex"><a href="#" id="query_details" data-value="qury_log" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-eye"></i></a></div>'
               }
            },
        ]
    });
});

$('#search_btn').click(function(e){
  if($("#from_date").val() == '' && $("#to_date").val() == ''){
    toastr.warning('Date Fields Required');
  }
  table.ajax.reload();
});


</script>

<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>

<script>
  $(document).ready(function() {

      $('#from_date').datepicker({
          autoclose: true,
          // endDate: '+0d',
          format: 'dd-mm-yyyy'
      });
      $('#to_date').datepicker({
          autoclose: true,
          // endDate: '+0d',
          format: 'dd-mm-yyyy'
      });
  });
</script>

<script>
  $('#department-table').on('click', '#query_details', function(){
        var data = table.row($(this).parents('tr')).data();
        console.log(data);
        $('#queryD').modal();
        $('#details_Q').html(data.qury_log);

    });
</script>
