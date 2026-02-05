<style>
    .group {
        background-color: aquamarine !important;
    }
    </style>
    <div class="content-body">
        <div class="container-fluid">

            <form name="frm" id="frm" action=""  class="card card-body" method="POST">
                @csrf
                <div class="row">






                    <div class="col-xl-2 col-md-6">
                        <div class="form-group">
                            <label class="text-label">From Date</label>
                            <input type="text" name="from_date" id="from_date" class="form-control" value="<?= date('d-m-Y')?>"   placeholder="" >
                            <small id="name_error" class="form-text text-muted error"></small>
                        </div>
                    </div>


                    <div class="col-xl-2 col-md-3">
                        <div class="form-group">
                            <label class="text-label">To Date </label>


                            <input type="text" name="to_date" id="to_date"  value="<?=date('d-m-Y');?>" class="form-control" placeholder="" >
                            </div>
                      </div>
                      <div class="col-xl-2 col-md-3 d-flex align-items-center">
                        <div>
                            {{-- search-btn --}}
                            <button class="btn btn-primary  btn-sm" type="submit">Search</button>
                        </div>
                      </div>
                </div>
            </form>
            {{-- DT --}}

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <div class="card card-sm ">

                        <div class="card-body">
                             <table id="visit_lists" class="display">
                                <thead>
                                    <tr>

                                        <th>BILL DATE</th>
                                        <?php
                                            foreach ($payment_modes as $key) {
                                               ?>
                                               <th>{{$key->payment_mode_name}}</th>
                                               <?php
                                            }
                                        ?>
                                        <th>Total</th>

                                    </tr>
                                </thead>
                                <tbody id="search_filter">

                                    <?php
                                        foreach ($payemt_Details as $result) {
                                            $b_date=$result->bill_date;
                                            $b_date= date("d-m-Y", strtotime($b_date));
                                            ?>
                                            <tr>
                                                <td>{{$b_date}}</td>
                                                <?php
                                                $total=0;
                                                  foreach ($sel_cols as $col) {
                                                        $amount=$result->$col;
                                                            if(!$amount || $amount=="") $amount=0;
                                                            $total=$total+$amount;
                                                        ?>
                                                         <td align="right">{{number_format($amount,2)}}</td>
                                                        <?php
                                                  }
                                                ?>
                                                <td  align="right"><b>{{number_format($total,2)}}</b></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- END OF DT --}}

        </div>
    </div>

    @include('frames/footer');
    <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
    <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
    <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>

    <script>
    $(document).ready(function() {


    $('.search-btn').click(function(e){
        e.preventDefault();
        table.ajax.reload();
    })

    $('#to_date').datepicker({
            autoclose: true,
            // endDate: '+0d',
            format: 'dd-mm-yyyy'
    });
    $('#from_date').datepicker({
            autoclose: true,
            // endDate: '+0d',
            format: 'dd-mm-yyyy'
    });
    });

    $(document).ready(function(){
        var groupColumn = 0;

       // $("#visit_lists").dataTable().fnDestroy();
         table = $('#visit_lists').DataTable(
            {
                "order": [] ,
                "dom": 'Blfrtip',
                buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
                ],
            }
         );

        // $("#visit_lists").dataTable().fnDestroy()
        //     table = $('#visit_lists').DataTable({

        //         scrollY: 470,
        //         "order": [] ,
        //         "dom": 'Blfrtip',


        //     buttons: [
        //         'copy', 'csv', 'excel', 'pdf', 'print'
        //     ],
        //     'ajax': {
        //         url: "<?php echo url('/') ?>/get-day-collection-report",
        //         type: 'POST',
        //         "data": function(d) {

        //             d._token= "{{ csrf_token() }}";
        //             d.from_date= $('#from_date').val();
        //             d.to_date= $('#to_date').val();

        //         }
        //     },

        //     "columns": [

        //         {
        //             "data": "created_at",
        //             "className": "text-right",
        //             "render": function(data, type, full, meta) {
        //                 var date = new Date(data);
        //                 var month = date.getMonth() + 1;
        //                 return ('0' + date.getDate()).slice(-2) + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   date.getFullYear();

        //             },
        //         },
        //         {
        //             "data": "patient_billing_mode_id",
        //             "className": "text-right",
        //             "render": function(patient_billing_mode_id, type, full, meta) {

        //                 if (patient_billing_mode_id == 5) return '<span>'+full.total_paid+'</span>';
        //                 // else if (patient_billing_mode_id == 2)return '<span>Card</span>';
        //                 else return '<span></span>';
        //             }
        //         },
        //         {
        //             "data": "patient_billing_mode_id",
        //             "className": "text-right",
        //             "render": function(patient_billing_mode_id, type, full, meta) {
        //                 if (patient_billing_mode_id == 4) return '<span>'+full.total_paid+'</span>';
        //                 // else if (patient_billing_mode_id == 2)return '<span>Card</span>';
        //                 else return '<span></span>';
        //             }
        //         },
        //         {
        //             "data": "patient_billing_mode_id",
        //             "className": "text-right",
        //             "render": function(patient_billing_mode_id, type, full, meta) {
        //                 if (patient_billing_mode_id == 1) return '<span>'+full.total_paid+'</span>';
        //                 // else if (patient_billing_mode_id == 2)return '<span>Card</span>';
        //                 else return '<span></span>';
        //             }
        //         },
        //         {
        //             "data": "patient_billing_mode_id",
        //             "className": "text-right",
        //             "render": function(patient_billing_mode_id, type, full, meta) {
        //                 if (patient_billing_mode_id == 3) return '<span>'+full.total_paid+'</span>';
        //                 // else if (patient_billing_mode_id == 2)return '<span>Card</span>';
        //                 else return '<span></span>';
        //             }
        //         },
        //         {
        //             "data": "patient_billing_mode_id",
        //             "className": "text-right",
        //             "render": function(patient_billing_mode_id, type, full, meta) {
        //                 if (patient_billing_mode_id == 2) return '<span>'+full.total_paid+'</span>';
        //                 // else if (patient_billing_mode_id == 2)return '<span>Card</span>';
        //                 else return '<span></span>';
        //             }
        //         },
        //         {
        //             "data": "patient_billing_mode_id",
        //             "className": "text-right",
        //             "render": function(patient_billing_mode_id, type, full, meta) {
        //                 if (patient_billing_mode_id == 2) return '<span>'+full.total_paid+'</span>';
        //                 // else if (patient_billing_mode_id == 2)return '<span>Card</span>';
        //                 else return '<span></span>';
        //             }
        //         },
        //         {
        //             "data": "balance_amount",className: "text-right"
        //         },
        //         {
        //             "data": "TotalAmount",className: "text-right"
        //         },

        //     ]
        // });
    });



    </script>
