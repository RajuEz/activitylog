<?php namespace Spatie\Activitylog;

use Illuminate\Config\Repository;
use Illuminate\Auth\Guard;
use Spatie\Activitylog\Handlers\DefaultLaravelHandler;
use Request;
use Config;

class ActivitylogSupervisor
{
    /**
     * @var array logHandlers
     */
    protected $logHandlers = [];

    protected $auth;

    /**
     * Create the logsupervisor using a default Handler
     * Also register Laravels Log Handler if needed.
     *
     * @param Handlers\ActivitylogHandlerInterface $handler
     * @param Repository $config
     * @param Guard $auth
     */
    public function __construct(Handlers\ActivitylogHandlerInterface $handler, Repository $config, Guard $auth)
    {
        $this->logHandlers[] = $handler;
        if ($config->get('activitylog.alsoLogInDefaultLog')) {
            $this->logHandlers[] = new DefaultLaravelHandler();
        }
        $this->auth = $auth;
    }

    /**
     * Log some activity to all registered log handlers.
     *
     * @param $text
     * @param string $userId
     *
     * @return bool
     */
    public function log($text, $userId = '')
    {
        $userId = $this->normalizeUserId($userId);

        $portal_id = null;
        $ipAddress = Request::getClientIp();
        if(Request::route())
            $portal_id = Request::route()->parameters('portal') ? Request::route()->parameters('portal')['portal']->id : null;


        foreach ($this->logHandlers as $logHandler) {
            $logHandler->log($text, $userId, compact('ipAddress'), $portal_id);
        }

        return true;
    }

    /**
     * Clean out old entries in the log.
     *
     * @return bool
     */
    public function cleanLog()
    {
        foreach ($this->logHandlers as $logHandler) {
            $logHandler->cleanLog(Config::get('activitylog.deleteRecordsOlderThanMonths'));
        }

        return true;
    }

    /**
     * Normalize the user id.
     *
     * @param $userId
     *
     * @return int
     */
    public function normalizeUserId($userId)
    {
        if (is_object($userId)) {
            return $userId->id;
        }

        if ($userId == '' && $this->auth->check()) {
            return $this->auth->user()->id;
        }

        return $userId;
    }
}
// version update
