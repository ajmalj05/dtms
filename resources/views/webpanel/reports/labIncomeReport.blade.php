<style>
    .group {
        background-color: aquamarine !important;
    }
    </style>
    <div class="content-body">
        <div class="container-fluid">
    
            <form name="frm" id="frm" action="#"  class="card card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Bill Type</label>
                            <select class="form-control" id="reportType_em" name="reportType_em">
                                <option value="">Select Bill type</option>
                                <option value="2">General</option>
                                <option value="3">Lab</option>
                                
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Report Type</label>
                            <select class="form-control" id="reportType" name="reportType">
                                <option value="1">Item Group Wise</option>
                                <option value="2">Item Wise</option>
                                
                            </select>
                        </div>
                    </div>
                   
    
                        <div class="form-group col-md-2">
                            <label>Item Group</label>
                            <select id="group_search" name="group_search" class="form-control group_search" >
                                  <option value="">Select Group</option>
                                 
                             </select>
    
                        </div>
    
                        <div class="form-group col-md-2">
                            <label>Item Name</label>
                            <select id="item_search" class="form-control item_search">
                                <option value="">Select Item</option>
    
                            </select>
    
                        </div>
    
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
                            <button class="btn btn-primary search-btn btn-sm">Search</button>
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
                                        <th>Group Name</th>
                                        <th>Date</th>
                                        <th>Qty</th>
                                        <th>Total Amout</th>
                                        <th>Discount Amount</th>
                                        <th>Net Amount</th>
    
    
                                    </tr>
                                </thead>
                                <tbody id="search_filter">
    
    
    
    
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
        $("#visit_lists").dataTable().fnDestroy()
            table = $('#visit_lists').DataTable({
                "columnDefs": [{ visible: false, targets: groupColumn }],
                "order": [[groupColumn, 'asc']],
                "autoWidth": false,
                "dom": 'Blfrtip',
                "destroy" : true,
    
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            'ajax': {
                url: "<?php echo url('/') ?>/get-lab-income-report",
                type: 'POST',
                "data": function(d) {
    
                    d._token= "{{ csrf_token() }}";
                    d.from_date= $('#from_date').val();
                    d.to_date= $('#to_date').val();
                    d.reportType=$("#reportType").val();
                    d.group_search=$("#group_search").val();
                    d.item_search=$("#item_search").val();
    
                }
            },
            drawCallback: function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;
                api.column(groupColumn, { page: 'current' })
                    .data()
                    .each(function (group, i) {
                        if (last !== group) {
                            $(rows)
                                .eq(i)
                                .before('<tr class="group" style="backgrond-color:#CCC;font-weight:bold"><td colspan="5">' + group + '</td></tr>');
    
                            last = group;
                        }
                    });
            },
            "columns": [
                {
                    "data": "name",
                },
                {
                    "data": "billdate",
                },
                {
                    "data":"quantity",className: "text-right"
                },
                {
                    "data": "unit_total",className: "text-right"
                },
                {
                    "data": "discount_amount",className: "text-right"
                },
                {
                    "data": "serviceitemamount",className: "text-right"
                },
    
            ]
        });
    });
    
    
    $('#group_search').on('change', function (e) {
            getItemList();
    });
    
    function getItemList(){
            $('#item_search').empty();
            var groupId=$('#group_search').val();
            $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/getServiceItemList",
                data: {groupId:groupId},
                success: function(datas) {
    
                    console.log(datas);
                    var opt="<option value=''>Select</option>";
                    $.each(datas, function (key, val) {
    
                        opt+="<option value='"+val.id+"' itemamt='"+val.item_amount+"' itemcode='"+val.item_code+"'>"+val.item_name+"</option>";
                    });
    
                    $('#item_search').append(opt).selectpicker('refresh');
                },
            });
        }



        $('#reportType_em').on('change', function (e) {
            getGroupList();
        });

    function getGroupList(){
            $('#group_search').empty();
            var grouptype=$('#reportType_em').val();
            // alert(grouptype);
            $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/getlabItemList",
                data: {grouptype:grouptype},
                success: function(datas) {
    
     console.log(datas);
                    var opt="<option value=''>Select</option>";
                    $.each(datas, function (key, val) {
    
                        opt+="<option value='"+val.id+"' >"+val.group_name+"</option>";
                    });
    
                    $('#group_search').append(opt).selectpicker('refresh');
                },
            });
        }
    
    </script>
    