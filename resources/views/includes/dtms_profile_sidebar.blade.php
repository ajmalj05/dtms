<div class="col-xl-3 col-lg-12 col-sm-12">
    <div class="card card-sm">
        <div class="text-center p-3 ">
            <i class="fa fa-pencil fl-right p-left" aria-hidden="true" ondblclick="EditPatients();" data-toggle="tooltip" data-placement="bottom" title="Double click here to edit patient details"></i>

            <i class="fa fa-exchange fl-right" aria-hidden="true" ondblclick="SearchPatients();" data-toggle="tooltip" data-placement="bottom" title="Double click here to search patients"></i>

            <div class="profile-photo">
              

                @if(isset($gallery))
                    
                    <img src="{{ url('/images/'.$gallery->image) }}" id="pro_pic" width="100" class="img-fluid rounded-circle dtms-profile" alt="">
                    
                @else
                    
                    <img src="{{ url('/images/profile/profile.png')}}" id="pro_pic" width="100" class="img-fluid rounded-circle dtms-profile" alt="">
                    
                    
                @endif
              
            </div>
            <p>{{$patient_data->name}}</p>
            
            <table class="table table-profile">
                <tr>
                    <td>UHID No:</td>
                    <td>{{$uhidNo}}</td>
                </tr>
                <tr>
                    <td>Gender:</td>
                    <td>
                        @php
                            if(str_contains($patient_data->gender, 'm') ) {
                                echo "Male";
                            }else if(str_contains($patient_data->gender, 'f')){
                                echo "Female";
                            }else if(str_contains($patient_data->gender, 'o')){
                                echo "Others";
                            }else{
                                echo 'NA';
                            }

                               
                        @endphp
                    </td>
                </tr>
                <tr>
                    <td>DOB:</td>
                    <td>{{$patient_data->dob}}</td>
                </tr>
                <tr>
                    <td>Age:</td>
                    <td>{{ \Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y') }} yrs</td>
                </tr>
                <tr>
                    <td>Mobile No:</td>
                    <td>{{$patient_data->mobile_number}}</td>
                </tr>

            </table>

        </div>
    </div>
</div>

{{-- logout menu not working.... --}}
{{-- <script src="{{asset('/vendor/global/global.min.js')}}"></script> --}}

@include('modals/searchpatient_modal',['title'=>'Search Patient','data'=>'fgh'])


<script language="JavaScript">
    function SearchPatients(){
        
        $('#addModal').modal();
    }

    function EditPatients(){
        
        var pid="<?php echo $patient_data->id ;?>"
        window.location.href = '{{url("patientRegistration")}}?id='+ pid;
    }

   

</script>

