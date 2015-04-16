<?php

namespace Spatie\Activitylog\Handlers;

use Log;

class DefaultLaravelHandler implements ActivitylogHandlerInterface
{
    /**
     * Log activity in Laravels log handler.
     *
     * @param string $text
     * @param $userId
     * @param array  $attributes
     *
     * @return boolean
     */
    public function log($text, $userId = '', $attributes = [], $portal_id = null)
    {
        $logText = $text;
        $logText .= ($userId != '' ? ' (by user_id '.$userId.')' : '');
        $logText .= (count($attributes)) ? PHP_EOL.print_r($attributes, true) : '';

        Log::info($logText);

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
        //this handler can't clean it's records

        return true;
    }
}
