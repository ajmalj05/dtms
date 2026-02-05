<?php

/**
 * Patient Constants
 *
 */

namespace App\Http\Constants;

class PatientConstant
{
    const PATIENT_STATUS_ACTIVE = 0;
    const PATIENT_STATUS_INACTIVE = 1;
    const PATIENT_STATUS_EXPIRED = 2;

    const PATIENT_STATUS = [
        self::PATIENT_STATUS_ACTIVE => 'Active',
        self::PATIENT_STATUS_INACTIVE => 'Inactive',
        self::PATIENT_STATUS_EXPIRED => 'Expired',
    ];



}

