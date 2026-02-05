<link href='date_picker/css/bootstrap-datepicker.min.css' rel='stylesheet' type='text/css'>

<div class="content-body">
    <div class="container-fluid pt-2">
        <form name="frm" id="frm" action="#">


            <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">



                <section>
                    <div class="row">

                        <div class="col-xl-4 col-md-6 mb-2">
                            <div class="form-group">
                                <label class="text-label">From Date</label>

                                <input type="text" name="from_date" id="from_date"
                                
                                    class="form-control" placeholder="">
                            </div>
                        </div>


                        <div class="col-xl-4 col-md-3 mb-2">
                            <div class="form-group">
                                <label class="text-label">To Date </label>


                                <input type="text" name="to_date" id="to_date" value="<?=date('d-m-Y');?>"
                                    class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-3 mb-2">
                            <div class="form-group">
                                <label class="text-label" style="opacity:0">Search</label>
                                <br>
                                <button class="btn btn-primary  btn-sm search-btn"
                                   >Search</button>
                            </div>
                        </div>


                        <div class="col-xl-1 col-md-3 mb-2 ">
                            <div class="form-group">
                                <label class="text-label" style="opacity:0">Search</label>
                                <br>

                            </div>
                        </div>




                    </div>

                </section>
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
                                        <table id="productPurchase" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Sl No.</th>
                                                    <th>Order No.</th>
                                                    <th> Purchased By</th>
                                                    <th>Total Amount </th>
                                                    <th>Payment Status</th>
                                                    <th> Purchased Date</th>
                                                    <th>View Item</th>
                                                    <th>Status Updates</th>
                                                    <th>Upload Invoice</th>


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
<div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h3 text-info" id="myModalLabel">Status Updates</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">


                        <div class="dropdown">
                            <label for="dropdownButton">Choose status</label>
                            <select class="form-control" name="purchase_status1" id="purchase_status1">
                                <option value="">Select Status</option>
                                <option value="1">Cancel</option>
                                <option value="2">Processed</option>
                                <option value="3">Dispatched</option>
                                <option value="4">Delivered</option>

                            </select>

                        </div>

                        <div class="form-group pt-2">
                            <label for="inputBox">Remarks</label>
                            <input type="text" class="form-control" id="inputBox" placeholder="Enter the remarks">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="openStatus()">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="myModalInvoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h3 text-info" id="myModalLabel">Upload Invoice</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">


                        <div class="form-group" id="validation">
                                <label class="text-label">Upload Invoice</label>
                                <input type="file" accept="application/pdf" class="file-input" id="images" name="images"  />
                            <small class="form-text text-muted error" id="invoiceImg_error"></small>
                            </div>
                            <input type="hidden" name="invoice_id" id="invoice_id" value="0">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="uploadInvoice()">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <input type="hidden" name="hidOrderId1" id="hidOrderId1" value="0">
    <input type="hidden" name="paymentStatusHiden" id="paymentStatusHiden" value="0">



    @include('frames/footer');
    @include('modals/productPurchase_modal',['title'=>'Selected items','data'=>'dfsds'])
    <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
    <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
    <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>


    <script>
    $(document).ready(function() {
        table = $('#productPurchase').DataTable({
            "order": [],
            "autoWidth": false,
            "dom": 'Blfrtip',
            "destroy": true,

            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],

            'ajax': {
                url: "<?php echo url('/') ?>/appManagement/productListing",
                type: 'POST',
                "data": function(d) {
                    // d._token= "{{ csrf_token() }}";
                    // d.uhid= $('#s_uhid').val();
                    // d.mobile_number= $('#s_mobile_number').val();
                    // d.patient_type= $('#patient_type').val();
                    // d.patient_name= $('#s_patient_name').val();
                    // d.last_name= $('#last_name').val();
                    // d.gender= $('#gender').val();
                    // d.age= $('#age').val();
                    // d.address= $('#address').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            "columns": [{
                    "data": "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data":"order_number"

                },
                {
                    "data": "name"
                },
                {
                    "data": "total_amount"
                },
                {
                    "data": "payment_status",
                "render": function(data, type, row, meta) {
                    if (data == '0' ) {
                       return "Not Paid";
                    }else{
                        return "Paid";
                    }
                }
                },
                {
                    "data": "created_at",
                    "render": function(data, type, row, meta) {
                        if (type === 'display' || type === 'filter') {
                            var date = new Date(data);
                            var formattedDate = date.toLocaleDateString();
                            return formattedDate;
                        }
                        return data;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            // return '<button type="button" class="btn btn-primary" data-toggle="modal" id="v_btn" data-target="#view-btn">VIEW</button>';

                            return '<a href="#" id="v_btn" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a>';
                        }
                        return '';
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            return '<a href="#" id="status_btn" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-shopping-cart"></i></a>';
                        }
                        return '';
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            return '<a href="#" id="invoice_btn" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-upload" aria-hidden="true"></i></a>';
                        }
                        return '';
                    }
                },



            ]
        });
    });
    //////
    $(document).ready(function() {


        $('.search-btn').click(function(e) {
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


    $('#productPurchase').on('click', '#v_btn', function() {
        var data = table.row($(this).parents('tr')).data();
        let id = data.id;
        openmodal(id);

        console.log(data);

    });


    $('#productPurchase').on('click', '#status_btn', function() {
        var data = table.row($(this).parents('tr')).data();
        console.log(data);
        //read id
        let statusId = data.id;
        // let paymentStatus = data.payment_status;
        let order_status = data.order_status;
        let remarks=data.remarks
        console.log(order_status);

        if(order_status!=0){
        $("#purchase_status1").val(order_status);
        $("#purchase_status1").val(order_status).change();

    }else{
        // $('#purchase_status1').val('');
        $("#purchase_status1").val('').change();
    }
    
        // copy the value in hidden box
        // $('#myModal').modal();

        if (remarks  != null) {
            $("#inputBox").val(remarks).change();
        } else {
            $("#inputBox").val('').change();

        }

        $('#hidOrderId1').val(statusId);
        $('#paymentStatusHiden').val(remarks);


        // openStatus(statusId,paymentStatus);

        $('#myModal').modal();
       
        // $("#purchase_status1").val('0').change();

        

    
        });

       



    function openStatus() {
        var dataId = $('#hidOrderId1').val();
        var statusvalue = $('#paymentStatusHiden').val();
        var statusUpdate = $('#purchase_status1').val();
        var remarks = $('#inputBox').val();
        if (statusvalue == "Not Paid") {
            if (statusUpdate != 1) {
                swal("Only the cancellation option is available for those who haven't made a payment");
            }
        } else {
            var modalData = {
                dataId: dataId,
                statusUpdate: statusUpdate,
                remarks: remarks,

            };
            console.log(modalData);

            url = "{{route('productPurchaseStatus')}}";
            $.ajax({
                type: "POST",
                url: url,
                data: modalData,
                success: function(result) {

                    if (result.status == 1) {
                        $('#myModal').modal('hide');
                        swal("Done", result.message, "success");

                        table.ajax.reload();
                        

                    } else if (result.status == 2) {
                        sweetAlert("Oops...", result.message, "error");
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
                error: function(result, jqXHR, textStatus, errorThrown) {
                    if (result.status === 422) {
                        result = result.responseJSON;
                        var error = result.errors;
                        $.each(error, function(key, val) {
                            console.log(key);
                            $("#" + key + "_error").text(val[0]);
                        });
                    }

                }
            });

        }

    }

    function searchList() {
        var fromDta = document.getElementById("from_date").value;
        var todate = document.getElementById("to_date").value;
        console.log(fromDta);
    }

    // search table data
    //save status function
    // read status , reamkr,  id from hiden box
    // date(Y-m-d H:i:s)

    $('#productPurchase').on('click', '#invoice_btn', function() {
        var data = table.row($(this).parents('tr')).data();
        let id = data.id;
        $('#invoice_id').val(id);
        $('#invoiceImg_error').text('');

        $('#myModalInvoice').modal();

    });
    function uploadInvoice() {
    var dataId = $('#invoice_id').val();
    var invoiceImg = document.getElementById("images").files[0]; 
    console.log(invoiceImg);

    var formData = new FormData();
    formData.append('dataId', dataId);
    formData.append('invoiceImg', invoiceImg);
    url = "{{route('productInvoiceStatus')}}";
    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        contentType: false, 
        processData: false, 
        success: function(result) {
            if (result.status == 1) {
                $('#myModalInvoice').modal('hide');
                $('#images').val('');
                swal("Done", result.message, "success");
                table.ajax.reload();
            } else if (result.status == 2) {
                sweetAlert("Oops...", result.message, "error");
            } else {
                sweetAlert("Oops...", result.message, "error");
            }
        },
        error: function(result, jqXHR, textStatus, errorThrown) {
            if (result.status === 422) {
                result = result.responseJSON;
                var error = result.errors;
                $.each(error, function(key, val) {
                    console.log(key);
                    $("#" + key + "_error").text(val[0]);
                });
            }
        }
    });
}

    </script>