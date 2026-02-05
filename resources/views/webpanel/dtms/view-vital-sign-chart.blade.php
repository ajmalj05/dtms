<style>
    .form-group {
   margin-bottom: 0rem !important;
}
</style>
<div class="content-body" >
    <div class="container-fluid" >


        <div class="row">
            <div class="col-md-12">


                <div class="card">
                    <div class="card-body">
                        <input type="hidden" id="patient_id" value="{{ $patient_details->id}}"/>
                        <div class="form-row">
                            <div class="form-group col-md-2">
{{--                                <label></label>--}}
                                <p>UHID No: {{ isset($patient_details)?$patient_details->uhidno :0}}</p>
                            </div>

                            <div class="form-group col-md-3">
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

                                <select id="vital_name" name="vital_name" class="form-control" onchange="getVitalNameByMonth(this.value)">
                                    <option  value="" selected>Select Vital Sign</option>
                                    @foreach($vital_data as $vital)
                                        <option value="{{$vital['vitals_id']}}">{{$vital['vital_name']}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>



                    </div>
                </div>
            </div>
        </div>
{{--        <div style="padding-inline: inherit;">--}}
{{--            <span>Outside Lab</span> <span style="background-color: #6967eb; padding-right: 20px;">&nbsp;</span>--}}
{{--            <span style="padding-left: 10px;">Inside Lab </span> <span style="background-color: #FFB6C1; padding-right: 20px;">&nbsp;</span>--}}
{{--        </div>--}}

        <div class="row" style="">
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Fees Collection</h4>
                    </div> --}}
                    <div class="card-body">
                        <div style="width: 100%;height: 400px;">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@include('frames/footer');
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function viewVitalGraph(response){

        const labels = response.vital_result_date;
        // console.log(response);

    const data = {
        labels: labels,
        datasets: [{
            label: '',
            backgroundColor: response.is_outside_lab,
            borderColor: response.is_outside_lab,
            // data: [0, 10, 5, 2, 20, 30, 45],
            data: response.vital_result_value,
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    };

//         var  config = {
//             type: 'bar',
//             data:  {
//                 labels:
// ["January", "February", "March", "April", "May", "June"],
//                 datasets: [{
//                     label: 'My First dataset',
//                     backgroundColor: 'rgb(255, 99, 132)',
//                     borderColor: 'rgb(255, 99, 132)',
//                     data: response.ResultValue,
//                 }]
//             },
//             options: {
//                 scales: {
//                     y: {
//                         beginAtZero: true
//                     }
//                 }
//             },
//         };

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

{{--<script>--}}
{{--    const labels = [--}}
{{--        'January',--}}
{{--        'February',--}}
{{--        'March',--}}
{{--        'April',--}}
{{--        'May',--}}
{{--        'June',--}}
{{--    ];--}}

{{--    const data = {--}}
{{--        labels: labels,--}}
{{--        datasets: [{--}}
{{--            label: 'My First dataset',--}}
{{--            backgroundColor: 'rgb(255, 99, 132)',--}}
{{--            borderColor: 'rgb(255, 99, 132)',--}}
{{--            data: [0, 10, 5, 2, 20, 30, 45],--}}
{{--        }]--}}
{{--    };--}}

{{--    const config = {--}}
{{--        type: 'bar',--}}
{{--        data: data,--}}
{{--        options: {--}}
{{--            scales: {--}}
{{--                y: {--}}
{{--                    beginAtZero: true--}}
{{--                }--}}
{{--            }--}}
{{--        },--}}
{{--    };--}}
{{--</script>--}}
{{--<script>--}}

{{--</script>--}}
<script>
    function getVitalNameByMonth(VitalId)
    {
        var patientId = $('#patient_id').val();
        $.ajax({
            url: "{{ route('get-vital-filter-data') }}",
            type: 'POST',
            data:{
                'VitalId': VitalId,
                'patientId': patientId,
            },
            success : function(response) {
                var jsonParse =JSON.parse(response)
                if((jsonParse.status_result) == 1) {
                    // color = 'rgb(250, 235, 215)';
                    // if(jsonParse.is_outside_lab == 1) {
                    //     color = 'rgb(105, 103, 235)';
                    // }
                    viewVitalGraph(jsonParse);
                } else {
                    alert(" Non numeric value cannot display in graph!");
                }
            },
        });
    }
</script>
