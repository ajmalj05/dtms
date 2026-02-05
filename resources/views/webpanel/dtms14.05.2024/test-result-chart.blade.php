<style>
    table.dataTable {
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

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
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

    th:first-child,
    td:first-child {
        position: sticky;
        left: 0px;
        background-color: #CCC;
    }

    .thheader {
        position: sticky;
        top: 0;
        background-color: #ccc;
        min-width: 40px !important;
    }

    .table-wrapper-scroll-y {
        height: 60vh;
    }
    .testname{
        z-index: 1;
    }
</style>
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">


                <div class="card">
                    <div class="card-body">
                        <input type="hidden" id="patient_id" value="{{ $patient_details->id }}" />
                        <div class="row">
                            <div class="col-md-2">
                                UHID No: {{ isset($patient_details) ? $patient_details->uhidno : 0 }}
                            </div>

                            <div class="col-md-2">
                                Name:
                                {{ isset($patient_details) ? $patient_details->name . $patient_details->last_name : '' }}
                            </div>
                            <div class="col-md-2">
                                Age: @if (isset($patient_details->dob))
                                    {{ \Carbon\Carbon::parse($patient_details->dob)->diff(\Carbon\Carbon::now())->format('%y') . ' yrs' }}
                                @else
                                    {{ '' }}
                                @endif
                            </div>
                            <div class="col-md-2">
                                Gender: @if (isset($patient_details->gender))
                                    @if ($patient_details->gender != '')
                                        @if (str_contains($patient_details->gender, 'm'))
                                            {{ 'Male' }}
                                        @elseif (str_contains($patient_details->gender, 'f'))
                                            {{ 'Female' }}
                                        @elseif (str_contains($patient_details->gender, 'o'))
                                            {
                                            {{ 'Others' }}
                                        @else
                                            {{ 'NA' }}
                                        @endif
                                    @endif
                                @endif
                            </div>


                        </div>



                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="">








            <div class="card">
                <div class="card-header">
                    <div style="padding-inline: inherit;">
                        <span>Outside Lab</span> <span
                            style="background-color: #6967eb; padding-right: 20px;">&nbsp;</span>
                        <span style="padding-left: 10px;">Inside Lab </span> <span
                            style="background-color: #FFB6C1; padding-right: 20px;">&nbsp;</span>
                    </div>
                </div>

                    <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">

                        @if (!empty($testDate))
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="thheader testname">Test Name</th>
                                        @foreach ($testDate as $valueDate)
                                            <th class="thheader" style="width: 90px;">{{ $valueDate }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($testName as $key => $value)
                                        <tr>
                                            <td>{{ $value['testName'] }}</td>
                                            @foreach ($value['value'] as $date => $testResult)
                                                <td
                                                    @if ($testResult['is_outside_lab'] === 1) style="background-color: #6967eb; color: white;"
                                                                         @elseif($testResult['is_outside_lab'] === 0) style="background-color: #FFB6C1;" @endif>
                                                    {{ implode(',', $testResult['result_value']) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            No Test data found
                        @endif
                        <div class="page-page">
                            <div class="row">
                                <div class="col-md-8 col-pagination">
                                    <div class="pagination">
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
