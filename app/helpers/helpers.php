<?php
use Illuminate\Support\Facades\DB;


function changeDateFormate($date,$date_format){
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);
}
function getAfeild($field,$table,$cond)
{

    // $query="SELECT $field FROM $table  $cond";
    // $filldata=DB::select($query);

  $result = DB::table($table)->where($cond)->value($field);

  return $result;
}
function getCount($table,$cond)
{
    $count = DB::table($table)->where($cond)->count();
    return $count ;
}
function GenerateUhid($branchId=0)
{
    if($branchId>0)
    {
        $cond=['branch_id' => $branchId];
        $result=DB::table("uhidno_settings")->where($cond)->get();
        $last_generated_number=$result[0]->last_generated_number;
        if($last_generated_number==0)
        {
            return $result[0]->starting_number;

        }
        else{
            return $result[0]->last_generated_number;
        }


    }
}

function GenerateBillNo($branchId=0)
{
    if($branchId>0)
    {
        $cond=['branch_id' => $branchId];
        $result=DB::table("billno_settings")->where($cond)->where('bill_type',1)->get();
        $last_generated_number=$result[0]->last_generated_number;
        if($last_generated_number==0)
        {
            return $result[0]->starting_number;

        }
        else{
            return $result[0]->last_generated_number;
        }


    }
}

function GenerateReceiptNo($branchId=0)
{
    if($branchId>0)
    {
        $cond=['branch_id' => $branchId,'bill_type'=>2];
        $result=DB::table("billno_settings")->where($cond)->get();
        $last_generated_number=$result[0]->last_generated_number;
        if($last_generated_number==0)
        {
            return $result[0]->starting_number;

        }
        else{
            return $result[0]->last_generated_number;
        }


    }
}

function GetBranchDetails($branchId)
{
    if($branchId>0)
    {
        $cond=['branch_id' => $branchId];
        $result=DB::table("branch_master")->where($cond)->get();
        return $result[0];
    }
}

function getPrintSettings($branchId,$printItem)
{
    if($branchId>0)
    {
        $result=DB::table("print_settings")->where('branch_id',$branchId)->where('print_item',$printItem)->get();
        return $result[0];
    }
}

function GenerateLabNo($branchId=0)
{
    if($branchId>0)
    {
        $cond=['branch_id' => $branchId,'bill_type'=>3];
        $result=DB::table("billno_settings")->where($cond)->get();
        $last_generated_number=$result[0]->last_generated_number;
        if($last_generated_number==0)
        {
            return $result[0]->starting_number;

        }
        else{
            return $result[0]->last_generated_number;
        }


    }
}

function getAfeildByOrder($field,$table,$cond,$id="id",$order="ASC"){

    $result = DB::table($table)->where($cond)->orderBy($id,$order)->value($field);
    return $result;
}

function getAfeild2($field,$table,$cond,$id="id",$order="ASC"){

    $result = DB::connection('mysqlSecondConnection')->table($table)->where($cond)->orderBy($id,$order)->value($field);
    return $result;
}

function checkExists($field,$table,$cond)
{
//     $cond=[[ DB::raw('upper(sub_category_name)'),strtoupper($request->sub_category_name)],['is_deleted',0]];
//     $result = DB::table($table)->where($cond)->value($field);

//   return $result;
}

function LoadCombo($TName,$FiledCodeName,$FieldDescName,$SelectCode,$Cond,$ordby)
{
    $sql="SELECT ". $FiledCodeName. "," .$FieldDescName ." from ". $TName . " ". $Cond." ".$ordby;
    $query = DB::select($sql);

    foreach($query as $row)
        {
            $id= $row->$FiledCodeName;
            $value= $row->$FieldDescName;

            if($id==$SelectCode)
            {
                print("<option value=$id selected=selected>$value</option>");
            }
            else {
                print("<option value=$id >$value</option>");
            }
        }
}
function LoadComboMulti($TName,$FiledCodeName,$FieldDescName,$SelectCode,$Cond,$ordby)
{
    $sql="SELECT ". $FiledCodeName. "," .$FieldDescName ." from ". $TName . " ". $Cond." ".$ordby;
    $query = DB::select($sql);

    foreach($query as $row)
        {
            $id= $row->$FiledCodeName;
            $value= $row->$FieldDescName;

            if(in_array($id,$SelectCode))
            {
                print("<option value=$id selected=selected>$value</option>");
            }
            else {
                print("<option value=$id >$value</option>");
            }
        }
}

function getTable($table,$ordby,$cond)
{
    $result = DB::table($table)->where($cond)->orderByDesc($ordby)->get();
    return $result;
}
function getASingleValue($table,$cond)
{
    $result = DB::table($table)->where($cond)->first();
    return $result;
}
function getASingleValueByorderLimit($table,$cond,$ordby)
{
    $result = DB::table($table)->where($cond)->orderByDesc($ordby)->limit(1)->first();
    return $result;
}
function getSearchValue($table,$cond)
{
    $result = DB::table($table)->where($cond)->get();
    return $result;
}
function zip_file($main_directory="uploads",$zipFileName="",$full_file_path="",$file_name="")
{

    $public_dir=public_path().'/'.$main_directory;
    $zip = new \ZipArchive();
    $dst_file = $full_file_path;

    if ($zip->open($public_dir . '/DST/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
        $zip->addFile(public_path($dst_file), $file_name);
        $zip->close();
        }
        $headers = array(
        'Content-Type' => 'application/octet-stream',
        );
        $filetopath=$public_dir.'/DST/'.$zipFileName;
        if(file_exists($filetopath)){
        $res=  response()->download($filetopath,$zipFileName,$headers);
        $path =url("$main_directory/DST/$zipFileName");
        return $path;
        }
}
