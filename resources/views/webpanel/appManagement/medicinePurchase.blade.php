<style>
    .tablettypecls .tablettype_options {
        width: 100% !important;
    }
</style>
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

                                <input type="text" name="from_date" id="from_date" class="form-control"
                                    placeholder="">
                            </div>
                        </div>


                        <div class="col-xl-4 col-md-3 mb-2">
                            <div class="form-group">
                                <label class="text-label">To Date </label>


                                <input type="text" name="to_date" id="to_date" value="<?= date('d-m-Y') ?>"
                                    class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-1 col-md-3 mb-2">
                            <div class="form-group">
                                <label class="text-label" style="opacity:0">Search</label>
                                <br>
                                <button class="btn btn-primary  btn-sm search-btn">Search</button>
                            </div>
                        </div>
                        <div class="col-xl-1 col-md-3 mb-2 ">
                            <div class="form-group">
                                <label class="text-label" style="opacity:0">Search</label>
                                <br>

                            </div>
                        </div>
                        <div class="col-xl-2 col-md-3 mb-2">
                            <div class="d-flex flex-wrap align-content-center h-100 w-100">
                                <button type="button" class="btn btn-primary btn-xl mt-1" onclick="openModal()">Add
                                    Medicine Order</button>
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
                                        <table id="medicinePurchase" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Sl No.</th>
                                                    <th> Purchased By</th>
                                                    <th>Total Amount </th>
                                                    <th>Payment Status </th>
                                                    <th>Payment Remarks </th>
                                                    <th>Remarks </th>
                                                    <th> Purchased Date</th>
                                                    <th>View Item</th>
                                                    <th>Status Updates</th>
                                                    <th>Update payment</th>
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
<!-- modal for listing medicine order -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Medicine Purchase</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>sl.No</th>
                                <th>Item</th>
                                <!-- <th>Number of Items</th> -->
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- end of medicinal order list -->
<!-- status updates -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                            <option value="0">Select Status</option>
                            <option value="1">Cancel</option>
                            <option value="2">Processed</option>
                            <option value="3">Dispatched</option>
                            <option value="4">Delivered</option>

                        </select>

                    </div>

                    <div class="form-group pt-2">
                        <label for="inputBox">Remarks</label>
                        <input type="text" class="form-control" name="inputBox1" id="inputBox1"
                            placeholder="Enter the remarks">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="openMedicineStatus()">Save</button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Payment updates -->
<div class="modal fade" id="modal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h3 text-info" id="modallabel3">Update Payment</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label id="lbl_key" style="display: none;"></label>
                    <div class="form-group pt-2">
                        <label for="inputBox">Enter Amount</label>
                        <input type="number" class="form-control" id="totalAmount" placeholder="Enter Amount">
                    </div>
                    <div class="form-group pt-2">
                        <label for="inputBox">Remarks</label>
                        <input type="text" class="form-control" id="paymentRemarks" placeholder="Enter Remarks">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="Updateamount()">Update amount</button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- //////add medicine modal -->
<div class="modal fade" id="medicineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Medicine </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form name="medicineForm" id="medicineForm" action="#">

                <!-- Modal body -->
                <div class="modal-body" id="frm">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2">
                                <label id="lbl_key" style="display: none;"></label>
                                <div class="form-group" id="validation">
                                    <label for="uhidNumber">Enter UHID No.</label>
                                    <input type="text" class="form-control" id="uhidNumber"
                                        placeholder="Enter UHID No">
                                </div>
                            </div>
                            <div class="col-md-2 mt-2">
                                <button type="button" id="btn_uhid" class="btn btn-primary btn-sm mt-3"
                                    onclick="getUserInfo()">Search</button>
                            </div>

                            <div class="col-md-4">
                                <label id="lbl_key" style="display: none;"></label>
                                <div class="form-group">
                                    <label for="uhidNumber">Patient Name</label>
                                    <input type="text" class="form-control" id="patient_name" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label id="lbl_key" style="display: none;"></label>
                                <div class="form-group">
                                    <label for="uhidNumber">Phone Number</label>
                                    <input type="text" class="form-control" id="patient_number" disabled>
                                </div>
                            </div>

                        </div>

                        <div class="form-group pt-2 tablettypecls" id="validation1">
                            <label for="inputBox">Select Tablet Type</label>
                            <select class="tablettype_options" id="tablettype_options">
                                <option value=''>All</option>
                                {{ LoadCombo('tablet_type_master', 'id', 'tablet_type_name', '', 'where display_status=1 AND is_deleted=0', 'order by id desc') }}

                            </select>
                        </div>
                        <label id="lbl_key" style="display: none;"></label>
                        <div class="form-group pt-2">
                            <label for="inputBox">Select Medicine</label>
                            <select class="js-data-example-ajax" id="tablettype_type"></select>
                        </div>
                        <div class="table-responsive mt-5">
                            <table class="table table-striped" id="medicine_order_table">
                                <thead>
                                    <tr>
                                        <th>Selected Medicine</th>
                                        <th>Qty</th>

                                    </tr>
                                </thead>
                                <tbody id="selectedMedTbl">

                                </tbody>
                            </table>
                        </div>
                        <div>
                            <small id="medicine_options_name__error"></small>
                        </div>

                        <label id="lbl_key" style="display: none;"></label>
                        <div class="form-group pt-2">
                            <label for="inputBox">Amount</label>
                            <input type="number" class="form-control" name="medicineAmount" id="medicineAmount"
                                placeholder="Enter Amount">
                            <small id="amount_error" class="form-text text-muted error"></small>

                        </div>

                    </div>

                </div>
            </form>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success  " onclick="saveoredrMedicine()">Save</button>

                <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
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



<!-- end of status updates -->

<input type="hidden" name="paymentStatusHiden" id="paymentStatusHiden" value="0">

<input type="hidden" name="hidMedicineId1" id="hidMedicineId1" value="0">
<input type="hidden" name="getPatientId" id="getPatientId" value="0">
<input type="hidden" name="updateId" id="updateId" value="0">
<input type="hidden" name="hiddenId" id="hiddenId" value="0">






@include('frames/footer');
<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>
<script>
    // ---------Data Table --------------------------//
    var selectedTabletType = [];
    var count = 1;
    var patientId;
    $(document).ready(function() {


        table3 = $('#medicinePurchase').DataTable({
            "order": [],
            "autoWidth": false,
            "dom": 'Blfrtip',
            "destroy": true,

            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],
            'ajax': {
                url: "<?php echo url('/'); ?>/appManagement/medicineListing",
                type: 'POST',
                "data": function(d) {
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
                    "data": "name"
                },
                {
                    "data": "order_amt"
                },
                {
                    "data": "payment_status",
                    "render": function(data, type, row, meta) {
                        if (data == '0') {
                            return "Not Paid";
                        } else {
                            return "Paid";
                        }
                    }
                },
                {
                    "data": "payment_remarks"
                    // "data": "payment_status",
                    // "render": function(data, type, row, meta) {
                    //     if (data == '0') {
                    //         return "Not Paid";
                    //     } else {
                    //         return "Paid";
                    //     }
                    // }

                },
                {
                    "data": "remarks"
                },

                {
                    "data": "created_at",
                    "render": function(data, type, row, meta) {
                        if (type === 'display' || type === 'filter') {
                            var date = new Date(data);
                            var formattedDate = date.toLocaleDateString();
                            var dateParts = formattedDate.split('/');
                            var dateObject = new Date(dateParts[2], dateParts[0] - 1, dateParts[
                                1]);
                            var convertedDate = dateObject.toLocaleDateString('en-GB')
                            return convertedDate;
                        }
                        return data;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            // return '<button type="button" class="btn btn-primary" data-toggle="modal" id="v_btn" data-target="#view-btn">VIEW</button>';

                            return '<a href="#" id="viewBtn" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-eye"></i></a>';
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
                            return '<a href="#" id="update_payment_btn" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-inr" aria-hidden="true"></i></a>';
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
        $(".js-data-example-ajax").select2({
            placeholder: "Search Medicine",
            ajax: {
                url: "{{ route('searchMedicineNames') }}",
                type: "post",
                dataType: 'json',
                // delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term, // search term
                        typeid: $('#tablettype_options').val(),
                    };

                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('.js-data-example-ajax').on('select2:select', function(e) {
            var data = e.params.data;
            add_duplicate(data.tablet_typeid, data.id, '', '', data.tablet_type_name, data.text);


        });


    });

    $(document).ready(function() {


        $('.search-btn').click(function(e) {
            e.preventDefault();
            table3.ajax.reload();
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


    // ---------display total amount --------------------------//

    function totalamount() {
        table3 = $('#medicinePurchase').DataTable({
            "order": [],
            "autoWidth": false,
            "dom": 'Blfrtip',
            "destroy": true,

            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],
            'ajax': {
                url: "<?php echo url('/'); ?>/appManagement/medicineListing",
                type: 'POST',
                "data": function(d) {

                }
            },
            "columns": [{
                    "data": "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "name"
                },
                {
                    "data": "order_amt"

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

                            return '<a href="#" id="viewBtn" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-eye"></i></a>';
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

                        return '<a href="#" id="update_payment_btn" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-usd" aria-hidden="true"></i></a>';
                    }
                },
            ]
        });
    }




    //-------------------- on click view button---------------- //
    $('#medicinePurchase').on('click', '#viewBtn', function() {
        var data = table3.row($(this).parents('tr')).data();
        let statusId = data.id;
        $('#myModal').modal();
        $('#myModal table tbody').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
        $('#hidMedicineId1').val(statusId);
        medicineOrder();

    });

    function medicineOrder() {
        let orderId = $('#hidMedicineId1').val();
        let data = {
            id: orderId
        };
        url = "{{ route('medicineOrderList') }}";
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(result) {
                if (result) {
                    let dataOut = result;
                    $('#myModal table tbody').empty();
                    for (let i = 0; i < dataOut.length; i++) {
                        let item = dataOut[i];
                        let row = '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>'
                        if (item.medicineTypeImagePath) {
                            let imagePath = item.medicineTypeImagePath;
                            row += '<button class="btn btn-primary id="view_btn" value="' + imagePath +
                                '" onclick="viewMedicine(this.value)">View</button>';
                        } else if (item.medicine_name) {
                            row += item.medicine_name;
                        }

                        row += '<br>' +
                            '</td>' +
                            // '<td>' + item.medicineAmt + '</td>' +
                            '<td>' + item.medicineQty + '</td>' +

                            '</tr>';
                        $('#myModal tbody').append(row);


                    }
                } else {
                    sweetAlert("Oops...", result.message, "error");
                }
            },
            error: function(result, jqXHR, textStatus, errorThrown) {
                if (result.status === 422) {
                    result = result.responseJSON;
                    var error = result.errors;
                    $.each(error, function(key, val) {
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            }
        });






    }

    function viewMedicine(value) {
        let root = "<?php echo url('/'); ?>";
        let imagePath = root + "/" + value;
        window.open(imagePath, '_blank');
        $(this).removeClass('active');

    }


    //-------------- status button--------------- //
    $('#medicinePurchase').on('click', '#status_btn', function() {
        var data = table3.row($(this).parents('tr')).data();

        //read id
        let statusId = data.id;
        let paymentStatus = data.order_status;
        let order_remarks = data.order_remark;
        // copy the value in hidden box
        if (paymentStatus != 0) {
            $('#paymentStatusHiden').val(paymentStatus);
            $("#purchase_status1").val(paymentStatus).change();

        } else {
            // $('#purchase_status1').val('');
            $("#purchase_status1").val('0').change();
        }
        if (order_remarks != null) {
            $("#inputBox1").val(order_remarks).change();
        } else {
            $("#inputBox1").val('').change();

        }
        $('#myModal2').modal();
        // openMedicineStatus(statusId,paymentStatus)
        $('#hidMedicineId1').val(statusId);

    });




    function openMedicineStatus() {
        var dataId = $('#hidMedicineId1').val();

        var statusUpdate = $('#purchase_status1').val();
        var remarks = $('#inputBox1').val();
        var statusvalue = $('#paymentStatusHiden').val();
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

            url = "{{ route('medicinePurchaseStatus') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: modalData,
                success: function(result) {

                    if (result.status == 1) {
                        $('#myModal2').modal('hide');

                        swal("Done", result.message, "success");
                        table3.ajax.reload();

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
                            $("#" + key + "_error").text(val[0]);
                        });
                    }

                }
            });

        }

    } //-------------- updatepayment button--------------- //
    $('#medicinePurchase').on('click', '#update_payment_btn', function() {
        var data = table3.row($(this).parents('tr')).data();
        //read id

        let Id = data.id;
        let paymentStatus = data.payment_status;
        let amount = data.order_amt;
        let remarks = data.payment_remarks


        if (paymentStatus == '0') {
            $('#totalAmount').val(amount);
            $('#modal3').modal();

            if (paymentStatus !== '0') {
                $('#totalAmount').val(amount);
                $("#totalAmount").val(amount).change();

            } else {
                // $('#purchase_status1').val('');
                $("#totalAmount").val('').change();
            }
            if (remarks != null) {
                $('#totalAmount').val(amount);
                $("#paymentRemarks").val(remarks).change();
            } else {
                $("#paymentRemarks").val('').change();

            }




        } else {

            // swal(" amount is already paid by the customer");
            swal({
                text: "Amount is already paid by the customer",
                html: '<div style="font-size: 18px;">Amount is already paid by the customer</div>'
            });
        }

        // copy the value in hidden box

        $('#lbl_key').val(Id);







    });



    function Updateamount() {
        var amount = $('#totalAmount').val();
        var orderid = $('#lbl_key').val();
        var remarks = $('#paymentRemarks').val();

        var modalData = {
            orderId: orderid,
            paymentamount: amount,
            remarks: remarks
        };
        $('#totalAmount').val('');
        url = "{{ route('UpdatepaymentStatus') }}";
        $.ajax({
            type: "POST",
            url: url,
            data: modalData,
            success: function(result) {
                if (result.status == 1) {
                    $('#modal3').modal('hide');
                    $(this).click(totalamount);
                    table3.ajax.reload();
                    // $('#modal3').modal('hide');
                    swal("Done", result.message, "success");
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
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            }
        });
    }

    function openModal() {
        // $("#medicineModal input[type='text']").val("");
        $("#uhidNumber").val('').change();
        $("#tablettype_options").val('').change();
        $("#tablettype_type").val('').change();
        $("#medicine_order_table tbody").empty();
        $("#medicineAmount").val('').change();
        selectedTabletType = [];
        $('#medicineModal').modal();
        $('#patient_name').val('');
        $('#patient_number').val('');
        $('#getPatientId').val();
        $(".text-danger").text("");

    }
    // $("#uhidNumber").on('keypress', function(e) {
    //     var uhidValue = $('#uhidNumber').val();
    //     var modalData = {
    //         uhidValue: uhidValue,
    //     };
    //     url = "{{ route('getUhidNumber') }}";
    //     $.ajax({
    //         type: "POST",
    //         url: url,
    //         data: modalData,
    //         success: function(result) {
    //             console.log(result.id);
    //             $('#getPatientId').val(result.id);

    //         },
    //     });

    // });

    function getUserInfo() {
        var uhidValue = $('#uhidNumber').val();
        var modalData = {
            uhidValue: uhidValue,
        };
        url = "{{ route('getUhidNumber') }}";
        $.ajax({
            type: "POST",
            url: url,
            data: modalData,
            success: function(result) {

                if (result.length == 0) {
                    sweetAlert("Oops...", "Invalid UHID", "error");
                    $("#uhidNumber").val("");
                } else {
                    $('#patient_number').val(result.mobile_number);
                    var trimmedName = result.name.trim();
                    var trimmedLastName = result.last_name ? result.last_name.trim() : ''; // Check for null;
                    var fullName = trimmedName + (trimmedLastName !== '' ? ' ' + trimmedLastName : ''); // Concatenate if not empty

                    $('#patient_name').val(fullName);

                    $('#getPatientId').val(result.id);

                }

            },
        });


    }
    //   $("#uhidNumber").validate({
    //     rules: {
    //         uhidNumber: "required",

    //     },
    //     messages: {
    //         uhidNumber: "Please enter your name",

    //     }
    //   });
    // });

    // function getSelectedId(id) {
    //     var selectedText = $(id).find(":selected").text();
    //     var selectedValue = $(id).val();
    //     var newItem = {
    //         'id': selectedValue,
    //         'text': selectedText
    //     };
    //     var existingItem = findById(selectedValue);
    //     if (!existingItem) {
    //         selectedTabletType.push(newItem);
    //         displayItems();
    //     }
    // }

    // function findById(idv) {
    //     return selectedTabletType.find(function(item) {
    //         return item.id == idv;
    //     });
    // }

    // function displayItems() {

    //     $("#selectedMedTbl").empty();
    //     // Loop through the JSON array and append items to the list
    //     for (var i = 0; i < selectedTabletType.length; i++) {
    //         var item = selectedTabletType[i];
    //         console.log(item);
    //         var listItem = "<tr data-item-id='" + item.id + "'>" +
    //             "<td>" + item.text + "</td>" +
    //             "<td><input type='text' class='item-textbox' id='textqty'></td>" +
    //             "<td><i class='fa fa-trash delete-icon'></i></td>" +
    //             "</tr>";
    //         $("#selectedMedTbl").append(listItem);
    //     }
    // }

    function saveoredrMedicine() {
        $(".text-danger").text('');

        $("[id*='_error']").text('');

        var pid = $('#getPatientId').val();
        console.log(pid);
        var form = $('#medicineForm')[0];
        var formData = new FormData(form);
        formData.append('pid', pid);
        // for (var pair of formData.entries()) {
        //     console.log(pair[0] + ', ' + pair[1]);
        // }

        var amount = document.getElementById("medicineAmount").value;





        if (pid != 0) {

            url = "{{ route('saveorderMedicine') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.status == 1) {
                        $('#patient_name').val('');
                        $('#patient_number').val('');
                        $('#getPatientId').val('');
                        $('#medicineModal').modal('hide');
                        $(this).click(totalamount);

                        table3.ajax.reload();
                        $('#modal3').modal('hide');
                        swal("Done", result.message, "success");
                    } else if (result.status == 2) {

                        $('#patient_number').val('');

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
                            $("#" + key + "_error").text(val[0]).addClass('text-danger');
                        });
                    }
                }
            });
        } else {
            $("#validation").append('<label class="text-danger">Please Enter UHID No</label>');

        }
    }





    var count = 0;

    function add_duplicate(tablettype_Id = "", medicine_Id = "", remarks = "", dose = "", tablettype_name = '',
        medicine_name = '', id = '') {
        count++;
        let newId;
        if (id != '') {
            var html = "<tr id='tr_" + id + "'>";
            newId = `tr_${id}`
        } else {
            var html = "<tr id='new_" + count + "'>";
            newId = `new_${count}`
        }

        // html += "<td><input type='text'  id='tablet_type_name_" + count + "' class='form-control' readonly><input type='hidden' name='tablet_type_id[]' id='tablet_type_id_" + count + "' class='form-control'></td>";
        html += "<td><input type='text'  id='medicine_options_name_" + count +
            "' class='form-control' name='medicine_options_name_[" + count +
            "]'  readonly><input type='hidden' name='medicine_id[" + count + "]' id='medicine_options_" + count +
            "' class='form-control'></td>";
        html += "<td><input type='number'  name='dose[" + count + "]' id='dose_" + count +
            "'   class='form-control'></td>";
        // html += "<td><input type='text' name='remarks[]' id='remarks_" + count + "' class='form-control' placeholder='Remarks'></td>";
        html += "<td><i style='color:red' class='fa fa-trash' onclick=removeFiled(this);></i></td>";
        html += "</tr>"
        $('#selectedMedTbl').append(html);
        if (tablettype_Id) {
            $('#tablet_type_id_' + count).val(tablettype_Id);
            $('#tablet_type_name_' + count).val(tablettype_name);
        }
        if (medicine_Id) {
            $('#medicine_options_' + count).val(medicine_Id);
            $('#medicine_options_name_' + count).val(medicine_name);
        }
        if (dose) {
            $('#dose_' + count).val(dose);
        }




        // $(".js-data-example-ajax").empty();



        // $(".js-data-example-ajax").val('').select2("refresh");
        // $("#tablettype_options").val('').selectpicker('refresh');


    }
    $('#medicinePurchase').on('click', '#invoice_btn', function() {
        var data = table3.row($(this).parents('tr')).data();
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
    url = "{{route('medicineInvoiceStatus')}}";
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
                table3.ajax.reload();
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

    function removeFiled(thiss) {
        $(thiss).parent().parent().remove();
    }
</script>
{{-- <style>
    .custom-swal-content {
  font-size: 16px;
}
    </style> --}}
