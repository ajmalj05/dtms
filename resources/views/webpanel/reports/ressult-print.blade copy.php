<html>
<head>
    <style>
        .common{
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        .table_custom
        {
            font-size:11px;font-family:Verdana, Arial, Helvetica, sans-serif;
            width:100%;
            border-collapse: collapse;

        }
        .border
        {
            border: 1px solid #000;
        }
        .bt{
            border-top: 1px solid #000;
        }
        .bb{
            border-bottom: 1px solid #000;
        }
        .bl{
            border-left: 1px solid #000;
        }
        .br{
            border-right: 1px solid #000;
        }

        .table_custom2{
            font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif;
            width:100%;
            border-collapse: collapse;
            border-top: 1px solid #000;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }
        .s6{
            font-size: 12px;
            padding: 1%;
        }
        .s5{
            font-size: 11px;
            text-align: center;
            padding: 1.2%;
        }
        .t-center{
        text-align: center;
    }
    .t-left
    {
        text-align: left;
    }

    .table-headings{
        background-color: #2592cc;
        color: #fff;
        font-size: 16px;
        font-weight: 800;

    }
    .table-sub-headings{
        /* background-color: #9796d7; */
        font-size: 14px;
        /* color: #fff; */
        font-weight: 800;
        padding-left: 2%;
    }
    @page {
	header: page-header;
	footer: page-footer;
}

    </style>
    <title>Lab Report</title>

</head>
<body>

    <main>

    <div class="common">


       {{-- <table class="table_custom">
        <tr>
            <td>
                <img width="30%" src="./images/company-logo.png">
                <br>
            </td>
            <td width="10%">
                &nbsp;
            </td>
            <td>
                <b> JDC Junction, Mudavanmugal, Konkalam Road,<br>
                    Trivandrum, Kerala, India, Pin. 695032.</b> <br>
                    Phone: 0471-2356200, 9846040055, Email : info@jothydev.net
            </td>
        </tr>
       </table>
       <br>
       (A Project of Living Longer Life Care Pvt. Ltd)
       <hr>

       <div style="text-align: center">
        <h4>LAB REPORT</h4>
      </div> --}}



      <table class="table_custom border">

        <tr class="border">

            <td>

                    <table>
                        <tr>
                            <td>
                                <p>  Patient Name </p>
                                <p>  Age </p>
                                <p>  Gender </p>
                                {{-- <p> Contact Number </p>
                                <p> Address </p> --}}
                                <p>Result Date & Time </p>
                                <p>Test Date & Time</p>

                            </td>
                            <td>
                                <p> :  {{$data['patient_data']->name}} </p>
                                <p> :
                                    @if(isset($data['patient_data']->dob))
                                    {{ \Carbon\Carbon::parse($data['patient_data']->dob)->diff(\Carbon\Carbon::now())->format('%y') . ' yrs' }}
                                @else
                                    {{ '' }}
                                @endif
                                </p>
                                <p>   :
                                    @if(str_contains($data['patient_data']->gender, 'f'))
                                    female
                                @elseif (str_contains($data['patient_data']->gender, 'm'))
                                    male
                                @endif
                             </p>
                                {{-- <p>  :  {{$data['patient_data']->mobile_number}}</p>

                                <p> :   {{$data['patient_data']->address}} </p> --}}
                                <p> :

                                    {{  ($data['testreultsDates']) ?  \Carbon\Carbon::parse($data['testreultsDates']->result_date)->format('d-m-Y h:i a')   :   \Carbon\Carbon::now()->format('d-m-Y h:i a')  }}
                                   </p>
                                <p> :
                                    {{  ($data['testreultsDates']) ?  \Carbon\Carbon::parse($data['testreultsDates']->test_date)->format('d-m-Y h:i a')   : \Carbon\Carbon::now()->format('d-m-Y h:i a')   }}
                               </p>
                            </td>
                        </tr>
                    </table>

            </td>

            <td width="20%">
                <span class="s6"> UHID NO    <b>{{$data['patient_data']->uhidno}} </b></span>
            </td>

        </tr>

      </table>


      <div  class="br bb bl">
      <table class="table_custom" style="border-top:none !important" border="0">


        <thead>
            <tr>
                <th>Test Name</th>
                <th>Result</th>
                <th>Measure Unit </th>
                <th>Normal Value</th>
             </tr>
        </thead>

        <tbody>
            <?php
                  foreach ($data['test_results'] as $key) {

                    if(in_array($key['depid'], $data['selectedDepartments']))
                    { // if selected

                    ?>

                     <tr>
                        <td colspan="5" class="t-center table-headings">{{$key['depName']}}</td>
                    </tr>
                    <?php
                      foreach ($key['groups'] as $grps)
                      {

                        if(in_array($grps['groupId'], $data['selectedGroups']))
                        {

                        ?>
                         <tr>
                            <td colspan="5" class="t-left table-sub-headings">{{$grps['groupName']}}</td>
                           </tr>
                        <?php

                    foreach ($grps['test_items'] as $item) {

                       $test_id=$item['test_id'];

                       $expectedOut="";
                       $re_type=$item['result_type'];

                        $result="";

                       if($re_type==1)
                       {
                         $cond=array(array('id',$item['colour_id']) );
                         $expectedOut=getAfeild("color_name","test_colours",$cond);

                         $cond=array(array('id',$item['result']) );
                         $result=getAfeild("color_name","test_colours",$cond);

                       }
                       else  if($re_type==4)
                       {
                         $cond=array(array('id',$item['clarity_id']) );
                         $expectedOut=getAfeild("clarity_name","test_clarity",$cond);

                         $cond=array(array('id',$item['result']) );
                         $result=getAfeild("clarity_name","test_clarity",$cond);

                       }
                       else if($re_type==2)
                       {
                           $expectedOut=$item['from_range']. " - ".$item['to_range'];
                           $result=$item['result'];
                       }
                       else if($re_type==3)
                       {
                        if($item['positive_negative']==1){
                            $expectedOut="Positive";
                        }
                        else if($item['positive_negative']==2){
                            $expectedOut="Negative";
                         }
                         else if($item['positive_negative']==3){
                            $expectedOut="Normal";
                         }
                         else{
                            $expectedOut="---";
                         }

                         if($item['result']==1){
                            $result="Positive";
                        }
                        else if($item['result']==2){
                            $result="Negative";
                         }
                         else{
                            $result="Normal";
                         }

                       }

                       ?>
                             <tr>
                               <td  class="s5" align="left" style="padding-left:4%"><b>{{$item['test_name']}}</b>
                                <?php
                                    if($item['test_method']!="")
                                    {
                                        ?>
                                        <br>&nbsp;&nbsp;&nbsp;Method :  {{$item['test_method']}}
                                        <?php
                                    }
                                ?>
                            </td>
                               <td class="s5">

                                {{$result}}

                          </td>
                               <td  class="s5">{{$item['unit']}}</td>
                              <td class="s5">{{$expectedOut}}</td>

                           </tr>

                       <?php

                    }  //item

                   }   //if inisde selcted grps
                       }   //grp

                    } // end if inside depts
                  }//end foreach result
            ?>
        </tbody>

      </table>

    </div>






</main>
<htmlpagefooter name="page-footer">
    <div class="" style="font-size: 11px;height: 100px;">
        <div style="padding: 1%">
        For Jothydev’s Diabetes Hospital &amp; Research Centre
        <table class="table_custom" style="border:none !important" border="0">
            <tr>
                <td align="left">Employee Name Authorised Signatory*</td>
                <td align="right"> Reported By :  {{($data['testreultsDates']) ? $data['testreultsDates']->name :"None"}} </td>
            </tr>
        </table>

    </div>
    <br><br><br><br><br><br><br>
    </div>

</htmlpagefooter>
</body>

</html>
