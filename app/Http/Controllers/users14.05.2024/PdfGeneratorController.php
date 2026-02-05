<?php

namespace App\Http\Controllers\users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\PatientRegistration;
use App\Models\PatientCategory;
use App\Models\PatientSubCategory;
use App\Models\PatientGallery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use MPDF;
class PdfGeneratorController extends Controller

{

    public function __construct()
    {

    }


    public function idcard($id)
    {

        $patient_data=PatientRegistration::where('patient_registration.id',$id)->leftjoin('extension_master','extension_master.id','=','patient_registration.email_extension')->get()->toArray();
    //   print_r(json_encode($patient_data));exit;
        $pdf = MPDF::loadView('webpanel.pdf.idcard',[],['patient_data' => $patient_data],[
            'format'=>[150, 80]
        ]);

        return $pdf->stream('document.pdf',);
    }
}

?>
