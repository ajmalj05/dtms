<style>
    table.dataTable
    {
        width: 100% !important;
    }

</style>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    </style>
<div class="content-body">
  <div class="container-fluid pt-2">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-body">
                      <form action=""  method="GET" id="get-search-testname-data">
                      <div class="form-row">
                          <div class="form-group col-md-4">
                              <label>Test Name</label>
                              <input type="text" class="form-control search_by " id="search" name="search" placeholder="Test Name" value="{{ isset($_GET['search'])?base64_decode($_GET['search']):''}}">
                          </div>
                          <div id="crud">
                              <div class="form-group col-md-2 align-items-center justify-content-sm-center">
                                  <br>
                                  <button class="btn btn-square btn-primary btn-sm" type="submit" >
                                     Search
                                  </button>
                              </div>
                          </div>
                      </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
    <div class="row" style="">
      <div class="col-md-12">

        <div class="profile-tab pb-2">
          <div class="custom-tab-1">
            <ul class="nav nav-tabs">
              <li class="nav-item"><a href="#patient-reference" data-toggle="tab" class="nav-link active show">Test
                  Master</a>
              </li>
            </ul>
            <div class="tab-content pt-3">
              <div id="patient-reference" class="tab-pane fade active show">

                <div class="row">
                  <div class="col-xl-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive pt-3">
                          <table id="TestMaster" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Test Name</th>
                                <th>Test Rate</th>
                                <th>Actions</th>
                                <th>Target Default Value</th>
                                <th>DTMS Order No.</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach($test_master_data as $testMaster)
                                <tr>
                                    <td>{{ $testMaster->id }}</td>
                                    <td>{{ $testMaster->TestName }}</td>
                                    <td>{{ number_format((float)$testMaster->TestRate, 2, '.', '') }}</td>
                                    <td>
                                        <label class="form-check-label" for="displayStatus">Show in DTMS</label>
                                        <label class="switch">
                                            <input type="checkbox" name="show_in_dtms" id="show_in_dtms_{{$testMaster->id}}"  onchange="toggleShowInDtmsCheck({{$testMaster->id}},this)" {{ ($testMaster->show_test_in_dtms == 1)?'checked':'' }}>
                                            <span class="slider round"></span>
                                        </label>

                                        <label class="form-check-label" for="displayStatus">Show in Target</label>
                                        <label class="switch">
                                            <input type="checkbox" name="show_in_target" id="show_in_target_{{$testMaster->id}}"  onchange="toggleShowInTargetCheck({{$testMaster->id}},this)" {{ ($testMaster->show_test_in_targets == 1)?'checked':'' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="target_default_value_{{$testMaster->id}}" name="target_default_value" value="{{ $testMaster->target_default_value }}" placeholder=""
                                                {{ ($testMaster->show_test_in_targets == 0) ? 'readonly':'' }}>
                                            <div class="input-group-append">
                                                <span class="input-group-text"  onclick="updateTargetDefaultValue({{$testMaster->id}})"><i class="fa fa-save"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" id="order_value_{{$testMaster->id}}" name="order_value_" value="{{ $testMaster->dtms_order_no }}" placeholder="">
                                            <div class="input-group-append">
                                                <span class="input-group-text"  onclick="updateOrderValue({{$testMaster->id}})"><i class="fa fa-save"></i></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                          </table>
                            <div class="page-page">
                                <div class="row">
                                    <div class="col-md-8 col-pagination">
                                        <div class="pagination">
                                            {!! $test_master_data->withQueryString()->links('vendor.pagination.bootstrap-4',['class' => 'test-master-paginate']) !!}
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

    </div>
  </div>


</div>
</div>

@include('frames/footer');
<script>
    function toggleShowInDtmsCheck(testId,thiss) {
        // var showInDtms = ($(thiss).is(":checked") ==true) ? 1:0;
        var eid="show_in_dtms_"+testId;
        var showInDtms = ($("#"+eid).is(":checked") ==true) ? 1:0;
        $.ajax({
            url: "{{ route('update-test-in-dtms') }}",
            type: 'post',
            data: {
                testId: testId,
                showInDtms: showInDtms,
                token: "{{ csrf_token() }}"
            },
            success: function (response) {
                swal("Done", response.message, "success");
            },
        });
    }
    function toggleShowInTargetCheck(testId,thiss) {
        var eid="show_in_target_"+testId;
        var showInTarget = ($("#"+eid).is(":checked") ==true) ? 1:0;
        $.ajax({
            url: "{{ route('update-test-in-target') }}",
            type: 'post',
            data : {
                testId : testId,
                showInTarget : showInTarget,
                token: "{{ csrf_token() }}"
            },
        });
        var targetval="target_default_value_"+testId;
        if (showInTarget == 1) {
            $('#'+targetval).attr('readonly', false);
        } else {
            $('#'+targetval).attr('readonly', true);
            $('#'+targetval).val('');
        }
    }

    function updateOrderValue(testId)
    {

        var orderno="order_value_"+testId;
        var ordernoValue=$("#"+orderno).val();
        if(ordernoValue>0)
        {
            $.ajax({
                url: "{{ route('update-test-orderno') }}",
                type: 'post',
                data: {
                    testId: testId,
                    dtmsOrderNo: ordernoValue,
                    token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status == 1) {
                        swal("Done", response.message, "success");
                    } else {
                        sweetAlert("Oops...", response.message, "error");

                    }
                },
            })
        }
        else{
            alert("Order No.Must have a valid value")
        }
    }

    function updateTargetDefaultValue(testId) {
        var eid="show_in_target_"+testId;
        var targetval="target_default_value_"+testId;
        var defaultTvalue= $('#'+targetval).val();
        var showInTarget = ($("#"+eid).is(":checked") ==true) ? 1:0;

        if (showInTarget == 1 && '' != defaultTvalue.trim()) {
            $.ajax({
                url: "{{ route('update-target-default-value') }}",
                type: 'post',
                data: {
                    testId: testId,
                    targetDefaultValue: defaultTvalue,
                    token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status == 1) {
                        swal("Done", response.message, "success");
                    } else {
                        sweetAlert("Oops...", response.message, "error");

                    }
                },
            });
        }

    }


    $("#get-search-testname-data").submit(function(e){
        e.preventDefault();
        var data = $("#search").val();
        window.location.href = '{{url("test-master")}}?search='+ window.btoa(data);
    });
</script>
