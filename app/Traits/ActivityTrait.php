<?php

namespace App\Traits;

use App\Models\ActivityLog;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Validator;

trait ActivityTrait
{

    public function activity_log($module_name, $activity_name, $activity_message, $activity_status, $user_id)
    {

        $log = new ActivityLog();

        $log->module_name = $module_name;
        $log->activity_name = $activity_name;
        $log->activity_message = $activity_message;
        $log->user_id = $user_id;
        $log->job_status = $activity_status;

        if ($log->save()) {
            return true;
        } else {
            return false;
        }
    }
}
