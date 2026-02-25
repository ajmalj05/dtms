<?php

namespace App\Models\Traits;

use App\Http\Controllers\API\AiChatController;

trait ClearsPatientAiCache
{
    /**
     * Boot the trait to clear AI cache on changes.
     */
    protected static function bootClearsPatientAiCache()
    {
        static::saved(function ($model) {
            self::triggerAiCacheClear($model);
        });

        static::deleted(function ($model) {
            self::triggerAiCacheClear($model);
        });
    }

    private static function triggerAiCacheClear($model)
    {
        $patientId = self::getPatientIdForCache($model);
        if ($patientId) {
            AiChatController::clearPatientAiCache((int) $patientId);
        }
    }

    private static function getPatientIdForCache($model)
    {
        // 1. If the model IS the patient registration itself
        if ($model->getTable() === 'patient_registration') {
            return $model->id;
        }
        
        // 2. Standard relation patient_id
        if (!empty($model->patient_id)) {
            return $model->patient_id;
        }

        // 3. TestResults uses PascalCase PatientId
        if (!empty($model->PatientId)) {
            return $model->PatientId;
        }

        // 4. BpStatus uses visit_id, need to fetch patient_id through visit
        if (!empty($model->visit_id) && class_exists('\App\Models\PatientVisits')) {
            $visit = \App\Models\PatientVisits::find($model->visit_id);
            if ($visit) {
                return $visit->patient_id;
            }
        }

        return null;
    }
}
