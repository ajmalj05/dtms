<style>
    .form-group {
   margin-bottom: 0rem !important;
}

.chartMenu {
        width: 100vw;
        height: 40px;
        background: #1A1A1A;
        color: rgba(54, 162, 235, 1);
      }
      .chartMenu p {
        padding: 10px;
        font-size: 20px;
      }
      .chartCard {
        width: 100vw;
        height: calc(100vh - 40px);
        background: rgba(54, 162, 235, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .chartBox {
        width: 100%;
        padding: 20px;
        overflow-x: scroll;
        border-radius: 20px;
        border: solid 3px rgba(54, 162, 235, 1);
        background: white;
      }


      .chartWrapper {
    position: relative;
}

.chartWrapper > canvas {
    position: absolute;
    left: 0;
    top: 0;
    pointer-events:none;
}

.chartAreaWrapper {
    width: 600px;
    overflow-x: scroll;
}
</style>
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

                                <select id="test_name" name="test_name" class="form-control" onchange="getTestNameByMonth(this.value)">
                                    <option  value="" selected>Select a test..</option>
                                    @foreach($test_result_data as $test_result)
                                        <option value="{{$test_result['TestId']}}">{{$test_result['TestName']}}</option>
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
        </div>

        <div class="row" style="">
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Fees Collection</h4>
                    </div> --}}
                    <div class="card-body">
                        <div>
                            <div class="chartBox">
                                <canvas id="myChart" height="300" width="100"></canvas>
                              </div>

                            {{-- <canvas id="myChart" height="300" style="min-width: 1500px"></canvas> --}}
                        </div>





                          {{-- <div class="chartWrapper">
                            <div class="chartAreaWrapper">
                                <canvas id="myChart" height="300" width="1200"></canvas>
                            </div>
                            <canvas id="myChartAxis" height="300" width="0"></canvas>
                        </div> --}}


                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@include('frames/footer');
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
{{-- <script src="{{asset('./js/chart/chart.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/0.5.7/chartjs-plugin-annotation.min.js"></script> --}}

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
<script>

    </script>


<script>




    function viewTestGraph(response){
        $("#loader").hide();
        const labels = response.test_result_date;

        // 110 px for 1 graph
        var length=response.test_result_value.length;
        var widths=parseInt(length)*100;
        console.log(widths);

        var canvas = document.getElementById("myChart");
        canvas.width = widths;






         // setup
    const data = {
      labels:labels,
      datasets: [{
        label: 'Result',
        data:response.test_result_value,
        borderWidth: 1,
        barThickness: 40,
      }]
    };

    // config
    const config = {
      type: 'bar',
      data,
      barThickness: 50,
      maintainAspectRatio: false,
      options: {
        plugins: {
            legend: {
                display: false,
            },

        },
        scales: {
          y: {
            beginAtZero: true
          }
        },
        responsive: false,
      }
    };

    // render init block

    let chartStatus = Chart.getChart("myChart"); // <canvas> id
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

     myChart = new Chart(
      document.getElementById('myChart'),
      config
    );

    // Instantly assign Chart.js version
    const chartVersion = document.getElementById('chartVersion');
    chartVersion.innerText = Chart.version;
    }

</script>

<script>
    function getTestNameByMonth(testId)
    {
        var patientId = $('#patient_id').val();
        $.ajax({
            url: "{{ route('get-test-filter-data') }}",
            type: 'POST',
            data:{
                'testId': testId,
                'patientId': patientId,
            },
            success : function(response) {
                var jsonParse =JSON.parse(response)
                if((jsonParse.status_result) == 1) {
                    // color = 'rgb(250, 235, 215)';
                    // if(jsonParse.is_outside_lab == 1) {
                    //     color = 'rgb(105, 103, 235)';
                    // }
                    viewTestGraph(jsonParse);
                } else {
                    alert(" Non numeric value cannot display in graph!");
                }
            },
        });
    }



</script>
