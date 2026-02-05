<div class="modal fade" id="exampleModalLabel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid pt-2">

                    <div class="row">
                        <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card card-sm">
                                            <div class="card-header">
                                                <h4 class="card-title">Purchased List</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive pt-3">
                                                    <table id="selected_lists" class="display table" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th> Sl No</th>
                                                                <th> Selected Items</th>
                                                                <th> Unit Price</th>
                                                                <th>Number of Items</th>
                                                                <th>Total Price</th>

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
    <input type="hidden" name="hidOrderId" id="hidOrderId" value="0">

    <script>
        $(document).ready(function() {

            table2 = $('#selected_lists').DataTable({

                'ajax': {
                    url: "<?php echo url('/') ?>/appManagement/getSelectedItems",
                    type: 'POST',
                    "data": function(d) {
                        d.dataid = $("#hidOrderId").val();
                    }
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
                        "data": "product_price"
                    },
                    {
                        "data": "product_qty",
                    },
                    {
                        "data": "unit_total"
                    },
                ]
            });


        });

        function openmodal(id) {
           $("#hidOrderId").val(id);
           table2.ajax.reload();
           $('#exampleModalLabel').modal();

        }
    </script>
