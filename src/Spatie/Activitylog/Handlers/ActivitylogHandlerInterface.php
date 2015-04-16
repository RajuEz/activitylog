<?php namespace Spatie\Activitylog\Handlers;

interface ActivitylogHandlerInterface
{
    /**
     * Log some activity.
     *
     * @param string $text
     * @param string $user
     * @param array  $attributes
     *
     * @return boolean
     */
    public function log($text, $user = '', $attributes = [], $portal_id = null);

    /**
     * Clean old log records.
     *
     * @param int $maxAgeInMonths
     *
     * @return boolean
     */
    public function cleanLog($maxAgeInMonths);
}
