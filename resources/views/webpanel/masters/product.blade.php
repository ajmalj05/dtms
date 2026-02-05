<style>
    table.dataTable
    {
        width: 100% !important;
    }
</style>
<div class="content-body">
  <div class="container-fluid pt-2">
    <div class="row" style="">
      <div class="col-md-12">

        <div class="profile-tab pb-2">
          <div class="custom-tab-1">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a href="#products-tab" data-toggle="tab" class="nav-link active show">Products</a>
                </li>
            </ul>
            <div class="tab-content pt-3">
                <div id="products-tab" class="tab-pane fade active show">

                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body">

                                    <div class=" mb-5">
                                        <form name="product-frm" id="product-frm" method="POST" action="">
                                            <input type="hidden" name="hid_product_id" id="hid_product_id">
                                            <div class="form-group">
                                                <label class="text-label">Product Name<span class="required">*</span></label>
                                                <input type="text" name="product_name" id="product_name" onKeyPress="return Onlycharecters(event)" maxlength="350" class="form-control" placeholder="" required>
                                                <small id="product_name_error" class="form-text text-muted error"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-label">Product Description<span class="required">*</span></label>
                                                <input type="text" name="product_description" id="product_description" onKeyPress="return Onlycharecters(event)" maxlength="80" class="form-control" placeholder="" required>
                                                <small id="product_description_error" class="form-text text-muted error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-label">Product Rate(inclusive of tax)<span class="required">*</span></label>
                                                <input type="text" name="product_rate" id="product_rate" onKeyPress="return onlyNumbers(event)"  class="form-control" placeholder="" required>
                                                <small id="product_rate_error" class="form-text text-muted error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-label">Select Tax<span class="required">*</span></label>
                                                <select id="tax" name="tax" class="form-control">
                                                <option value="">Select</option>
                                                {{LoadCombo("tax_master","id","tax",'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                </select>
                                                <small id="tax_error" class="form-text text-muted error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-label">Product Offer Percentage<span class="required">*</span></label>
                                                <input type="text" name="product_discount_percentage" id="product_discount_percentage" onKeyPress="return blockWithoutPercentageAndOnlyNumbers(event)"  class="form-control" placeholder="" required>
                                                <small id="product_discount_percentage_error" class="form-text text-muted error"></small>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-label">Upload product images<span class="required">*</span></label>
                                                <input type="file" accept="image/png, image/jpeg, image/jpg" class="file-input" id="images" name="images[]" multiple />
                                                <small id="images_error" class="form-text text-muted error"></small>
                                            </div>

                                            <div class="form-check">
                                                <input type="checkbox" name="display_status_product" id="display_status_product" class="form-check-input" checked >
                                                <label class="form-check-label" for="displayStatus">Display Status</label>
                                            </div>

                                            <div id="crud_product">
                                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="saveProductData(1,1)" >Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive pt-3">
                                        <table id="Product" class="display">
                                            <thead>
                                            <tr>
                                                <th>Sl No.</th>
                                                <th>Product Name</th>
                                                <th>Product Rate</th>
                                                <th>Available Quantity</th>
                                                <th>Product Offer Percentage</th>
                                                <th>Display Status</th>
                                                <th>Action</th>
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
    </div>
  </div>


</div>
</div>

@include('frames/footer');
@include('modals/view_picture_modal',['title'=>'Images','data'=>'dfsds'])
@include('modals/stock_management_modal',['title'=>'Stock Management','data'=>'dfsds'])


<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>
<script>
    var inputLocalFont = document.getElementById("images");



  $(document).ready(function() {
    InitializeDT1();

    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        currTabTarget = $(e.target).attr('href');

        if(currTabTarget=="#products-tab") {
            InitializeDT1();
        }
    });


    function InitializeDT1(){

        table = $('#Product').DataTable({
            "destroy": true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            'ajax': {
                url: "<?php echo url('/') ?>/getProductData",
                type: 'POST',
                "data": function(d) {}
            },
            "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
                {
                    "data": "product_name"
                },
                {
                    "data": "product_rate"
                },
                {
                    "data": "available_quantity"
                },
                {
                    "data": "product_discount_percent"
                },
                {
                    "data": "display_status",
                    "render": function(display_status, type, full, meta) {
                        if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                        else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
                    }
                },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {
                        return '<div class="d-flex"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Stock Management"class="stock-management btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-th-large"></i></a><a href="#" class="picture btn btn-info shadow btn-xs sharp mr-1"><i class="fa fa-picture-o"></i></a><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                    }
                },
            ]
        });
    }

      //end of dt


      $('#Product tbody').on('click', '.stock-management', function() {
          $("[id*='_error']").text('');
          var data = table.row($(this).parents('tr')).data();
          $("#hid_pd_id").val(data.id);
          $("#quantity").val(data.available_quantity);
          $('#stock-management-modal').modal();

      });

      $('#Product tbody').on('click', '.edit', function() {
          $("[id*='_error']").text('');
          var data = table.row($(this).parents('tr')).data();
          $("#hid_product_id").val(data.id);
          $('#product_name').val(data.product_name);
          $("#product_description").val(data.product_description.trim());
          $("#product_rate").val(data.product_rate);
          // $("#available_quantity").val(data.available_quantity);
          $("#tax").val(data.tax_id).change();
          $("#product_discount_percentage").val(data.product_discount_percent);
          if(data.display_status==1)  $('#display_status_product').prop("checked", true); else  $('#display_status_product').prop("checked", false);
          crude_btn_manage(2,1);
      });

      $('#Product tbody').on('click', '.picture', function() {
          $('#picture-modal').modal();

          var data = table.row($(this).parents('tr')).data();

          $("#picture_data").dataTable().fnDestroy()

              tableP = $('#picture_data').DataTable({
              dom: 'lfrtip',


              'ajax': {
                  url: "<?php echo url('/') ?>/get-product-images/" + data.id,
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
                      "data": "product_image",
                      "render": function(data, type, full, meta) {
                          return '<a href="/jdc/public/'+data+'" target="_blank" class="active_link">'+data+'</a>';

                      }
                  },

                  {
                      "data": "id",
                      "render": function(data, type, full, meta) {

                          return '<div class="d-flex"><a href="#" class="delete_picture btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                      }
                  },

              ]
          });
          $('#picture_data').DataTable().ajax.reload();
      });


      $('#Product tbody').on('click', '.delete', function() {
        var data = table.row($(this).parents('tr')).data();

        let ajaxval = {
            id: data.id,
        };
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this data!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/') ?>/deleteProductData",
                    data: ajaxval,
                    success: function(result) {

                        if (result.status == 1) {
                            swal("Done", result.message, "success");
                            table.ajax.reload();
                        } else {
                            sweetAlert("Oops...", result.message, "error");
                        }
                    },
                });
            }
        })
    });


    ////////////////////////

  });


  function saveProductData(page,crude)
    {
        $("[id*='_error']").text('');

        var url="";
        if(page==1)
        {
            url='{{route('saveProductData')}}';
            var form = $('#product-frm')[0];
        }

        var formData = new FormData(form);
        let TotalFiles =inputLocalFont.files.length;

        let files = inputLocalFont;
        for (let i = 0; i < TotalFiles; i++) {
            formData.append('files' + i, files.files[i]);
        }
        formData.append('TotalFiles', TotalFiles);
        formData.append('crude', crude);
        $.ajax({
				 type: "POST",
				 url: url,
				 data: formData,
				 processData: false,
				 contentType: false,
				 success: function(result){

                            if(result.status==1)
                            {
                                swal("Done", result.message, "success");
                                document.getElementById("product-frm").reset();
                                if(page==1){
                                    window.location.reload();
                                    // table.ajax.reload();
                                }
                                crude_btn_manage(1,page)
                            }
                            else if(result.status==2){
                                sweetAlert("Oops...",result.message, "error");
                            }
                            else{
                                sweetAlert("Oops...",result.message, "error");
                            }
                     },
                     error: function(result,jqXHR, textStatus, errorThrown){
                         if( result.status === 422 ) {
                            result=result.responseJSON;
                            var error=result.errors;
                             $.each(error, function (key, val) {
                             console.log(key);
                            $("#" + key + "_error").text(val[0]);
                    });
                }

                }
			 });
    }

function crude_btn_manage(type=1,page)
{
    if(page==1)
    {
        if(type==1) $('#crud_product').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="saveProductData(\''+page+'\',\''+type+'\')" >Save</button>');
        else if(type==2)  $('#crud_product').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="saveProductData(\''+page+'\',\''+type+'\')" >Update</button>');

    }
}

</script>
