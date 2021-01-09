<?php
/*
* リクエストを処理するクラス
*/
class Request
{
    public function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }
        return false;
    }

    public function isGet()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }
        return false;
    }

    public function getGet($name, $default = null)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        return $default;
    }

    public function getPost($name, $default = null)
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        return $default;
    }

    public function getHost()
    {
        if (!empty($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }
        return $_SERVER['SERVER_NAME'];
    }

    public function isSsl()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        }
        return false;
    }

    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }
    
    // URL 制御
    public function getBaseUrl()
    {
        $script_name = $_SERVER['SCRIPT_NAME'];

        $request_uri = $this->getRequestUri();

        if (0 === strpos($request_uri, $script_name)) {
            return $script_name;
        } elseif (0 === strpos($request_uri, dirname($script_name))) {
            return rtrim(dirname($script_name), '/');
        }

        return;
    }

    public function getPathInfo()
    {
        $base_url    = $this->getBaseUrl();
        $request_uri = $this->getRequestUri();

        if (false !== ($pos = strpos($request_uri, '?'))) {
            $request_uri = substr($request_uri, 0, $pos);
        }

        $path_info = (string)substr($request_uri, strlen($base_url));

        return $path_info;
    }

    public function isRoot()
    {
        if ($_SERVER['PHP_SELF'] === '/') {
            return true;
        }
        return false;
    }

    public function getUserAgent()
    {
        $userAgent = isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT'] : '';
        return $userAgent;
    }

    public function isSmartPhone()
    {
        $ua = $this->getUserAgent();

        if (stripos($ua, 'iphone') !== false ||
            stripos($ua, 'ipod') !== false ||
            (stripos($ua, 'android') !== false && stripos($ua, 'mobile') !== false) ||
            (stripos($ua, 'windows') !== false && stripos($ua, 'mobile') !== false) ||
            (stripos($ua, 'firefox') !== false && stripos($ua, 'mobile') !== false) ||
            (stripos($ua, 'bb10') !== false && stripos($ua, 'mobile') !== false) ||
            (stripos($ua, 'blackberry') !== false) ||
            // タブレットもスマホレイアウト
            (strpos($ua, 'iPad') !== false) ||
            (strpos($ua, 'Android') !== false) 
        ) {
            return true;
        } 
        return false;
    }

    public function checkPathInfo()
    {
        $path_info = $this->getPathInfo();
        if ($path_info === '/' || $path_info === '/post/search') {
            return true;
        } 
        return false;
    }

}