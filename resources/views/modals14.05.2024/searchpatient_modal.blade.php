<style>
#searchpatient_MDT tr:hover {
    background-color: #d7dae3!important;
}
#searchpatient_MDT tr:hover td {
    background-color: transparent!important; /* or #000 */
}
</style>
<div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              {{$title}}
            </div>



            <form name="frm" id="frm" action="#" >


                      <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">



                          <section>
                                      <div class="row">

                                          <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">UHID No.</label>
                                                    <input type="text" class="form-control search_by" name="uhid" id="s_uhid" value="" >


                                            </div>
                                          </div>
                                          <div class="col-xl-3 col-md-6 mb-2">
                                              <div class="form-group">
                                                  <label class="text-label">Patient Name </label>
                                                  <input type="text" name="name" id="s_patient_name" class="form-control search_by"  placeholder="" >
                                                  <small id="name_error" class="form-text text-muted error"></small>
                                              </div>
                                          </div>


                                          <div class="col-xl-3 col-md-3 mb-2">
                                              <div class="form-group">
                                                  <label class="text-label">Mobile Number </label>


                                                  <input type="text" name="mobile_number" id="s_mobile_number"  value="" class="form-control search_by" placeholder="" >
                                                  </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3 mb-2">
                                              <div class="form-group">
                                                <label class="text-label" style="opacity:0">Search</label>
                                                <br>
                                                <button class="btn btn-primary search-btn" onclick="searchPatient(event)">Search</button>
                                              </div>
                                            </div>




                                      </div>

                          </section>
                      </div>


            </form>
            <div class="row" style="">
              <div class="col-md-12">
                  <div class="card">

                      <div class="card-body">
                          <div class="table-responsive">
                              <table id="searchpatient_MDT" class="display" style="min-width: 845px">
                                  <thead>
                                      <tr>
                                          <th>UHID No</th>
                                          <th>Name</th>
                                          <th>Age</th>
                                          <th>Sex </th>
                                          <th>Address </th>
                                          <th>Mobile No</th>
                                          <th>Admission Date</th>
                                          {{-- <th></th> --}}

                                      </tr>
                                  </thead>
                                  <tbody id="search_filter">



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



<style>
 .modal-lg {
    max-width: 80% !important;
}
.search-btn{
  padding:0.375rem 0.75rem!important;
}
</style>
<script>
//   $(document).ready(function() {

//     $('.search-btn').click(function(e){
//       e.preventDefault();
//         tablereload();
//     });



//   });

function searchPatient(e){
    e.preventDefault();
    tablereload();
}

  function tablereload(){
    table = $('#searchpatient_MDT').DataTable({
            // scrollY: 470,
            "order": [] ,
            "autoWidth": false,
            "dom": 'Blfrtip',
            "destroy" : true,

        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/searchPatient",
            type: 'POST',
            "data": function(d) {

                d._token= "{{ csrf_token() }}";
                d.uhid= $('#s_uhid').val();
                d.mobile_number= $('#s_mobile_number').val();
                d.patient_type= $('#patient_type').val();
                d.patient_name= $('#s_patient_name').val();
                d.last_name= $('#last_name').val();
                d.gender= $('#gender').val();
                d.age= $('#age').val();
                d.address= $('#address').val();
                // d.from_date= $('#from_date').val();
                // d.to_date= $('#to_date').val();

            }
        },
        "columns": [
            {
                "data": "id",
                // render: function(data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }
                render: function(data, type, row, meta) {
                    return "<?= Session::get('current_branch_code') ?> -"+ data;
                }
            },
            {
                "data": "name"
            },
            {
                "data": "dob",
                "render":function(dob, type, full, meta)
                {
                    var tempdob=dob;
                    dob = new Date(dob);
                    var today = new Date();
                    var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
                  //  return age + "- " +tempdob;
                  return age ;
                }
            },
            {
                "data": "gender",
                "render": function(data, type, full, meta) {
                    return (data==0) ? 'NA' :data;
                }
            },
            {
                "data": "address"
            },
            {
                "data": "mobile_number"
            },
            {
                "data": "created_at",
                "render": function(data, type, full, meta) {
                    var date = new Date(data);
                    var month = date.getMonth() + 1;
                    return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" + month) + "/" +   date.getFullYear();


                },
            },

            // {
            //     "data": "id",
            //     "render": function(display_status, type, full, meta) {
            //         return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1 view_details"><i class="fa fa-pencil"></i></a></div>'
            //     }
            // },
        ]
        });

  }
$(document).ready(function() {
    $(".search_by").keydown(function(event) {
        if (event.which === 13)
        {
            event.preventDefault();
            tablereload();

        }
    });
});

</script>
