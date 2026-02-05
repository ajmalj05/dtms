<html>
<head>
    <style>
        .common{
            font-family:'Times New Roman', Times, serif;

            font-size: 12px;
        }
        .table_custom
        {
            font-size:12px;font-family:'Times New Roman', Times, serif;
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
            font-size:12px;font-family:'Times New Roman', Times, serif;
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
            font-size: 10px;
            text-align: center;
            padding: 0.8%;
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
.patient_info{
    font-size: 14px;
    font-family: 'Times New Roman', Times, serif;;
}
.p2{
    padding-top: 2% !important;
    font-size: 14px;
    font-family: 'Times New Roman', Times, serif;
    font-weight: 600;
}

.patient_info tr{
    padding-top: 2% !important;
    font-size: 14px;
    font-weight: 600;
}
.f-14
{
    font-size:14px !important;
}

    </style>
    <title>Lab Report</title>

</head>
<body>

    <htmlpageheader name="page-header">
        <div class="common">
            <?php
                if($data['result_print_type']==1){
                    ?>
          <table class="table_custom">
        <tr>
            <td>
                <img width="50%" src="./images/nabh.jpg">
                <br>
            </td>

            <td width="10%">
                &nbsp;
            </td>
            <td>
               <p style="font-size: 11px;"> <b> <span>{{$data['branch_details']->address_line_1}}</span><br>
                   <span style="margin-top: 10%"> {{$data['branch_details']->address_line_2}}.</b> <br> </span>
                   <span> Phone: {{$data['branch_details']->phone}} Email:  {{$data['branch_details']->email}}</span>
            </td>
        </tr>
       </table>
       <br>
       {{$data['branch_details']->tag_line}}
       <hr>

       <div style="text-align: center">
        <h4>LAB REPORT</h4>
      </div>

                    <?php
                }
            ?>


        <table class="table_custom  patient_info page-header">

            <tr class="">

                <td width="60%">

                        <table class="patient_info">
                            <tr>
                                <td>UHID</td>
                                <td> <b>: {{$data['patient_data']->uhidno}}</b></td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>
                                    <?php
                                    $condx=array(array('id',$data['patient_data']->salutation_id) );
                                    $salutation=getAfeild("salutation_name","salutation_master",$condx);
                                    ?>
                                    <b>: {{$salutation}} {{$data['patient_data']->name}}  {{$data['patient_data']->last_name}} </b>
                                </td>
                            </tr>
                            <tr>
                                <td>Doctor Name</td>
                                <td>: {{$data['drName']}}</td>
                            </tr>
                            <tr>




                        </table>

                </td>

                <td width="40%">
                    <table class="patient_info">

                        <tr>
                                <td>
                                    Lab No
                                </td>
                                <td>
                                        : {{$data['lab_no']}}
                                </td>
                        </tr>
                        <tr>
                            <td>Age/Sex</td>
                            <td> :
                                @if(isset($data['patient_data']->dob))
                                {{ \Carbon\Carbon::parse($data['patient_data']->dob)->diff(\Carbon\Carbon::now())->format('%y') . ' yrs' }}
                            @else
                                {{ '' }}
                            @endif /
                            @if(str_contains($data['patient_data']->gender, 'f'))
                            female
                        @elseif (str_contains($data['patient_data']->gender, 'm'))
                            male
                        @endif
                            </td>
                        </tr>
                        <tr>
                                <td>Date</td>
                                <td> :
                                    {{  ($data['testreultsDates']) ?  \Carbon\Carbon::parse($data['testreultsDates']->result_date)->format('d-m-Y h:i a')   :   \Carbon\Carbon::now()->format('d-m-Y h:i a')  }} </p>

                                    {{--       {{  ($data['testreultsDates']) ?  \Carbon\Carbon::parse($data['testreultsDates']->test_date)->format('d-m-Y h:i a')   : \Carbon\Carbon::now()->format('d-m-Y h:i a')   }}
                                         --}}
                                </td>
                        </tr>

                    </table>
                </td>

            </tr>

          </table>

        </div>

          </htmlpageheader>

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




      <div >
        {{-- <div  class="br bb bl"> --}}
            <br/>
      <table class="table_custom" style="border-top:none !important" border="0">


        <thead>
            <tr style="padding-top: 2%" style="border:1px solid #000;border-right:none; border-left:none;">
                <th class="f-14">Test Name</th>
                <th class="f-14">Result</th>
                <th class="f-14">Unit </th>
                <th class="f-14">Reference Value</th>
             </tr>
        </thead>

        <tbody style="padding-top:5px">
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
                            <td colspan="5" class="t-left table-sub-headings"><b>{{$grps['groupName']}}</b></td>
                           </tr>
                        <?php

                    foreach ($grps['test_items'] as $item) {

                        $report_data=$item['report_data'];

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
                            if($expectedOut=="- - -"){
                                $expectedOut=" ";
                            }

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
                            $expectedOut=" ";
                         }

                         if($item['result']==1){
                            $result="Positive";
                        }
                        else if($item['result']==2){
                            $result="Negative";
                         }
                         else if($item['result']==-1){
                            $result=$item['test_result_value'];
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
                              <td class="s5">{{$expectedOut}}
                                <?php
                                        if($report_data){
                                            echo "<br>";
                                            print  nl2br($report_data);
                                        }
                                    ?>
                            </td>

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
        For Jothydev’s Diabetes Hospital &amp; Research Centre <br> <br>
        <table class="table_custom" style="border:none !important" border="0">
            <tr>
                <td align="left">Employee Name Authorised Signatory*</td>
                <td align="right"> Reported By :  {{($data['testreultsDates']) ? $data['testreultsDates']->name :"None"}} </td>
            </tr>
        </table>

    </div>
    <br><br><br><br><br><br><br><br><br>
    </div>

</htmlpagefooter>
</body>

</html>
