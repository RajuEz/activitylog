<?php

namespace Spatie\Activitylog\Handlers;

use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class EloquentHandler implements ActivitylogHandlerInterface
{
    /**
     * Log activity in an Eloquent model.
     *
     * @param string $text
     * @param $userId
     * @param array  $attributes
     *
     * @return boolean
     */
    public function log($text, $userId = '', $attributes = [], $portal_id = null)
    {
        Activity::create(
            [
                'text'       => $text,
                'user_id'    => ($userId == '' ? null : $userId),
                'ip_address' => $attributes['ipAddress'],
                '$portal_id' => ($portal_id == null ? null : $portal_id) ,
            ]
        );

        return true;
    }

    /**
     * Clean old log records.
     *
     * @param int $maxAgeInMonths
     *
     * @return boolean
     */
    public function cleanLog($maxAgeInMonths)
    {
        $minimumDate = Carbon::now()->subMonths($maxAgeInMonths);
        Activity::where('created_at', '<=', $minimumDate)->delete();

        return true;
    }
}
