<style>
    .form-group {
   margin-bottom: 0rem !important;
}
.chartBox {
        width: 100%;
        padding: 20px;
        overflow-x: scroll;
        border-radius: 20px;
        border: solid 3px rgba(54, 162, 235, 1);
        background: white;
      }

</style>
<form name="frm" action="" method="POST">
    @csrf
<div class="content-body" >
    <div class="container-fluid" >


        <div class="row">
            <div class="col-md-12">


                <div class="card">
                    <div class="card-body">
                        <input type="hidden" id="patient_id" value="{{ $patient_details->id}}"/>
                        <div class="row">
                            <div class="form-group col-md-2">
{{--                                <label></label>--}}
                                <p>UHID No: {{ isset($patient_details)?$patient_details->uhidno :0}}</p>
                            </div>

                            <div class="form-group col-md-2">
{{--                                <label></label>--}}
                                <p>Name: {{ isset($patient_details)?$patient_details->name . $patient_details->last_name :''}}</p>
                            </div>

                            <div class="form-group col-md-2">
                                <p>Age:  @if(isset($patient_details->dob))
                                        {{ \Carbon\Carbon::parse($patient_details->dob)->diff(\Carbon\Carbon::now())->format('%y') . ' yrs' }}
                                    @else
                                        {{ '' }}
                                    @endif</p>
                            </div>

                            <div class="form-group col-md-2">
                                <p>Gender: @if (isset($patient_details->gender))
                                        @if($patient_details->gender != '')
                                            @if(str_contains($patient_details->gender, 'm'))
                                                {{ 'Male' }}
                                            @elseif (str_contains($patient_details->gender, 'f'))
                                                {{ 'Female' }}
                                            @elseif (str_contains($patient_details->gender, 'o')){
                                            {{ 'Others' }}
                                            @else
                                                {{ 'NA' }}
                                            @endif
                                        @endif
                                    @endif</p>
                            </div>

                            <div class="form-group col-md-2">

                                <select id="test_name" name="test_name" class="form-control" onchange="this.form.submit()">
                                    {{-- getTestNameByMonth(this.value --}}
                                    <option  value="" selected>Select a test..</option>
                                    @foreach($test_result_data as $test_result)
                                    <?php
                                    if($selected_test==$test_result['TestId'])
                                    {
                                        $sel="selected";
                                    }
                                    else{
                                       $sel="";
                                    }

                                    $t_name=$test_result['dtms_code'];
                                            if($t_name=="" || !$t_name)
                                            {
                                                $t_name=$test_result['TestName'];
                                            }
                                         //   $t_name.=" - ".$test_result['chart_order_no'];
                                    ?>
                                        <option value="{{$test_result['TestId']}}"  {{$sel}}> {{$t_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>



                    </div>
                </div>
            </div>
        </div>
        <div style="padding-inline: inherit;">
            <span>Outside Lab</span> <span style="background-color: #6967eb; padding-right: 20px;">&nbsp;</span>
            <span style="padding-left: 10px;">Inside Lab </span> <span style="background-color: #FFB6C1; padding-right: 20px;">&nbsp;</span>
            <span style="padding-left: 10px;">SMBG</span> <span style="background-color: #60D05B; padding-right: 20px;">&nbsp;</span>
        </div>

        <div class="row" style="">
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Fees Collection</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="chartBox">
                            <canvas id="myChart" height="300" width="100"></canvas>
                        </div>
                        <div id="pagination-container" class="d-flex justify-content-end"></div>
                    </div>

                </div>
            </div>
        </div>


    </div>
</div>

</form>
@include('frames/footer');
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script src="{{asset('./js/chart/chart.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0-rc"></script>


<script>
Chart.register(ChartDataLabels);
Chart.defaults.set('plugins.datalabels', {
color: '#FE777B'
});

    function viewTestGraph(response){

        const labels = response.test_result_date;

        var length=response.test_result_value.length;
        var widths=parseInt(length)*100;
        //console.log(widths);

        var canvas = document.getElementById("myChart");
        canvas.width = widths;

        // console.log(response);

    const data = {
        labels: labels,
        datasets: [

            {
            type: 'bar',
            plugins: [ChartDataLabels],
           // label: '',
            backgroundColor: response.is_outside_lab,
            borderColor: response.is_outside_lab,
            // data: [0, 10, 5, 2, 20, 30, 45],
            data: response.test_result_value,
            datalabels: {
               color: '#000'
             }
          }

        ]
    };

    const config = {
      data: data,
      barThickness: 40,
      maintainAspectRatio: false,
        options: {
            layout: {
    padding: {
      top: 25
    }
  },

            plugins: {
                legend: {
                 display: false
             },
                datalabels: { // This code is used to display data values
                anchor: 'end',
                align: 'top',

                labels: {

                },
                font: {
                    weight: 'bold',
                    size: 14
                }
            }
    },

            responsive: false,



        },
    };



        // JS - Destroy exiting Chart Instance to reuse <canvas> element
        let chartStatus = Chart.getChart("myChart"); // <canvas> id
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

         myChart = new Chart(
           document.getElementById('myChart'),
         config
       );
    }

</script>

<script>
    var currentPage = 1;

    function goToPage(page) {
        var test=$("#test_name").val();
        getTestNameByMonth(test, page);
    }

    function getTestNameByMonth(testId,page=currentPage)
    {
        var patientId = $('#patient_id').val();
        $.ajax({
            url: "{{ route('get-test-filter-data') }}",
            type: 'POST',
            data:{
                'testId': testId,
                'patientId': patientId,
                'page': page,
            },
            success : function(response) {
                 var jsonParse =JSON.parse(response)
                if((jsonParse.status_result) == 1) {
                    viewTestGraph(jsonParse);

                    pagination=jsonParse.pagination;
                    generatePaginationButtons(5);
                } else {
                    alert(" Non numeric value cannot display in graph!");
                }
            },
        });
    }

    $( document ).ready(function() {
        var test=$("#test_name").val();
        if(test>0)
        {
            getTestNameByMonth(test);
        }
});



function generatePaginationButtons(totalPages) {
    var paginationHtml = '';
    for (var i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            paginationHtml += '<div class="pl-2"><button type="button" class="btn btn-success btn-sm active">' + i + '</button></div>';
        } else {
            paginationHtml += '<div class="pl-2"><button  type="button"  class="btn btn-primary btn-sm" onclick="goToPage(' + i + ')">' + i + '</button></div>';
        }
    }
    $('#pagination-container').html(paginationHtml); // Replace 'pagination-container' with the appropriate ID
}
</script>
