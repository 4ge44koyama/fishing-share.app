<?php

class Session
{
    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;

    public function __construct()
    {
        if (!self::$sessionStarted) {
            session_start();

            self::$sessionStarted = true;
        }
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function setArr($name, $array = [])
    {
        foreach ($array as $key => $val) {
            $_SESSION[$name][$key] = $val;
        }
    }

    public function get($name, $default = null)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return $default;
    }

    public function clear()
    {
        $_SESSION = [];
    }

    public function regenerate($destroy = true)
    {
        if (!self::$sessionIdRegenerated) {
            session_regenerate_id($destroy);

            self::$sessionIdRegenerated = true;
        }
    }

    public function setAuthenticated($bool)
    {
        $this->set('_authenticated', (bool)$bool);

        $this->regenerate();
    }

    public function isAuthenticated()
    {
        return $this->get('_authenticated', false);
    }

    public function checkFlashMsg($msg_kind = null)
    {
        if (isset($_SESSION['primary'])) {
            $msg_kind = 'primary';
        }
        if (isset($_SESSION['danger'])) {
            $msg_kind = 'danger';
        }
        return $msg_kind;
    }

    public function getMsg()
    {
        $msg_kind = $this->checkFlashMsg();
        $msg = $_SESSION[$msg_kind];
        unset($_SESSION[$msg_kind]); 
        $msg_arr[$msg_kind] = $msg;
        return $msg_arr;
    }

    public function getNickname($nickname = null)
    {
        if ($_SESSION['member']['nickname']) {
            $nickname = $_SESSION['member']['nickname'];
        }
        return $nickname;
    }

    public function getMemberId($member_id = null)
    {
        if ($_SESSION['member']['id']) {
            $member_id = $_SESSION['member']['id'];
        }
        return $member_id;
    }
}