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
                                    {{-- getTestNameByMonth(this.value) --}}
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Test Result History</h4>
                        <button type="button" class="btn btn-info btn-sm" onclick="analyzeTestPatterns()" id="btnAnalyzePatterns">
                            <i class="fa fa-magic"></i> Analyze Patterns (AI)
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- AI Insights Container -->
                        <div id="aiInsightContainer" style="display:none; background: linear-gradient(to right, #f8f9fa, #eef2f5); border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e1e4e8; overflow: hidden; margin-bottom: 20px;">
                            <div style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); padding: 12px 20px; color: white; display: flex; align-items: center;">
                                <i class="fa fa-stethoscope" style="font-size: 1.2rem; margin-right: 10px;"></i>
                                <h5 style="margin: 0; font-weight: 600; color: white;">AI Clinical Insights</h5>
                            </div>
                            <div style="padding: 20px;">
                                <div id="aiInsightContent" style="color: #444; font-size: 0.95rem; line-height: 1.6;"></div>
                                <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px; text-align: right;">
                                    <small style="color: #888; font-style: italic;">
                                        <i class="fa fa-robot"></i> AI-generated content. Verify with clinical judgment.
                                    </small>
                                </div>
                            </div>
                        </div>

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    Chart.register(ChartDataLabels);
    Chart.defaults.set('plugins.datalabels', {
        color: '#FE777B'
    });

    // Custom CSS for AI Insights format
    const style = document.createElement('style');
    style.innerHTML = `
        #aiInsightContent ul { list-style-type: none; padding: 0; margin: 0; }
        #aiInsightContent li { 
            position: relative; 
            padding-left: 15px; 
            padding-bottom: 8px; 
            margin-bottom: 8px; 
            border-bottom: 1px solid white; /* The requested white line */
        }
        #aiInsightContent li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #6a11cb;
            font-weight: bold;
        }
        #aiInsightContent li:last-child { border-bottom: none; margin-bottom: 0; }
    `;
    document.head.appendChild(style);

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
        //     {
        //     type: 'line',
        //   //  label: 'Result',
        //     data: response.test_result_value,
        //     borderColor: "#FF0032",
        //     backgroundColor: "#FF0032",
        //     datalabels:{
        //        color: '#000'
        //      }
        //  },
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
    currentPage=page;
    getTestNameByMonth(test, page,1);
}



function getTestNameByMonth(testId,page=currentPage,init=0)
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
                    // color = 'rgb(250, 235, 215)';
                    // if(jsonParse.is_outside_lab == 1) {
                    //     color = 'rgb(105, 103, 235)';
                    // }
                    if(init==1)
                    {
                        if (myChart) {
                        myChart.destroy();
                        }
                    }
                    viewTestGraph(jsonParse);
                    pagination=jsonParse.pagination;
                    var lstpage=pagination.lastPage;
                    generatePaginationButtons(lstpage);
                } else {
                    alert(" Non numeric value cannot display in graph!");
                }
            },
        });
    }

$( document ).ready(function() {
    $("#test_name").select2();

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

<script>
    //jdc 24.09.2024
    function scrollToEnd() {
        const chartBox = document.querySelector('.chartBox');
        chartBox.scrollLeft = chartBox.scrollWidth;
    }

    // Call scrollToEnd after the chart is drawn
    function viewTestGraph(response) {
        const labels = response.test_result_date;
        var length = response.test_result_value.length;
        var widths = parseInt(length) * 100;

        var canvas = document.getElementById("myChart");
        canvas.width = widths;

        const data = {
            labels: labels,
            datasets: [{
                type: 'bar',
                plugins: [ChartDataLabels],
                backgroundColor: response.is_outside_lab,
                borderColor: response.is_outside_lab,
                data: response.test_result_value,
                datalabels: {
                    color: '#000'
                }
            }]
        };

        const config = {
            data: data,
            options: {
                layout: { padding: { top: 25 } },
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        font: { weight: 'bold', size: 14 }
                    }
                },
                responsive: false,
            }
        };

        let chartStatus = Chart.getChart("myChart");
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

        myChart = new Chart(document.getElementById('myChart'), config);

        // Scroll to the right after rendering the chart
        scrollToEnd();
    }
</script>

<script>
    function analyzeTestPatterns() {
        // 1. Get Data from Chart or Global Response
        let testName = $("#test_name option:selected").text().trim();
        let patientAge = "{{ isset($patient_details->dob) ? \Carbon\Carbon::parse($patient_details->dob)->diff(\Carbon\Carbon::now())->format("%y") : "Unknown" }}";
        let patientGender = "{{ isset($patient_details->gender) ? $patient_details->gender : "Unknown" }}";

        if (typeof myChart === "undefined" || !myChart || !myChart.data || !myChart.data.labels) {
            alert("No chart data available to analyze.");
            return;
        }

        let labels = myChart.data.labels; // Dates
        let dataset = myChart.data.datasets[0].data; // Values

        if (dataset.length < 2) {
             alert("Need at least 2 data points for a trend analysis.");
             return;
        }

        let history = [];
        for (let i = 0; i < labels.length; i++) {
            history.push({
                date: labels[i],
                value: dataset[i]
            });
        }

        // 2. Show Loading State
        let btn = $("#btnAnalyzePatterns");
        let originalText = btn.html();
        btn.html("<i class=\"fa fa-spinner fa-spin\"></i> Analyzing...");
        btn.prop("disabled", true);
        $("#aiInsightContainer").hide();

        // 3. API Call
        $.ajax({
            url: "{{ route("ai.analyze-trend") }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || "{{ csrf_token() }}"
            },
            data: {
                test_name: testName,
                history: history,
                age: patientAge,
                gender: patientGender
            },
            success: function(response) {
                if (response.status === "success") {
                    $("#aiInsightContent").html(response.analysis);
                    $("#aiInsightContainer").slideDown();
                    // Scroll to insights
                    $("html, body").animate({
                        scrollTop: $("#aiInsightContainer").offset().top - 100
                    }, 500);
                } else {
                    alert("Analysis Failed: " + response.message);
                }
            },
            error: function(xhr) {
                 alert("Error connecting to AI service.");
                 console.error(xhr);
            },
            complete: function() {
                btn.html(originalText);
                btn.prop("disabled", false);
            }
        });
    }
</script>
