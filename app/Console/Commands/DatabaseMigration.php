<?php

namespace App\Console\Commands;

use App\Models\Billing\PaymentModeMaster;
use App\Models\Masters\BloodGroupMaster;
use App\Models\Masters\CategoryMaster;
use App\Models\Masters\CitiesMaster;
use App\Models\Masters\ComplicationMaster;
use App\Models\Masters\DepartmentMaster;
use App\Models\Masters\DiagnosisMaster;
use App\Models\Masters\EducationMaster;
use App\Models\Masters\FormEngineQuestions;
use App\Models\Masters\IdProodTypeMaster;
use App\Models\Masters\MedicineMaster;
use App\Models\Masters\MeritalStatusMaster;
use App\Models\Masters\OccupationMaster;
use App\Models\Masters\RelationMaster;
use App\Models\Masters\SalutationMaster;
use App\Models\Masters\SpecialistMaster;
use App\Models\Masters\StateMaster;
use App\Models\Masters\SubComplicationMaster;
use App\Models\Masters\TabletTypeMaster;
use App\Models\Masters\VisitTypeMaster;
use App\Models\Masters\VitalsMaster;
use App\Models\PatientAnswerSheet;
use App\Models\PatientBpStatus;
use App\Models\PatientDietPlan;
use App\Models\PatientDocument;
use App\Models\PatientPep;
use App\Models\PatientPrescriptions;
use App\Models\PatientRegistration;
use App\Models\PatientTarget;
use App\Models\PatientVaccination;
use App\Models\PatientVisits;
use App\Models\PatientVitals;
use App\Models\PatientGallery;
use App\Models\Tele_usual_medicines;
use App\Models\Test_results;

use App\Models\Masters\VaccinationMaster;



use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DatabaseMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old database to new';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->alert('DB Backup Processing at - ' . Carbon::now());
//        $this->_syncBloodGroups();
//        $this->_syncComplicationMaster();
//        $this->_syncDiagnosisMaster();
//        $this->_syncEducationMaster();
//        $this->_syncPaymentModeMaster();
//        $this->_syncSubComplicationMaster();
//        $this->_syncTabletTypeMaster();
//        $this->_syncSalutationMaster();
//        $this->_syncMedicineMaster();
//        $this->_syncDepartmentMaster();
//        $this->_syncOccupationMaster();
//        $this->_syncCategoryMaster();
//        $this->_syncSpecialistMaster();
//        $this->_syncCountry();
//        $this->_syncStates();
//        $this->_syncCities();
//        $this->_syncIdProofType();
//        $this->_syncRelationMaster();
//        $this->_syncMaritalStatusMaster();
//       $this->_syncPatientRegistration();
//        $this->_syncUpdatePatientRegistration();
  //    $this->_syncVisitData();
//       $this->_syncVisitTypeMaster();
      $this->_syncVitalData();
    //  $this->_xtraData();
//       $this->_syncTargetData();
//       $this->_syncPrescriptionData();
//      $this->_syncVaccinationData();
//       $this->_syncTestResultData();
//       $this->_syncBpStatus();
//       $this->_syncPatientDocument();
//       $this->_syncPatientPhotos();
       // $this->_synchold_tele_usual_medicines();
    //  $this->_synchPatientTest();
       // $this->_synchPatientTargets();
      // $this->_synchExternalTest();
//       $this->_synchPatientTest();
//       $this->_syncTeleMedicineQuestions();
//       $this->_syncTeleMedicineQuestions();
//       $this->_syncTeleMedicineAnswers();
//       $this->_syncTeleMedicineAnswersSheet();
//       $this->_syncTeleMedicinePaperTypes();
//       $this->_syncTeleMedicineQuestionMiscellaneous();
   //      $this->_syncQuestionMaster();

    //$this->_synchQuestionAnswer();

 //   $this->_synchzjdc_tele_complications();

// $this->_synch_zjdc_tele_diagnosis();
   // $this->_synch_alerts();


    }

    public function _synch_alerts()
    {
        $iterationLimit = 3000;
        $skip = 0;

        for ($i = 0; $i <= 13; $i++) {
            if($i>0){
                $skip=$i*3000;
            }
            $this->info("Iteration count : " . $i);
            $this->info('Skip Value : ' . $skip);

            $patients = DB::connection('mysqlSecondConnection')->table('students')
            ->select('id','name','Medical_allergy','note','branch_id')
            ->where('name','!=','')
            ->orderBy('id', 'ASC')
            ->skip($skip)
            ->take($iterationLimit)
            ->get();

            $this->info('Total diagnosis : ' . count($patients));
            $newData=[];
            foreach ($patients as $item)
            {
                $alert=$item->Medical_allergy."\n".$item->note;
                $newData[]=[
                    'patient_id'=>$item->id,
                    'alerts'=>$alert,
                    'branch_id'=>$item->branch_id,
                    'display_status'=>1,
                    'created_by'=>1,
                    'is_deleted'=>0

                ];
            }
            if (! empty($newData)) {
                DB::table('patient_alert')->insert($newData);
            }
            $this->info('Total inserted : ' . count($newData));
        }
    }


    public function _synch_zjdc_tele_diagnosis()
    {
        $iterationLimit = 1000;
        $skip = 0;

         for ($i = 0; $i <= 9; $i++) {

            if($i>0){
                $skip=$i*1000;
            }


                $this->info("Iteration count : " . $i);
                $this->info('Skip Value : ' . $skip);

                 $diagnosis = DB::connection('mysqlSecondConnection')->table('zjdc_tele_diagnosis')
                ->orderBy('id', 'ASC')
                ->skip($skip)
                ->take($iterationLimit)
                ->get();

                 $this->info('Total diagnosis : ' . count($diagnosis));
                     $newData=[];
                  foreach ($diagnosis as $item)
                    {
                        if($item->add_date=="0000-00-00"){
                            $add_date="1920-01-01";
                        }
                        else{
                            $add_date=$item->add_date;
                        }

                        $newData[]=[
                            'diagnosis_id'=>$item->diagnosis_id,
                            'diagnosis_year'=>$item->year,
                            'patient_id'=>$item->patient_id,
                            'examined_date'=>$add_date,
                            'icd_diagnosis'=>$item->diagnosis,
                            'icd_code'=>'',
                            'specialist_id'=>0,
                            'branch_id'=>1,
                            'display_status'=>1,
                            'created_by'=>1,
                            'is_deleted'=>0,


                        ];
                    } //end of foreac

                    if (! empty($newData)) {
                        DB::table('patient_diagnosis')->insert($newData);
                    }
                    $this->info('Total inserted : ' . count($newData));



         } // main for loop
    }


    public function _synchzjdc_tele_complications()
    {
        $data = DB::connection('mysqlSecondConnection')->table('zjdc_tele_complications')
        ->orderBy('id', 'ASC')->get();
         $this->info('Total Data : ' . count($data));

         $newData=[];
         foreach ($data as $item) {

            $date=$item->add_date;
            if($date=="0000-00-00") $date="01-01-1970";
            $newData[]=[
                'complication_id'=>$item->complications_id,
                'sub_complication_id'=>$item->sub_complication_id,
                'patient_id'=>$item->patient_id,
                'complication_year'=>$item->year,
                'examined_date'=>$date,
                'icd_diagnosis'=>$item->old_diagnosis,
                'icd_code'=>'',
                'specialist_id'=>0,
                'branch_id'=>1,
                'display_status'=>1,
                'created_by'=>1,
                'is_deleted'=>0,


            ];

         }  //end of foreac

        if (! empty($newData)) {
            DB::table('patient_complication')->insert($newData);
        }
        $this->info('Total inserted : ' . count($newData));

    }


    public function _synchQuestionAnswer()
    {
        $data = DB::connection('mysqlSecondConnection')->table('tele_medicine_answeres')
            ->orderBy('id', 'ASC')->get();
          $this->info('Total answers : ' . count($data));
          $newData=[];
          foreach ($data as $item) {

            for($i=1;$i<=10;$i++)
            {
                $label="";

                switch($i)
                {
                    case 1:   $label=$item->answer_1; break;
                    case 2:   $label=$item->answer_2; break;
                    case 3:   $label=$item->answer_3; break;
                    case 4:   $label=$item->answer_4; break;
                    case 5:   $label=$item->answer_5; break;
                    case 6:   $label=$item->answer_6; break;
                    case 7:   $label=$item->answer_7; break;
                    case 8:   $label=$item->answer_8; break;
                    case 9:   $label=$item->answer_9; break;
                    case 10:  $label=$item->answer_10; break;
                    default:  $label="";
                }
                if($label && $label!="")
                {
                    $newData[]=[
                        'question_id'=>$item->questions_id,
                        'label'=>$label,
                        'branch_id'=>$item->branch_id,
                        'display_status'=>1,
                        'created_by'=>1,
                        'is_deleted'=>0,
                    ];
                }

            } // end of for

        } // end of foreach

        if (! empty($newData)) {
            DB::table('questionnaire_sub_lables')->insert($newData);
        }
        $this->info('Total Medicine Question Miscellaneous : ' . count($newData));

    }

    private function  _syncTeleMedicineQuestionMiscellaneous()
    {
        $iterationLimit = 1000;
        $teleMedicineQuestionMiscellaneousCount = DB::connection('mysqlSecondConnection')->table('tele_medicine_question_miscellaneous')->count();
        $this->info('Total Tele Medicine Question Miscellaneous : ' . $teleMedicineQuestionMiscellaneousCount);
        $iterations = $teleMedicineQuestionMiscellaneousCount / $iterationLimit;
        $skip = 0;
        for ($i = 0; $i <= $iterations; $i++) {
            $this->info("Iteration count : " . $i);
            $this->info('Skip Value : ' . $skip);
            $medicineMiscellaneous = DB::connection('mysqlSecondConnection')->table('tele_medicine_question_miscellaneous')
//                ->orderBy('id', 'ASC')
                ->skip($skip)
                ->take($iterationLimit)
                ->get();
            $this->info('Total Tele Medicine Question Miscellaneous : ' . count($medicineMiscellaneous));
            $newDbTeleMedicineMiscellaneous = [];
            foreach ($medicineMiscellaneous as $answerSheet) {
                $newDbTeleMedicineMiscellaneous[] = [
                    'id' => $answerSheet->id,
                    'telemedicineanswerid' => $answerSheet->telemedicineanswerid,
                    'fage' => $answerSheet->fage,
                    'faged' => $answerSheet->faged,
                    'fdiabetes' => $answerSheet->fdiabetes,
                    'fcad' => $answerSheet->fcad,
                    'fckd' => $answerSheet->fckd,
                    'fcvd' => $answerSheet->fcvd,
                    'famp' => $answerSheet->famp,
                    'fcancer' => $answerSheet->fcancer,
                    'fthyroid' => $answerSheet->fthyroid,
                    'fdisorder' => $answerSheet->fdisorder,
                    'fhtn' => $answerSheet->fhtn,
                    'mage' => $answerSheet->mage,
                    'maged' => $answerSheet->maged,
                    'mdiabetes' => $answerSheet->mdiabetes,
                    'mcad' => $answerSheet->mcad,
                    'mckd' => $answerSheet->mckd,
                    'mcvd' => $answerSheet->mcvd,
                    'mamp' => $answerSheet->mamp,
                    'mcancer' => $answerSheet->mcancer,
                    'mthyroid' => $answerSheet->mthyroid,
                    'mdisorder' => $answerSheet->mdisorder,
                    'mhtn' => $answerSheet->mhtn,
                    'sage' => $answerSheet->sage,
                    'saged' => $answerSheet->saged,
                    'sdiabetes' => $answerSheet->sdiabetes,
                    'scad' => $answerSheet->scad,
                    'sckd' => $answerSheet->sckd,
                    'scvd' => $answerSheet->scvd,
                    'samp' => $answerSheet->samp,
                    'scancer' => $answerSheet->scancer,
                    'sthyroid' => $answerSheet->sthyroid,
                    'sdisorder' => $answerSheet->sdisorder,
                    'shtn' => $answerSheet->shtn,
                    'cage' => $answerSheet->cage,
                    'caged' => $answerSheet->caged,
                    'cdiabetes' => $answerSheet->cdiabetes,
                    'ccad' => $answerSheet->ccad,
                    'cckd' => $answerSheet->cckd,
                    'ccvd' => $answerSheet->ccvd,
                    'camp' => $answerSheet->camp,
                    'ccancer' => $answerSheet->ccancer,
                    'cthyroid' => $answerSheet->cthyroid,
                    'cdisorder' => $answerSheet->cdisorder,
                    'chtn' => $answerSheet->chtn,
                    'vweight' => $answerSheet->vweight,
                    'vheight' => $answerSheet->vheight,
                    'vbmi' => $answerSheet->vbmi,
                    'vcomplexion' => $answerSheet->vcomplexion,
                    'vage' => $answerSheet->vage,
                    'vgender' => $answerSheet->vgender,
                    'vparameter' => $answerSheet->vparameter,
                    'vscr' => $answerSheet->vscr,
                    'vgfr' => $answerSheet->vgfr,
                ];
            }
            if (! empty($newDbTeleMedicineMiscellaneous)) {
                DB::table('old_tele_medicine_question_miscellaneous')->insert($newDbTeleMedicineMiscellaneous);
            }
            $this->info('Total Medicine Question Miscellaneous : ' . count($newDbTeleMedicineMiscellaneous));
            $skip += $iterationLimit;
            $this->info('-----------------------' );

        }
        $this->info('Total Tele Medicine Question Miscellaneous : ' . $teleMedicineQuestionMiscellaneousCount);

    }

    private function  _syncTeleMedicineAnswersSheet()
    {
        $iterationLimit = 1000;
        $answerSheetCount = DB::connection('mysqlSecondConnection')->table('tele_medicine_answersheets')->count();
        $this->info('Total Tele Medicine Answers Sheet : ' . $answerSheetCount);
        $iterations = $answerSheetCount / $iterationLimit;
        $skip = 0;
        for ($i = 0; $i <= $iterations; $i++) {
            $this->info("Iteration count : " . $i);
            $this->info('Skip Value : ' . $skip);
            $medicineAnswerSheet = DB::connection('mysqlSecondConnection')->table('tele_medicine_answersheets')
//                ->orderBy('id', 'ASC')
                ->skip($skip)
                ->take($iterationLimit)
                ->get();
            $this->info('Total Tele Medicine Answers Sheet : ' . count($medicineAnswerSheet));
            $newDbTeleMedicineAnswersSheet = [];
            foreach ($medicineAnswerSheet as $answerSheet) {
                $newDbTeleMedicineAnswersSheet[] = [
                    'id' => $answerSheet->id,
                    'branch_id' => $answerSheet->branch_id,
                    'student_id' => $answerSheet->student_id,
                    'revisit_code' => $answerSheet->revisit_code,
                    'paper_id' => $answerSheet->paper_id,
                    'paper_code' => $answerSheet->paper_code,
                    'questions_id' => $answerSheet->questions_id,
                    'answer_id' => $answerSheet->answer_id,
                    'answer' => $answerSheet->answer,
                    'add_date' => $answerSheet->add_date,
                    'schools_id' => $answerSheet->schools_id,
                    'answerremark' => $answerSheet->answerremark,
                    'telemqmisclastid' => $answerSheet->telemqmisclastid,
                ];
            }
            if (! empty($newDbTeleMedicineAnswersSheet)) {
                DB::table('old_tele_medicine_answersheets')->insert($newDbTeleMedicineAnswersSheet);
            }
            $this->info('Total new City : ' . count($newDbTeleMedicineAnswersSheet));
            $skip += $iterationLimit;
            $this->info('-----------------------' );

        }
        $this->info('Total Tele Medicine Answers Sheet : ' . $answerSheetCount);

    }


    private function  _syncTeleMedicineAnswers()
    {
        $teleMedicineAnswers = DB::connection('mysqlSecondConnection')->table('tele_medicine_answeres')->get();
        $this->info('Total Tele Medicine Answers : ' . count($teleMedicineAnswers));
        $newDbTeleMedicineAnswers = [];
        foreach ($teleMedicineAnswers as $answer) {
            $newDbTeleMedicineAnswers[] = [
                'id' => $answer->id,
                'branch_id' => $answer->branch_id,
                'questions_id' => $answer->questions_id,
                'answer_1' => $answer->answer_1,
                'answer_2' => $answer->answer_2,
                'answer_3' => $answer->answer_3,
                'answer_4' => $answer->answer_4,
                'answer_5' => $answer->answer_5,
                'answer_6' => $answer->answer_6,
                'answer_7' => $answer->answer_7,
                'answer_8' => $answer->answer_8,
                'answer_9' => $answer->answer_9,
                'answer_10' => $answer->answer_10,
                'option_limit' => $answer->option_limit,
                'correct_answere' => $answer->correct_answere,
                'answer_type' => $answer->answer_type,
                'schools_id' => $answer->schools_id,
                'add_date' => $answer->add_date,
                'answer_11' => $answer->answer_11,
                'answer_12' => $answer->answer_12,
            ];
        }
        if (! empty($newDbTeleMedicineAnswers)) {
            DB::table('old_tele_medicine_answeres')->insert($newDbTeleMedicineAnswers);
        }
        $this->info('Total new tele medicine answers : ' . count($newDbTeleMedicineAnswers));

    }

    private function  _syncTeleMedicinePaperTypes()
    {
        $teleMedicinePaperTypes = DB::connection('mysqlSecondConnection')->table('tele_medicine_papertypes')->get();
        $this->info('Total Tele Medicine Questions : ' . count($teleMedicinePaperTypes));
        $newDbTeleMedicinePaperTypes = [];
        foreach ($teleMedicinePaperTypes as $paperTypes) {
            $newDbTeleMedicinePaperTypes[] = [
                'id' => $paperTypes->id,
                'branch_id' => $paperTypes->branch_id,
                'paper_name' => $paperTypes->paper_name,
            ];
        }
        if (! empty($newDbTeleMedicinePaperTypes)) {
            DB::table('old_tele_medicine_papertypes')->insert($newDbTeleMedicinePaperTypes);
        }
        $this->info('Total new tele medicine paper types : ' . count($newDbTeleMedicinePaperTypes));

    }

    private function  _syncTeleMedicineQuestions()
    {
        $teleMedicineQuestions = DB::connection('mysqlSecondConnection')->table('tele_medicine_questions')->get();
        $this->info('Total Tele Medicine Questions : ' . count($teleMedicineQuestions));
        $newDbTeleMedicineQuestions = [];
        foreach ($teleMedicineQuestions as $question) {
            $newDbTeleMedicineQuestions[] = [
                'id' => $question->id,
                'branch_id' => $question->branch_id,
                'question' => $question->question,
                'paper_nameid' => $question->paper_nameid,
                'paper_type' => $question->paper_type,
                'add_date' => $question->add_date,
                'schools_id' => $question->schools_id,
            ];
        }
        if (! empty($newDbTeleMedicineQuestions)) {
            DB::table('old_tele_medicine_questions')->insert($newDbTeleMedicineQuestions);
        }
        $this->info('Total new tele medicine questions : ' . count($newDbTeleMedicineQuestions));

    }




    private function _synchExternalTest()
    {
        $skipValue=3000;
        for($i=0;$i<=13;$i++)  //29
        {
            if($i==0)
            {
                $skip=0;
            }
            else{
                $skip=$skipValue*$i;
            }


            echo "$skip  Started" .Carbon::now()." \n ";

            $data= DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_records')
            ->select('id','flag')
            ->where('flag','=','3')
            ->skip($skip)
            ->take(3000)
            ->orderBy('id','ASC')
            ->get();

            $this->info('Total Data : ' . count($data));

            $update=array();
            $id=array();

            foreach ($data as $item)
            {
                $id[]=[$item->id];
            } // foreach
             $update[] = [['is_outside_lab' => 3]];
             $updated= Test_results::whereIn('visit_id', $id)
             ->update([
                'is_outside_lab' => $item->flag
            ]);

            $this->info("Updated  : $item->id " . " FLAG $item->flag  count = $updated");

            echo "$skip  Ended" .Carbon::now()." \n ";



        }//end of for
    }

    private function _synchPatientTargets()
    {
        $skipValue=100;

        // $testArray=array(
        //     array('name'=>'fbs','id'=>'1'),
        //     array('name'=>'ppbs','id'=>'2'),
        //     array('name'=>'hba1c','id'=>'876'),
        //     array('name'=>'ldl','id'=>'1220'),
        //     array('name'=>'bp','id'=>'1477'),
        // );

        $testArray = collect([
            (object) [
                'name'=>'fbs','id'=>'1','tvalue'=>"100"
            ],
            (object) [
                'name'=>'ppbs','id'=>'2','tvalue'=>"140"
            ],
            (object) [
                'name'=>'hba1c','id'=>'876','tvalue'=>"6.0"
            ],
            (object) [
                'name'=>'ldl','id'=>'1220','tvalue'=>"n"
            ],
            (object) [
                'name'=>'bp','id'=>'1477','tvalue'=>"120/80"
            ]
        ]);


        for($i=0;$i<500;$i++)
        {
          if($i==0)
            {
                $skip=0;
            }
            else{
                $skip=$skipValue*$i;
            }

            echo "$skip  " .Carbon::now()." \n ";

             $data= DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_target_datas')
            ->select('id','branch_id','patient_id','tele_medicinerecord_id','fbs','ppbs','hba1c','ldl','bp','weight')
            ->orWhere(function($query){
                $query->orWhere('fbs', '>', 0);
                $query->orWhere('ppbs', '>', 0);
                $query->orWhere('hba1c', '>', 0);
                $query->orWhere('ldl', '>', 0);
                $query->orWhere('bp', '>', 0);
                $query->orWhere('Weight', '>', 0);
            })
            ->where('tele_medicinerecord_id','>',66)
            ->skip($skip)
            ->take(100)
            ->orderBy('id','ASC')
            ->get();

            $this->info('Total Data : ' . count($data));

            $newData=array();
            $newData2=array();
            foreach ($data as $item)
            {

                $cond=array(
                    array('id',$item->tele_medicinerecord_id),
                 );

                $patientId=getAfeild("patient_id","patient_visits",$cond);

                echo " $item->tele_medicinerecord_id  -  $patientId \n";

                if($patientId>0)
                {
                //for weight insertion
                if($item->weight!="" && $item->weight)
                {
                    $newData[] = [
                        'patient_id'=>$patientId,
                        'visit_id'=>$item->tele_medicinerecord_id,
                        'weight_target'=>0,
                        'weight_present'=>$item->weight,
                    ];
                }

                //for all other test inertion
                foreach($testArray as $test)
                {
                        $testName=$test->name;
                        $testId=$test->id;
                        if($item->$testName !="" && $item->$testName )
                        {
                            $newData2[] = [
                                'patient_id'=>$patientId,
                                'visit_id'=>$item->tele_medicinerecord_id,
                                'test_id'=>$testId,
                                'target_value'=>$test->tvalue,
                                'present_value'=>$item->$testName,
                            ];
                        }
                } // foreach test array

                } // if patient id
            } // foeach item array




            if (! empty($newData)) {
                DB::table('patient_target_details')->insert($newData);

            }
            $this->info(' Weight Data : ' . count($newData));
            ///////////////////////////////////////////////////////
            if (! empty($newData2)) {
                DB::table('patient_targets')->insert($newData2);
            }
            $this->info(' Test Data : ' . count($newData2));

        } // for loop
    }

    private function _synchPatientTest()
    {
        $skipValue=100;
        $migrate_test = DB::table('test_migration')->get();
        for($i=0;$i<=69;$i++)
        {
            if($i==0)
            {
                $skip=0;
            }
            else{
                $skip=$skipValue*$i;
            }
          //  $skip=$skipValue*$i;

            echo "$skip  " .Carbon::now()." \n ";

            $data= DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_records')
            // ->where('id','>','125075')
            // ->where('id','<','250141')
            ->skip($skip)
            ->take(1000)
            ->orderBy('id','ASC')
            ->get();
            $this->info('Total Data : ' . count($data));

            //$newData = [];
            foreach ($data as $item)
            {
                $newData=array();

                foreach ($migrate_test as $test) {

                    $test_icon_id=$test->test_icon_id;
                    $test_name=$test->test_name;
                    $test_name=trim($test_name);
                    $ResultValue=$item->$test_name;
//////////MERGE////////////////////////////////////////////////////////////////////////////////////////////////////
                      /*PSA = PSAC MERGE==============================================*/

                      if($test_icon_id==95)
                      {
                          $ResultValue=$item->PSA;
                          if(!$ResultValue || $ResultValue=="")
                          {
                              $ResultValue=$item->PSAc ;
                          }
                      }
                      /**==insulin_test = insulin MERGE ================================ */
                      else if($test_icon_id==2310)
                      {
                          $ResultValue=$item->insulin_test;
                          if(!$ResultValue || $ResultValue=="")
                          {
                              $ResultValue=$item->insulin ;
                          }
                      }
                      /**=====Lipoprotein_A=ip MERGE=========================================== */
                      else if($test_icon_id==1079)
                      {
                          $ResultValue=$item->Lipoprotein_A;
                          if(!$ResultValue || $ResultValue=="")
                          {
                              $ResultValue=$item->lp ;
                          }
                      }
///////////////////////////END MERGE/////////////////////////////////////////////////////////////////////////////////////////
                    if($ResultValue)
                    {
                        if($item->add_date=="0000-00-00"){
                            $add_date="1920-01-01";
                        }
                        else{
                            $add_date=$item->add_date;
                        }
                        if(!$add_date || $add_date=="") $add_date="1920-01-01";

                        //$is_outside_lab=$item->flag;


                      $newData[] = [
                            'Labno'=>"",
                            'TestNrml'=>"",
                            'ResultValue'=>$ResultValue,
                            'unit'=>'',
                            'created_at'=>$add_date,
                            'TestId'=>$test_icon_id,
                            'PatientId'=>$item->patient_id,
                            'visit_id'=>$item->id,
                            'is_outside_lab'=>$item->flag
                        ];
                    }

                } // migration test item end
                if (! empty($newData)) {
                    Test_results::insert($newData);
                }
                $this->info($item->id  .' Total new patient test : ' . count($newData));


            } // end of tele_med_record




        } // for lopp for auto insert
    }


    private function _synchold_tele_usual_medicines(){

        $skipValue=4000;
        for($i=194;$i<242;$i++)
        {

         if($i==0)
            {
                $skip=0;
            }
            else{
                $skip=$skipValue*$i;
            }

         echo "$skip \n ";
        $data= DB::connection('mysqlSecondConnection')
        ->table('tele_usual_medicines')
        ->skip($skip)
        ->take(4000)
        ->orderBy('id','ASC')
        ->get();
        $this->info('Total Data : ' . count($data));

        $newData = [];
        foreach ($data as $item) {

            if($item->add_date=="0000-00-00"){
                $add_date="1920-01-01";
            }
            else{
                $add_date=$item->add_date;
            }

            $newData[] = [
                'id'=>$item->id,
                'branch_id'=>$item->branch_id,
                'patient_id'=>$item->patient_id,
                'tele_medicinerecord_id'=>$item->tele_medicinerecord_id,
                'revisit_code'=>$item->revisit_code,
                'tablet_type'=>$item->tablet_type,
                'tablet_name'=>$item->tablet_name,
                'tab_id'=>$item->tab_id,
                'qty'=>$item->qty,
                'dose'=>$item->dose,
                'add_date'=>$add_date,
                'schools_id'=>$item->schools_id,
                'remark'=>$item->remark,
                'streg'=>$item->streg,
                'name'=>$item->name,
                'new_datetime'=>$item->new_datetime,

            ];
        }
        if (! empty($newData)) {
            Tele_usual_medicines::insert($newData);
        }
        $this->info('Total new patient document data : ' . count($newData));


        } // for loop
    }

    private function  _syncPatientPhotos()
    {
        $patientPhotos = DB::connection('mysqlSecondConnection')
        ->table('students')
        ->select('id','image')
        ->where('image','!=','')
         ->skip(15000)
         ->take(5000)
        ->orderBy('id','ASC')
        ->get();
        $this->info('Total Documents : ' . count($patientPhotos));

        $newDbPatientDocumentData = [];

        foreach ($patientPhotos as $document) {

            $doc_name="patient_photos/".$document->image;

            $newDbPatientDocumentData[] = [
                'patient_id'=>$document->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_main'=>1,
                'upload_type'=>1,
                'is_deleted'=>0,
                'image'=>$doc_name
            ];

        }
        if (! empty($newDbPatientDocumentData)) {
            PatientGallery::insert($newDbPatientDocumentData);
        }
        $this->info('Total new patient document data : ' . count($newDbPatientDocumentData));

    }
    private function  _syncPatientDocument()
    {
        $patientDocuments = DB::connection('mysqlSecondConnection')
            ->table('documents')
//            ->where('id','>',20046)
             ->skip(2500)
             ->take(6000)
            ->orderBy('id','ASC')
            ->get();
        $this->info('Total Documents : ' . count($patientDocuments));
        $newDbPatientDocumentData = [];
        foreach ($patientDocuments as $document) {

            $doc_name="patient_documents/".$document->docpath;

            $newDbPatientDocumentData[] = [
                'image'=>$doc_name,
                'remarks' => $document->test_status,
                'category_id' =>$document->dtms_test,
                'display_status' => 1,
                'created_by'=>1,
                'branch_id'=>$document->branch_id,
                'is_deleted' => 0,
                'patient_id'=>$document->student_id,
                'created_at' =>$document->dateadded,
                'updated_at' => Carbon::now(),
            ];


        }
        if (! empty($newDbPatientDocumentData)) {
            PatientDocument::insert($newDbPatientDocumentData);
        }
        $this->info('Total new patient document data : ' . count($newDbPatientDocumentData));
    }

    private function  _syncBpStatus()
    {
        $iterationLimit = 500;
        $skip = 0;

        for($inc=0;$inc<=15;$inc++)
        {
            if($inc>0){
                $skip=$inc*500;
            }
            $this->info(" BP Status  : Iteration count : " . $inc);
            $this->info('Skip Value : ' . $skip);

            $bpStatusData = DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_records')
            ->select('id', 'branch_id', 'BP_S_1', 'BP_S_2', 'BP_S_3', 'BP_S_4', 'bp_s_5', 'bp_s_6', 'bp_s_7', 'bp_s_8',
                'BP_D_1', 'BP_D_2', 'BP_D_3', 'BP_D_4', 'pulse_1', 'pulse_2', 'pulse_3', 'pulse_4')
           ->where('id','>',0)
              ->skip($skip)
              ->take($iterationLimit)
            ->orderBy('id','ASC')
            ->get();

            $this->info('Total BP Status : ' . count($bpStatusData));

            $newDbBpStatusData = [];

            foreach ($bpStatusData as $bpStatus) {

                $newDbBpStatusData=array();
                //combination
                /**
                 *      TIME     BPS     BPD   PULSE
                 * 1.   BP_S_4  BP_S_2  BP_D_1 pulse_1
                 * 2.   BP_S_3  BP_S_1  BP_D_2 pulse_2
                 * 3.   bp_s_5  BP_S_3  BP_D_3 pulse_3
                 * 4.   bp_s_7  bp_s_8  BP_D_4 pulse_4
                 */
                $incs=array(1,2,3,4);

                foreach ($incs as $i) {

                    if($i==1){
                        $time=$bpStatus->BP_S_4;
                        $bps=$bpStatus->BP_S_2;
                        $bpd=$bpStatus->BP_D_1;
                        $pulse=$bpStatus->pulse_1;
                    }
                    else if($i==2){
                        $time=$bpStatus->BP_S_3;
                        $bps=$bpStatus->BP_S_1;
                        $bpd=$bpStatus->BP_D_2;
                        $pulse=$bpStatus->pulse_2;
                    }
                    else if($i==3){
                        $time=$bpStatus->bp_s_5;
                        $bps=$bpStatus->BP_S_3;
                        $bpd=$bpStatus->BP_D_3;
                        $pulse=$bpStatus->pulse_3;
                    }
                    else if($i==4){
                        $time=$bpStatus->bp_s_7;
                        $bps=$bpStatus->bp_s_8;
                        $bpd=$bpStatus->BP_D_4;
                        $pulse=$bpStatus->pulse_4;
                    }





                    if($time==0) $time="";
                    if($bps==0) $bps="";
                    if($bpd==0) $bpd="";
                    if($pulse==0) $pulse="";


                    if($time!= "" || $bps!="" || $bpd!="" || $pulse!="")
                    {
                        echo "$bpStatus->id - $time $bps $bpd $pulse \n";
                       // dd();
                        $cond=array(
                            array('id',$bpStatus->id),
                         );

                        $specialistId=getAfeild("specialist_id","patient_visits",$cond);
                        if(!$specialistId || $specialistId=="") $specialistId=0;

                       $newDbBpStatusData[] = [
                            'visit_id'=>$bpStatus->id,
                            'time'=>$time,
                            'bps'=>$bps,
                            'bpd'=>$bpd,
                            'pulse'=>$pulse,
                            'specialist_id'=>$specialistId,
                            'order_no'=>$i,
                            'branch_id' => $bpStatus->branch_id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'display_status'=>1,
                            'created_by'=>1,
                            'is_deleted'=>0
                        ];

                    }
                } // end for bp status aray loop


                if (! empty($newDbBpStatusData)) {
                  //  print_r($newDbBpStatusData);
                    PatientBpStatus::insert($newDbBpStatusData);
                    $this->info('Total new bp status data : ' . count($newDbBpStatusData));
                }


            } // end of foreach

        } // for loop


 }

    private function  _syncQuestionType()
    {
        $questionType = DB::connection('mysqlSecondConnection')->table('tele_medicine_papertypes')->where('branch_id',1)->get();
        $this->info('Total Question Type : ' . count($questionType));
        $newDbQuestionType = [];
        foreach ($questionType as $questionType) {
            $newDbQuestionType[] = [
                'id' => $questionType->id,
                'type_name' => $questionType->paper_name,
            ];
        }
        if (! empty($newDbQuestionType)) {
            DB::table('question_types')->insert($newDbQuestionType);
        }
        $this->info('Total new question type : ' . count($newDbQuestionType));

    }

    private function  _syncQuestionMaster()
    {
        $questionMaster = DB::connection('mysqlSecondConnection')->table('tele_medicine_questions')->get();
        $this->info('Total Question Master : ' . count($questionMaster));
        $newDbQuestionMaster = [];
        foreach ($questionMaster as $question) {
            $newDbQuestionMaster[] = [
                'id' => $question->id,
                'question' => $question->question,
                'order_no' => null,
                'type' => $question->paper_nameid,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => $question->add_date,
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbQuestionMaster)) {
            DB::table('questionnaire_master')->insert($newDbQuestionMaster);
        }
        $this->info('Total new question master : ' . count($newDbQuestionMaster));

    }

    private function  _syncPatientDietHistory()
    {
        // Patient Answer Sheet
        $PatientAnswerSheet = DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_answersheets')
            ->skip(0)
            ->take(1000)
            ->orderBy('id','ASC')
            ->get();
        $this->info('Total Answer Sheet : ' . count($PatientAnswerSheet));
        $newDbPatientAnswerSheet = [];
        foreach ($PatientAnswerSheet as $answerSheet) {
            $newDbPatientAnswerSheet[] = [
                'id' => $answerSheet->id,
                'question_type_id' => 2,
                'patient_id' => $answerSheet->patient_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbPatientAnswerSheet)) {
            $patientAnswerSheetId = PatientAnswerSheet::create($newDbPatientAnswerSheet);
        }
        $this->info('Total new Answer Sheet : ' . count($newDbPatientAnswerSheet));

        //Diet History
        $PatientDietPlan = DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_questions')
            ->skip(0)
            ->take(1000)
            ->orderBy('id','ASC')
            ->get();
        $this->info('Total Patient Diet Plan : ' . count($PatientDietPlan));
        $newDbPatientDietPlan = [];
        foreach ($PatientDietPlan as $diet) {
            $newDbPatientDietPlan[] = [
                'id' => $diet->id,
                'patient_id' => $diet->patient_id,
                'dietplan_id' => $diet->patient_id,
                'dietplan_answer' => $diet->patient_id,
                'notes' => $diet->patient_id,
                'branch_id' => $diet->patient_id,
                'answer_sheet_id' =>$patientAnswerSheetId,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbPatientDietPlan)) {
            PatientDietPlan::insert($newDbPatientDietPlan);
        }
        $this->info('Total new Diet Plan : ' . count($newDbPatientDietPlan));
    }

    private function  _syncPatientPepData()
    {
        // Patient Answer Sheet
        $PatientAnswerSheet = DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_answersheets')
            ->skip(0)
            ->take(1000)
            ->orderBy('id','ASC')
            ->get();
        $this->info('Total Answer Sheet : ' . count($PatientAnswerSheet));
        $newDbPatientAnswerSheet = [];
        foreach ($PatientAnswerSheet as $answerSheet) {
            $newDbPatientAnswerSheet[] = [
                'id' => $answerSheet->id,
                'question_type_id' => 1,
                'patient_id' => $answerSheet->patient_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbPatientAnswerSheet)) {
          $patientAnswerSheetId =  PatientAnswerSheet::Create($newDbPatientAnswerSheet);
        }
        $this->info('Total new Answer Sheet : ' . count($newDbPatientAnswerSheet));

        //PEP
        $PatientPep = DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_questions')
            ->skip(0)
            ->take(1000)
            ->orderBy('id','ASC')
            ->get();
        $this->info('Total Patient PEP : ' . count($PatientPep));
        $newDbPatientPep = [];
        foreach ($PatientPep as $pep) {
            $newDbPatientPep[] = [
                'id' => $pep->id,
                'question_id' => $pep->patient_id,
                'answer' => $pep->patient_id,
                'notes' => $pep->patient_id,
                'patient_id' => $pep->patient_id,
                'branch_id' => $pep->patient_id,
                'answer_sheet_id' => $patientAnswerSheetId,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbPatientPep)) {
            PatientPep::insert($newDbPatientPep);
        }
        $this->info('Total new PEP : ' . count($newDbPatientPep));
    }

    private function  _syncPatientMiscellaneousData()
    {
        // Patient Answer Sheet
        $PatientAnswerSheet = DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_answersheets')
            ->skip(0)
            ->take(1000)
            ->orderBy('id','ASC')
            ->get();
        $this->info('Total Answer Sheet : ' . count($PatientAnswerSheet));
        $newDbPatientAnswerSheet = [];
        foreach ($PatientAnswerSheet as $answerSheet) {
            $newDbPatientAnswerSheet[] = [
                'id' => $answerSheet->id,
                'question_type_id' => 5,
                'patient_id' => $answerSheet->patient_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbPatientAnswerSheet)) {
            $patientAnswerSheetId =  PatientAnswerSheet::Create($newDbPatientAnswerSheet);
        }
        $this->info('Total new Answer Sheet : ' . count($newDbPatientAnswerSheet));

        //Miscellaneous
        $miscellaneous = DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_question_miscellaneous')
            ->skip(0)
            ->take(1000)
            ->orderBy('id','ASC')
            ->get();
        $this->info('Total Miscellaneous : ' . count($miscellaneous));

    }


    private function  _syncVitalData()
    {
        $iterationLimit = 500;
        $skip = 0;
        for($inc=0;$inc<=15;$inc++)
        {
            if($inc>0){
                $skip=$inc*500;
            }
            $this->info(" Vital Status  : Iteration count : " . $inc);
            $this->info('Skip Value : ' . $skip);


            $vitalData = DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_records')
            ->select('id','branch_id','height', 'weight','bmi','chest','waist','bp_s','bp_d','pulse')
            ->skip($skip)
            ->take($iterationLimit)
            ->orderBy('id','ASC')
            ->get();
              $this->info('Total Vital : ' . count($vitalData));

              $newDbVitalData = [];


        foreach ($vitalData as $vitalData) {

            //   dd($vitalData);


   $vital_ids=array(1,2,3,4,5,6,7,8);
   $newDbVitalData=array();
   foreach ($vital_ids as $vid) {

       $vital_value=0;
       if($vid==1) { if($vitalData->height=="" || !$vitalData->height) { $vital_value="";} else $vital_value= $vitalData->height;}
       if($vid==2) { if($vitalData->weight=="" || !$vitalData->weight) { $vital_value="";} else $vital_value= $vitalData->weight;}
       if($vid==3) { if($vitalData->bmi=="" || !$vitalData->bmi) { $vital_value="";} else $vital_value= $vitalData->bmi;}
       if($vid==4) { if($vitalData->chest=="" || !$vitalData->chest) { $vital_value="";} else $vital_value= $vitalData->chest;}
       if($vid==5) { if($vitalData->waist=="" || !$vitalData->waist) { $vital_value="";} else $vital_value= $vitalData->waist;}
       if($vid==6) { if($vitalData->bp_s=="" || !$vitalData->bp_s) { $vital_value="";} else $vital_value= $vitalData->bp_s;}
       if($vid==7) { if($vitalData->bp_d=="" || !$vitalData->bp_d) { $vital_value="";} else $vital_value= $vitalData->bp_d;}
       if($vid==8) { if($vitalData->pulse=="" || !$vitalData->pulse) { $vital_value="";} else $vital_value= $vitalData->pulse;}

       if($vital_value!="" && $vital_value){
       $newDbVitalData[] = [
       'visit_id'=>$vitalData->id,
       'vitals_id'=>$vid,
       'vitals_value'=>$vital_value,
       'branch_id' => $vitalData->branch_id,
       'created_at' => Carbon::now(),
       'updated_at' => Carbon::now(),
       'display_status'=>1,
       'created_by'=>1,
       'is_deleted'=>0
       ];

       } // if


   } // forech vital id
   if (! empty($newDbVitalData)) {
       PatientVitals::insert($newDbVitalData);
   }
   $this->info('Total new vital data : ' . count($newDbVitalData));

    } // end of main loop

        } // forllop





    }

    private function  _syncTargetData()
    {
        $targetData = DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_target_datas')
            ->skip(0)
            ->take(1000)
            ->orderBy('id','ASC')
            ->get();
        $this->info('Total Target : ' . count($targetData));
        $newDbTargetData = [];
        foreach ($targetData as $target) {
            $newDbTargetData[] = [
                'id' => $target->id,
                'patient_id' => $target->patient_id,
                'visit_id' => $target->visit_id,
                'test_id' => $target->visit_id,
                'branch_id' => $target->branch_id,
                'target_value' => $target->target_value,
                'present_value' => $target->target_value,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbTargetData)) {
            PatientTarget::insert($newDbTargetData);
        }
        $this->info('Total new target data : ' . count($newDbTargetData));

    }

    private function  _syncPrescriptionData()
    {
        $prescriptionData = DB::connection('mysqlSecondConnection')
            ->table('tele_usual_medicines')
            ->skip(0)
            ->take(1000)
            ->orderBy('id','ASC')
            ->get();
        $this->info('Total Prescription : ' . count($prescriptionData));
        $newDbPrescriptionData = [];
        foreach ($prescriptionData as $prescription) {
            $newDbPrescriptionData[] = [
                'id' => $prescription->id,
                'visit_id' => $prescription->visit_id,
                'patient_id' => $prescription->patient_id,
                'tablet_type_id' => $prescription->tablet_type,
                'medicine_id' => $prescription->visit_id,
                'dose' => $prescription->dose,
                'remarks' => $prescription->remark,
                'branch_id' => $prescription->branch_id,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbPrescriptionData)) {
            PatientPrescriptions::insert($newDbPrescriptionData);
        }
        $this->info('Total new prescription data : ' . count($newDbPrescriptionData));

    }

    // private function _syncVaccinationData()
    // {
    //     $vaccinationData="SELECT DISTINCT ";
    // }

    private function  _syncVaccinationData()
    {
          $iterationLimit = 1000;


        $skip = 1000;
        for ($i = 12; $i <= 13; $i++)
        {
            $skip=$i*1000;

              $this->info("Iteration count : " . $i);
              $this->info('Skip Value : ' . $skip);

                $vaccinationData = DB::connection('mysqlSecondConnection')
                ->table('tele_medicine_vaccinations')
               ->skip($skip)
                ->take($iterationLimit)
                ->orderBy('id','ASC')
                ->get();
                $this->info('Total Vaccination : ' . count($vaccinationData));
                $newDbVaccinationData = [];

                    foreach ($vaccinationData as $vaccination) {
                        $this->info('Skip Value : ' . $vaccination->id);

                            $vaccine_name=$vaccination->vaccine;


                            $cond=array(array(DB::raw('upper(trim(vaccination_name))'),strtoupper(trim($vaccine_name))));

                            $vaccine_id=getAfeild("id","vaccination_master",$cond);
                            if(!$vaccine_id || $vaccine_id==0)
                            {
                            // insert for

                                $newVaccineData = VaccinationMaster::create([
                                'vaccination_name' => $vaccine_name,
                                'display_status' => 1,
                                'created_by' => 1,
                                'is_deleted'=>0
                                ]);
                                $vaccine_id=$newVaccineData->id;
                                $this->info('vaccine_name : ' . $vaccine_name);
                                $this->info(' vaccine_id : ' . $vaccine_id);
                            }


                            $vdate= $vaccination->datetaken;
                            if($vdate)
                            {
                                if(strpos($vdate, "/") !== false){
                                    //date formate wrong
                                    $date_s=explode("/",$vdate);
                                    if(!$date_s[2] || $date_s[2]==""){
                                        $date_s[2]='2017';
                                    }
                                    else if( $date_s[2]=="20")
                                    {
                                        $date_s[2]="2020";
                                    }
                                    $vdate=$date_s[2].'-'.$date_s[1].'-'.$date_s[0];
                                }
                                else{

                                    $date_s=explode("-",$vdate);
                                    $len=strlen($date_s[2]);
                                    if($len>2)
                                    {
                                        $vdate=$date_s[2].'-'.$date_s[1].'-'.$date_s[0];
                                    }

                                }
                             }
                             else{
                                $vdate=null;
                             }

                             if($vdate=="-1-28"){
                                $vdate="2017-01-28";
                             }
                             if($vdate=="2020-20-09"){
                                $vdate="2020-09-20";
                             }

                            $newDbVaccinationData[] = [
                            // 'id' => $vaccine_id,
                            'patient_id' => $vaccination->patient_id,
                            'vaccination_date' => $vdate,
                            'remarks' => $vaccination->remarks,
                            'branch_id' => $vaccination->branch_id,
                            'vaccination_id' => $vaccine_id,
                            'display_status' => 1,
                            'is_deleted' => 0,
                            'created_by' => 1,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            ];
                    } // forach sql
                    if (! empty($newDbVaccinationData)) {
                    PatientVaccination::insert($newDbVaccinationData);
                    }
                    $this->info('Total new vaccination data : ' . count($newDbVaccinationData));
                    //  $skip += $iterationLimit;
                        $this->info('-----------------------' );
                 } // for loop

    }

    private function  _syncVisitData()
    {
         $iterationLimit = 100;
         $skip = 0;
         //for($i=0;$i<=69;$i++)
        for($i=2;$i<=69;$i++)
        {
            if($i>0){
                $skip=$i*100;
            }

            $this->info("Iteration count : " . $i);
            $this->info('Skip Value : ' . $skip);

            $visitRecords = DB::connection('mysqlSecondConnection')
            ->table('tele_medicine_records')
            ->skip($skip)
            ->take($iterationLimit)
            ->orderBy('id','ASC')
            ->get();
        $this->info('Total Visit Records : ' . count($visitRecords));
        $newDbVisitData = [];

        foreach ($visitRecords as $visit) {

            //  $previousVisitTypeId = PatientVisits::where('patient_id', $visit->patient_id)->select('visit_type_id')->orderBy('id','DESC')->first();

           // $previousVisitTypeId= DB::connection('mysqlSecondConnection')->table('tele_medicine_records')->where('patient_id', $visit->patient_id)->select('visit_type')->orderBy('id','DESC')->first();

           $cond=array(
                   array('patient_id',$visit->patient_id),
                   array('id','<',$visit->id),
              );

          $previousVisitTypeId= getAfeild2("id","tele_medicine_records",$cond,'id','DESC');

             if($previousVisitTypeId=="" || !$previousVisitTypeId) $previousVisitTypeId=0;


              //visit type
              $visit_type=$visit->visit_type;
              $item = array(1, 2, 3, 4,5,6,7,8,9);

                      if (in_array($visit_type, $item))
                      {
                      // echo "Match found";
                      }
                      else{
                          $visit_type=5;
                      }



              $newDbVisitData[] = [
                  'id' => $visit->id,
                  'visit_type_id' => $visit_type,
                  'specialist_id' => $visit->doctor,
                  'visit_date' => $visit->add_date,
                  'notes' => $visit->newremarks,
                  'branch_id' => $visit->branch_id,
                  'display_status' => 1,
                  'is_deleted' => 0,
                  'created_by' => 1,
                  'updated_by' => 1,
                  'patient_id' => $visit->patient_id,
                  'dtms_remarks' => $visit->remarks,
                  'visit_code' => $visit->revisit_code,
                  'is_edited' => 0,
                  'old_visit_type_id' => $previousVisitTypeId,
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now(),
              ];
          } // foreach
          if (! empty($newDbVisitData)) {
              PatientVisits::insert($newDbVisitData);
          }
          $this->info('Total new data inserted : ' . count($newDbVisitData));

        } // for loop






    }


    private function  _syncVisitTypeMaster()
    {
        $visitTypeMaster = DB::connection('mysqlSecondConnection')->table('tele_medicine_visits')->get();
        $this->info('Total Visit Type Master : ' . count($visitTypeMaster));
        $newDbVisitTypeMaster = [];
        foreach ($visitTypeMaster as $visitType) {
            $newDbVisitTypeMaster[] = [
                'id' => $visitType->id,
                'visit_type_name' => $visitType->visit_type,
                'branch_id' => 1,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbVisitTypeMaster)) {
            VisitTypeMaster::insert($newDbVisitTypeMaster);
        }
        $this->info('Total new Relation Master : ' . count($newDbVisitTypeMaster));

    }

    private function  _syncUpdatePatientRegistration()
    {
        $where=array(
            array( 'name','!=',''),
            // array('patient_referred_id','>','0')
        );
        $patientRegistration = DB::connection('pgsql')->table('patient_registration')->limit(10)->orderBy('id', 'ASC')->where($where)->get();
//        dd($patientRegistration);
        $this->info('Total Patient Registered : ' . count($patientRegistration));
        $newDbPatientRegistration = [];
        $i=0;
        foreach ($patientRegistration as $patient) {
//            dd($patient);
            $i++;
            //Update
            $patientHmsRegistration = DB::connection('mysqlSecondConnection')->table('students')->where('id',$patient->id)->first();
//            dd($patientHmsRegistration);
            $newDbUpdatePatientRegistration = [
                'branch_id' => $patientHmsRegistration->branch_id,
                'name' => $patientHmsRegistration->name,
                'prescription_no' => $patientHmsRegistration->prescription_no,
//                'dob' => $dob,
//                'gender' =>$gender,
//                'created_at'=>$regdate,
//                'uhidno' => $patient->streg,
//                'mobile_number'=>$mobileNumber,
//                'whatsapp_number'=>$whastapNo,
                'alternative_number_1_number'=>substr(trim($patientHmsRegistration->morecontacts), 0, 10) ,
                'alternative_number_2_number' => $patientHmsRegistration->contact4,
                'alternative_number_1_name' =>$patientHmsRegistration->f_name,
                'address' => $patientHmsRegistration->address,
                'last_name' => $patientHmsRegistration->last_name,
//                'country_id'=>$countryId,
//                'state_id' => $stateId,
//                'place_id'=>$cityId,
//                'admission_date' =>$ad_date,
                'specialist_id' => $patientHmsRegistration->specialist_id,
                'panel' =>$patientHmsRegistration->panel,
                'streg' => $patientHmsRegistration->streg,
                'email' => $patientHmsRegistration->email1,
//                'email_extension' => $email_ext_id,
                'pincode' => $patientHmsRegistration->pincode,
//                'salutation_id' =>$salutionId,
//                'penal_id' => $patient->penalid,
//                'religion_id' => $regId,
                'bar_code' => $patientHmsRegistration->barcode,
                'id_proof_type' => $patientHmsRegistration->idproff_id,
                'id_proof_number' => $patientHmsRegistration->id_proof,
//                'created_by' => 1,
//                'updated_at' => Carbon::now(),
//                'education' =>$eduId,
//                'occupation' => $occuId,
                'marital_status' => $patientHmsRegistration->material,
                'department_id' => $patientHmsRegistration->department_id,
//                'patient_reference_type_id' => $refId,
                'empanelment_no' => $patientHmsRegistration->empanelld,
                'claim_id' => $patientHmsRegistration->claim,
//                'is_email_verify' => 0,
//                'is_mobile_verify ' =>0,
                'phone_code' => $patientHmsRegistration->phone_code,
                'blood_group_id' => $patientHmsRegistration->newbrgp,
//                'is_api_verify' => 0,
            ];


        }

        if (! empty($newDbUpdatePatientRegistration)) {
            PatientRegistration::update($newDbUpdatePatientRegistration);

        }
//        $this->info('Total new Patients : ' . count($newDbPatientRegistration));

    }

    private function  _syncPatientRegistration()
    {
        $where=array(
            array( 'name','!=',''),
            array('id','>','31988'),
            array('id','<=','32088'),
        );


     //   $patientRegistration = DB::connection('mysqlSecondConnection')->table('students')->limit(100)->orderBy('id', 'ASC')->where($where)->get();
     $patientRegistration = DB::connection('mysqlSecondConnection')->table('students_new')->limit(100)->orderBy('id', 'ASC')->where($where)->get();

        $this->info('Total Patient Registered : ' . count($patientRegistration));
       // exit;
        $newDbPatientRegistration = [];
        $i=0;
         foreach ($patientRegistration as $patient) {

            $i++;
            ///////////////////////////////////////DOB CHECKING
            $dob= $patient->date_brith;
            if($dob){  $dob=str_replace("0000","1920",$dob);
            $dob=str_replace("00","12",$dob);
         }
       //  echo "$patient->id -  $dob \n";
            /////////////////////////////////REGN DATE UPDATION
            $cond=array(
                array('patient_id',$patient->id),
            );
            $regdate= getAfeild2("add_date","tele_medicine_records",$cond,'id');
            if(!$regdate || $regdate=="" || $regdate=="0000-00-00"){

                if($patient->registration_date=="0000-00-00"){
                    $regdate="1920-01-01";
                }
                else{
                    $regdate=$patient->registration_date;
                }

            }

            //gender Update
            $gender= $patient->gender;
            if($gender=="M" || $gender=="m" || $gender=="male" || $gender=="Male"){
                $gender="m";
            }
            else if($gender=="F" || $gender=="f" || $gender=="female" || $gender=="Female"){
                $gender="f";
            }
            else{
                $gender="o";
            }

            /////////MOBILE NUMBER
            $mobile=$patient->f_mobile;
            // $mobile2=$patient->contact4;
            // $mobile3=$patient->;

            $mobileNumber="";

            $mobile_array=preg_split ("/\,/", $mobile);

            if($mobile_array){


            if(is_numeric($mobile_array[0])){
                $mobileNumber=$mobile_array[0];
            }
            else if( strlen($mobile_array[0])==10 || strlen($mobile_array[0])==11 || strlen($mobile_array[0])==12  ){
                $mobileNumber=$mobile_array[0];
            }

            if(strlen($mobile_array[0])>12){
                $mobileNumber=substr($mobile_array[0], 0, 10);
            }

          } // if mobile array
          else if($patient->f_mobile){
            $mobileNumber=$patient->f_mobile;
          }
          else{
            $mobileNumber="123456789";
          }

        //   WAHSTAP NO

            $whastapNo=$patient->alternet_number;
            if(!$whastapNo || $whastapNo=="") $whastapNo=$patient->morecontacts;
            if($whastapNo){   $whastapNo=substr(trim($whastapNo), 0, 10); }


        // country update
        $country= $patient->village;
        if($country){

            $cond=array(
                array(DB::raw('upper(trim(name))'),strtoupper(trim($country))),
             );
            $countryId=getAfeild("id","country_has",$cond);
             if(!$countryId || $countryId==0 ){
                $countryId=101;
             }
        }
        else{
            $countryId=101;
        }

        ////////////////////////////////////STATE DATA
        $state= $patient->state;

 if($state){

            $cond=array(
                array(DB::raw('upper(trim(name))'),strtoupper(trim($state))),
             );
            $stateId=getAfeild("id","states",$cond);
             if(!$stateId || $stateId==0 ){
                $countryId=19;
             }
        }
        else{
            $stateId=19;
        }

///////////////////////////////////////////////////////////////////CITY NAME

$city=$patient->city;
if($city) {
    $cond=array( array(DB::raw('upper(trim(name))'),strtoupper(trim($city))) );
    $cityId=getAfeild("id","cities",$cond);
    if(!$cityId || $cityId==0){
        if($stateId && $countryId){
            //
            $newDbCityData = CitiesMaster::create([
                'branch_id' => 1,
                'name' => $city,
                'state_id' => $stateId,
            ]);
            $cityId=$newDbCityData->id;
            $this->info('CityId : ' . $cityId);
        }
    }
}
else{
    $cityId=0;
}

////////ADMISSION DATE
$admission_date=$patient->admission_date;
if($admission_date){
    $adate=preg_split ("/\ /", $admission_date);
    $ad_date=$adate[0];
    if($ad_date=="0000-00-00"){
        $ad_date=null;
    }
}
/////////////EMAIL

$extension= $patient->email2;
if($extension){

    $cond=array( array(DB::raw('upper(trim(extension))'),strtoupper(trim($extension))), );
    $email_ext_id=getAfeild("id","extension_master",$cond);
     if(!$email_ext_id || $email_ext_id==0 ){
        $email_ext_id=0;
     }
} else{
  $email_ext_id=0;
}

// SALUTAION ID MAPPING

$patient_salutation_id=$patient->patient_salutation_id;
if(is_numeric($patient_salutation_id)){

    $cond=array(
        array('id',$patient_salutation_id),
    );
    $salutaionName= getAfeild2("salutation","patient_salutations",$cond,'id');
    if($salutaionName){
        $cond=array( array(DB::raw('upper(trim(salutation_name))'),strtoupper(trim($salutaionName))) );
        $salutionId=getAfeild("id","salutation_master",$cond);
    }
    else{
        $salutionId=0;
    }
}else{
    $salutionId=0;
}
////////////RELIGION SET UP
$relgn=$patient->religion;
if($relgn){
    $cond=array( array(DB::raw('upper(trim(religion_name))'),strtoupper(trim($relgn))) );
    $regId=getAfeild("id","religion_master",$cond);
    if(!$regId || $regId==0 || $regId=="") $regId=0;
}
else{
  $regId=0;
}

//./////////////////////Education Master
$education=$patient->newedu;
if($education){
    $cond=array( array(DB::raw('upper(trim(education_name))'),strtoupper(trim($education))) );
    $eduId=getAfeild("id","education_master",$cond);
    if(!$eduId || $eduId==0 || $eduId=="") $eduId=0;
}
else{
    $eduId=0;
}

///ocuupation

$occupation=$patient->newoccu;
if($occupation){
    $cond=array( array(DB::raw('upper(trim(occupation_name))'),strtoupper(trim($occupation))) );
    $occuId=getAfeild("id","occupation_master",$cond);
    if(!$occuId || $occuId==0 || $occuId=="") $occuId=0;
}
else{
    $occuId=0;
}

//REFERENACE

$refId= $patient->patient_referred_id;
if($refId){
    $cond=array(
        array('id',$refId),
    );
    $refName= getAfeild2("referred_type","patient_referreds",$cond,'id');
    if($refName){

        $cond=array( array(DB::raw('upper(trim(patient_reference_name))'),strtoupper(trim($refName))) );
        $refId=getAfeild("id","patient_reference_master",$cond);

    }
    else{
        $refId=0;
    }
}
else{
    $refId=0;
}


$bggrp=$patient->newbrgp;
if(!$bggrp || $bggrp=="") $bggrp=0;


            /////////////////////////PREPARE INSERTION
            $newDbPatientRegistration[] = [
                'id' => $patient->id,
                'branch_id' => $patient->branch_id,
                'name' => $patient->name,
                'prescription_no' => $patient->prescription_no,
                'dob' => $dob,
                'gender' =>$gender,
                'created_at'=>$regdate,
                'uhidno' => $patient->streg,
                'mobile_number'=>$mobileNumber,
                'whatsapp_number'=>$whastapNo,
                'alternative_number_1_number'=>substr(trim($patient->morecontacts), 0, 10) ,
                'alternative_number_2_number' => $patient->contact4,
                'alternative_number_1_name' =>$patient->f_name,
                'address' => $patient->address,
                'last_name' => $patient->last_name,
                'country_id'=>$countryId,
                'state_id' => $stateId,
                'place_id'=>$cityId,
                'admission_date' =>$ad_date,
                'specialist_id' => $patient->specialist_id,
                'panel' =>$patient->panel,
                'streg' => $patient->streg,
                'email' => $patient->email1,
                'email_extension' => $email_ext_id,
                'pincode' => $patient->pincode,
                'salutation_id' =>$salutionId,
                'penal_id' => $patient->penalid,
                'religion_id' => $regId,
                'bar_code' => $patient->barcode,
                'id_proof_type' => $patient->idproff_id,
                'id_proof_number' => $patient->id_proof,
                'created_by' => 1,
                'updated_at' => Carbon::now(),
                'education' =>$eduId,
                'occupation' => $occuId,
                'marital_status' => $patient->material,
                'department_id' => 0,
                'patient_reference_type_id' => $refId,
               'empanelment_no' => $patient->empanelld,
               'claim_id' => $patient->claim,
                'is_email_verify' => 0,
                'is_mobile_verify ' =>0,
                'phone_code' => $patient->phone_code,
                'blood_group_id' => $bggrp,
                'is_api_verify' => 0,
            ];



        }
        if (! empty($newDbPatientRegistration)) {
        //   print_r($newDbPatientRegistration);
           PatientRegistration::insert($newDbPatientRegistration);
        }
        $this->info('Total new Patients : ' . count($newDbPatientRegistration));

    }

    private function  _syncMaritalStatusMaster()
    {
        $martialStatusMaster = DB::connection('mysqlSecondConnection')->table('summery_maritals')->where('branch_id',1)->get();
        $this->info('Total Martial Master : ' . count($martialStatusMaster));
        $newDbMartialMaster = [];
        foreach ($martialStatusMaster as $martial) {
            $newDbMartialMaster[] = [
                'id' => $martial->id,
                'merital_status_name' => $martial->data,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbMartialMaster)) {
            DB::table('merital_status_master')->insert($newDbMartialMaster);
        }
        $this->info('Total new Martial Master : ' . count($newDbMartialMaster));

    }

    private function  _syncRelationMaster()
    {
        $relationMaster = DB::connection('mysqlSecondConnection')->table('patient_relations')->where('branch_id',1)->get();
        $this->info('Total Relation Master : ' . count($relationMaster));
        $newDbRelationMasterMaster = [];
        foreach ($relationMaster as $relation) {
            $newDbRelationMasterMaster[] = [
                'id' => $relation->id,
                'relation_name' => $relation->relation,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbRelationMasterMaster)) {
            RelationMaster::insert($newDbRelationMasterMaster);
        }
        $this->info('Total new Relation Master : ' . count($newDbRelationMasterMaster));

    }

    private function  _syncIdProofType()
    {
        $idProofMaster = DB::connection('mysqlSecondConnection')->table('idprooftypes')->where('branch_id',1)->get();
        $this->info('Total ID proof Master : ' . count($idProofMaster));
        $newDbIdProofMaster = [];
        foreach ($idProofMaster as $idProof) {
            $newDbIdProofMaster[] = [
                'id' => $idProof->id,
                'id_proof_name' => $idProof->idproof_type,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbIdProofMaster)) {
            IdProodTypeMaster::insert($newDbIdProofMaster);
        }
        $this->info('Total new Id Proof : ' . count($newDbIdProofMaster));

    }

    private function  _syncCities()
    {
        $iterationLimit = 1000;
        $citiesCount = DB::connection('mysqlSecondConnection')->table('newcities')->count();
        $this->info('Total Cities : ' . $citiesCount);
        $iterations = $citiesCount / $iterationLimit;
        $skip = 0;
        for ($i = 0; $i <= $iterations; $i++) {
            $this->info("Iteration count : " . $i);
            $this->info('Skip Value : ' . $skip);
            $cities = DB::connection('mysqlSecondConnection')->table('newcities')
//                ->orderBy('id', 'ASC')
                ->skip($skip)
                ->take($iterationLimit)
                ->get();
            $this->info('Total City  : ' . count($cities));
            $newDbCityData = [];
            foreach ($cities as $city) {
                $newDbCityData[] = [
//                    'id' => $city->id,
                    'branch_id' => 1,
                    'name' => $city->city_name,
                    'city_state' => null,
                    'state_id' => null,
                    'pincode' => null,
                    'country_code' => $city->country_code,
                    'state_code' => $city->state_code,
                ];
            }
            if (! empty($newDbCityData)) {
                DB::table('cities')->insert($newDbCityData);
            }
            $this->info('Total new City : ' . count($newDbCityData));
            $skip += $iterationLimit;
            $this->info('-----------------------' );

        }
        $this->info('Total Cities : ' . $citiesCount);



    }

    private function  _syncStates()
    {
        $states = DB::connection('mysqlSecondConnection')->table('newstates')->get();
        $this->info('Total State  : ' . count($states));
        $newDbStateData = [];
        foreach ($states as $state) {

            $cond=array(
                'country_code'=>$state->country_code
            );

            $cid=getAfeild("id","country_has",$cond);

            $newDbStateData[] = [
//                'id' => $state->id,
                'branch_id' => 1,
                'name' => $state->state_name,
                'country_id' => $cid,
                'country_code' => $state->country_code,
                'state_code' => $state->state_code,
            ];
        }
        if (! empty($newDbStateData)) {
            DB::table('states')->insert($newDbStateData);
        }
        $this->info('Total new State : ' . count($newDbStateData));
    }
    private function  _syncCountry()
    {
        $countries = DB::connection('mysqlSecondConnection')->table('newcountries')->get();
        $this->info('Total Country  : ' . count($countries));
        $newDbCountryData = [];
        foreach ($countries as $country) {
            $newDbCountryData[] = [
                'name' => $country->country_name,
                'remarks' => null,
                'country_code' => $country->country_code,
                'display_status' => 1,
                'order_no' => null,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbCountryData)) {
            DB::table('country_has')->insert($newDbCountryData);
        }
        $this->info('Total new Country : ' . count($newDbCountryData));
    }

    private function  _syncSpecialistMaster()
    {
        // Specialisation
        $specialisations = DB::connection('mysqlSecondConnection')->table('specialisations')->get();
        $this->info('Total Specialisation : ' . count($specialisations));
        $newDbSpecialisations = [];
        foreach ($specialisations as $item) {
            $newDbSpecialisations[] = [
                'id' => $item->id,
                'branch_id' => $item->branch_id,
                'name' => $item->spname,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbSpecialisations)) {
            DB::table('specialisations')->insert($newDbSpecialisations);
        }
        $this->info('Total new Specialisation : ' . count($newDbSpecialisations));
        $specialistMaster = DB::connection('mysqlSecondConnection')->table('specialists')->get();
        $this->info('Total Specialist Master : ' . count($specialistMaster));
        $newDbSpecialistMaster = [];
        foreach ($specialistMaster as $specialist) {
            $newDbSpecialistMaster[] = [
                'id' => $specialist->id,
                'specialist_name' => $specialist->specialist_name,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'email' => $specialist->demail,
                'user_id' => 0,
                'department_id' => $specialist->department_id,
                'specialisation_id' => $specialist->specialisation_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbSpecialistMaster)) {
            SpecialistMaster::insert($newDbSpecialistMaster);
        }
        $this->info('Total new Specialist : ' . count($newDbSpecialistMaster));

    }

    private function  _syncOccupationMaster()
    {
        $occupationMaster = DB::connection('mysqlSecondConnection')->table('occupations')->where('branch_id',1)->get();
        $this->info('Total Occupation Master : ' . count($occupationMaster));
        $newDbOccupationMaster = [];
        foreach ($occupationMaster as $occupation) {
            $newDbOccupationMaster[] = [
                'id' => $occupation->id,
                'occupation_name' => $occupation->name,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbOccupationMaster)) {
            OccupationMaster::insert($newDbOccupationMaster);
        }
        $this->info('Total new Occupation : ' . count($newDbOccupationMaster));

    }

    private function  _syncCategoryMaster()
    {
        $categoryMaster = DB::connection('mysqlSecondConnection')->table('patient_category')->get();
        $this->info('Total Category Master : ' . count($categoryMaster));
        $newDbCategoryMaster = [];
        foreach ($categoryMaster as $categoryData) {

            $newDbCategoryMaster[] = [
                'id' => $categoryData->slno,
                'category_name' => $categoryData->category,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbCategoryMaster)) {
            CategoryMaster::insert($newDbCategoryMaster);
        }
        $this->info('Total new Category : ' . count($newDbCategoryMaster));
    }

    private function  _syncDepartmentMaster()
    {
        $departmentMaster = DB::connection('mysqlSecondConnection')->table('departments')->get();
        $this->info('Total Department Master : ' . count($departmentMaster));
        $newDbDepartmentMaster = [];
        foreach ($departmentMaster as $department) {
            $newDbDepartmentMaster[] = [
                'id' => $department->id,
                'branch_id' => $department->branch_id,
                'school_id' => 0,
                'department_name' => $department->department_name,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbDepartmentMaster)) {
            DepartmentMaster::insert($newDbDepartmentMaster);
        }
        $this->info('Total new Department : ' . count($newDbDepartmentMaster));

    }

    private function  _syncMedicineMaster()
    {
        $medicineMaster = DB::connection('mysqlSecondConnection')->table('tablets')->get();
        $this->info('Total Medicine Master : ' . count($medicineMaster));
        $newDbMedicineMaster = [];
        foreach ($medicineMaster as $medicine) {
            $newDbMedicineMaster[] = [
                'id' => $medicine->id,
                'tablet_type_id' => $medicine->tab_type,
                'medicine_name' => $medicine->tabletname,
                'route' => null,
                'notes' => null,
                'dose' => null,
                'branch_id' => $medicine->branch_id,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbMedicineMaster)) {
            MedicineMaster::insert($newDbMedicineMaster);
        }
        $this->info('Total new Medicine : ' . count($newDbMedicineMaster));

    }

    private function  _syncSalutationMaster()
    {
        $salutationMaster = DB::connection('mysqlSecondConnection')->table('patient_salutations')->where('branch_id', 1)->get();
        $this->info('Total Salutation Master : ' . count($salutationMaster));
        $newDbSalutationMaster = [];
        foreach ($salutationMaster as $salutation) {
            $newDbSalutationMaster[] = [
                'id' => $salutation->id,
                'salutation_name' => $salutation->salutation,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbSalutationMaster)) {
            SalutationMaster::insert($newDbSalutationMaster);
        }
        $this->info('Total new Salutation : ' . count($newDbSalutationMaster));

    }

    private function  _syncTabletTypeMaster()
    {
        $tabletTypeMaster = DB::connection('mysqlSecondConnection')->table('tablet_types')->get();
        $this->info('Total Tablet Type Master : ' . count($tabletTypeMaster));
        $newDbTabletTypeMaster = [];
        foreach ($tabletTypeMaster as $tabletType) {
            $newDbTabletTypeMaster[] = [
                'id' => $tabletType->id,
                'tablet_type_name' => $tabletType->name,
                'branch_id' => 1,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbTabletTypeMaster)) {
            TabletTypeMaster::insert($newDbTabletTypeMaster);
        }
        $this->info('Total new Tablet Type : ' . count($newDbTabletTypeMaster));

    }

    private function  _syncSubComplicationMaster()
    {
        $subComplicationMaster = DB::connection('mysqlSecondConnection')->table('subcomplications')->get();
        $this->info('Total Sub Complication Master : ' . count($subComplicationMaster));
        $newDbSubComplicationMaster = [];
        foreach ($subComplicationMaster as $subComplication) {
            $newDbSubComplicationMaster[] = [
                'id' => $subComplication->id,
                'complication_id' => $subComplication->complication_id,
                'code' => null,
                'branch_id' => $subComplication->branch_id,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'subcomplication_name' => $subComplication->name
            ];
        }
        if (! empty($newDbSubComplicationMaster)) {
            SubComplicationMaster::insert($newDbSubComplicationMaster);
        }
        $this->info('Total new Sub Complication : ' . count($newDbSubComplicationMaster));

    }

    private function  _syncPaymentModeMaster()
    {
        $paymentModeMaster = DB::connection('mysqlSecondConnection')->table('payment_modes')->get();
        $this->info('Total Payment Mode Master : ' . count($paymentModeMaster));
        $newDbPaymentModeMaster = [];
        foreach ($paymentModeMaster as $paymentMode) {
            $newDbPaymentModeMaster[] = [
                'id' => $paymentMode->id,
                'payment_mode_name' => $paymentMode->pay_mode,
                'branch_id' => 1,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbPaymentModeMaster)) {
            PaymentModeMaster::insert($newDbPaymentModeMaster);
        }
        $this->info('Total new Payment Mode : ' . count($newDbPaymentModeMaster));

    }

    private function  _syncEducationMaster()
    {
        $educationMaster = DB::connection('mysqlSecondConnection')->table('educations')->where('branch_id',1)->get();
        $this->info('Total Education Master : ' . count($educationMaster));
        $newDbEducationMaster = [];
        foreach ($educationMaster as $education) {
            $newDbEducationMaster[] = [
                'education_name' => $education->name,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbEducationMaster)) {
            EducationMaster::insert($newDbEducationMaster);
        }
        $this->info('Total new Education : ' . count($newDbEducationMaster));

    }

    private function  _syncDiagnosisMaster()
    {
        $diagnosisMaster = DB::connection('mysqlSecondConnection')->table('diagnosis_names')->get();
        $this->info('Total diagnosis Master : ' . count($diagnosisMaster));
        $newDbDiagnosisMaster = [];
        foreach ($diagnosisMaster as $diagnosis) {
            $newDbDiagnosisMaster[] = [
                'id' => $diagnosis->id,
                'code' => null,
                'branch_id' => $diagnosis->branch_id,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'diagnosis_name' => $diagnosis->name
            ];
        }
        if (! empty($newDbDiagnosisMaster)) {
            DiagnosisMaster::insert($newDbDiagnosisMaster);
        }
        $this->info('Total new Diagnosis : ' . count($newDbDiagnosisMaster));

    }

    private function  _syncComplicationMaster()
    {
        // Branch
        $branchs = DB::connection('mysqlSecondConnection')->table('branchs')->get();
        $this->info('Total branchs : ' . count($branchs));
        $newDbBranchs = [];
        foreach ($branchs as $item) {
            $newDbBranchs[] = [
                'branch_id' => $item->id,
                'branch_name' => $item->branch_name,
                'branch_code' => $item->branch_code,
            ];
        }
        if (! empty($newDbBranchs)) {
            DB::table('branch_master')->insert($newDbBranchs);
        }
        $this->info('Total new Branchs : ' . count($newDbBranchs));
        $complicationMaster = DB::connection('mysqlSecondConnection')->table('complications')->get();
        $this->info('Total complication Master : ' . count($complicationMaster));
        $newDbComplicationMaster = [];
        foreach ($complicationMaster as $complication) {
            $newDbComplicationMaster[] = [
                'id' => $complication->id,
                'code' => null,
                'branch_id' => $complication->branch_id,
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'complication_name' => $complication->name
            ];
        }
        if (! empty($newDbComplicationMaster)) {
            ComplicationMaster::insert($newDbComplicationMaster);
        }
        $this->info('Total new Complication : ' . count($newDbComplicationMaster));

    }


    private function _syncBloodGroups()
    {
        // Blood Groups
        $bloodGroups = DB::connection('mysqlSecondConnection')->table('blood_groups')->where('branch_id',1)->get();
        $this->info('Total blood groups : ' . count($bloodGroups));
        $newDbBloodGroups = [];
        foreach ($bloodGroups as $item) {
            $newDbBloodGroups[] = [
                'id' => $item->id,
                'blood_group_name' => $item->bloodgroup_name,
                'display_status' => 1,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (! empty($newDbBloodGroups)) {
            BloodGroupMaster::insert($newDbBloodGroups);
        }
        $this->info('Total blood groups inserted : ' . count($newDbBloodGroups));
    }
}
