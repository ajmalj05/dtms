<div class="content-body">

    <div class="container-fluid pt-2">

        <div class="row">

            <div class="col-xl-4">
                <form action="#" name="frm" id="frm" method="POST">
                <div class="card">
                    <div class="card-body">
                        <h3>{{$branch_Details->branch_name}}</h3>
                        <hr>
                        <div class="row pt-2">
                            <label>Address Line 1</label>
                            <input type="text" class="form-control" value="{{$branch_Details->address_line_1}}" name="address_line_1" id="address_line_1">
                        </div>

                        <div class="row pt-2">
                            <label>Address Line 2</label>
                            <input type="text" class="form-control" value="{{$branch_Details->address_line_2}}" name="address_line_2" id="address_line_2">
                        </div>

                        <div class="row pt-2">
                            <label>Phone</label>
                            <input type="text" class="form-control" value="{{$branch_Details->phone}}" name="phone" id="phone">
                        </div>

                        <div class="row pt-2">
                            <label>Email</label>
                            <input type="text" class="form-control" value="{{$branch_Details->email}}" name="email" id="email">
                        </div>

                        <div class="row pt-2">
                            <label>Tag Line</label>
                            <input type="text" class="form-control" value="{{$branch_Details->tag_line}}" name="tag_line" id="tag_line">
                        </div>

                        <button type="button" class="btn btn-sm btn-primary my-2 pull-right" onclick="submit_form()">Update</button>

                    </div>
                </div>
            </form>
            </div>


            <div class="col-xl-4">
                <form action="#" name="frm1" id="frm1" method="POST">

                <div class="card">
                    <div class="card-body">
                        <h3>PRINT SETTINGS</h3>
                        <hr>

                        <div class="row pt-2">
                            <div class="col-md-12">
                            <label>Select Print</label>
                            <select class="form-control" name="print_item" id="print_item" onchange="getPrintSettings()">
                                <option>--select--</option>
                                <option value="1">Bill Print</option>
                                <option value="2">Vital Print</option>
                            </select>
                        </div>
                        </div>

                        <div class="row pt-2">
                                <div class="col-md-6">
                                    <label>Size</label>
                                    <select class="form-control" name="paper_size" id="paper_size">
                                        <option value="1">A4</option>
                                        <option value="2">A5</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Type</label>
                                    <select class="form-control" name="paper_mode" id="paper_mode">
                                        <option value="1">Portrait</option>
                                        <option value="2">Landscape</option>
                                    </select>
                                </div>
                        </div>


                        <button type="button" class="btn btn-sm btn-primary my-2 pull-right" onclick="submit_settings()">Update</button>


                    </div>
                </div>

                </form>
            </div>

        </div>

    </div>

</div>

@include('frames/footer');

<script>
    function submit_form()
    {
        url = '{{ route('saveBranchData') }}';
        var form = $('#frm')[0];
        var formData = new FormData(form);

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result) {

                if (result.status == 1) {
                    swal("Done", result.message, "success");

             } else if (result.status == 2) {
                    sweetAlert("Oops...", result.message, "error");
                } else {
                    sweetAlert("Oops...", result.message, "error");
                }
            }

        });

    }

    function submit_settings()
    {

        var print_item=$("#print_item").val();
        if(print_item>0){


        url = '{{ route('savePrintSettings') }}';
        var form = $('#frm1')[0];
        var formData = new FormData(form);

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result) {

                if (result.status == 1) {
                    swal("Done", result.message, "success");

             } else if (result.status == 2) {
                    sweetAlert("Oops...", result.message, "error");
                } else {
                    sweetAlert("Oops...", result.message, "error");
                }
            }

        });
    }
    else{
        sweetAlert("Error!", "Please select an item", "error");
    }
    }

    function getPrintSettings()
    {
        var print_item=$("#print_item").val();
        if(print_item>0)
        {
            url = '{{ route('getPrintSettings') }}';

            var form = $('#frm1')[0];
             var formData = new FormData(form);
            $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result) {
                if (result.status == 1) {

                        var data=result.data;

                        $('#paper_size').val(data.paper_size).trigger('change');
                        $('#paper_mode').val(data.paper_mode).trigger('change');
               }
            }

        });
        }
    }
</script>
