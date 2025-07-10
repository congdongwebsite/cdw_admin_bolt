<?php
defined('ABSPATH') || exit;
class functionLock
{
    public function __construct()
    {
    }
    public function lock()
    {
        global $userCurrent, $CDWFunc;
        return $CDWFunc->updateUserOption($userCurrent->ID, 'lock', true);
    }
    public function unLock()
    {
        global $userCurrent, $CDWFunc;
        return $CDWFunc->updateUserOption($userCurrent->ID, 'lock', false);
    }
    public function getLock()
    {
        global $userCurrent, $CDWFunc;
        return $CDWFunc->getUserOption($userCurrent->ID, 'lock');
    }
}

$functionLock = new functionLock();
