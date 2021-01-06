<?php

class View
{
    protected $base_dir;
    protected $defaults;
    protected $layout_variables = [];

    public function __construct($base_dir, $defaults = [])
    {
        $this->base_dir = $base_dir;
        $this->defaults = $defaults;
    }

    public function setLayoutVar($name, $value)
    {
        $this->layout_variables[$name] = $value;
    }

    public function render($_path, $_variables = [], $_layout = false)
    {
        $_file = $this->base_dir . '/' . $_path . '.php';

        extract(array_merge($this->defaults, $_variables));

        ob_start();
        ob_implicit_flush(0);

        require $_file;

        $content = ob_get_clean();

        if ($_layout) {
            $content = $this->render($_layout, 
                            array_merge($this->layout_variables, 
                                [
                                    '_content' => $content, 
                                ]
                            )
            );
        }

        return $content;
    }

    public function escape($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    public function importFooter($sp_flg)
    {
        if ($sp_flg) {
            return 'footer_sp';
        }
        return 'footer';
    }

    public function importHeader($sp_flg)
    {
        if ($sp_flg) {
            return 'header_sp';
        }
        return 'header';
    }
    
    public function importPostButton($sp_flg)
    {
        if ($sp_flg) {
            return 'post_button_sp';
        }
        return 'post_button';
    }

    public function buttonText($login_flg) 
    {
        if ($login_flg) {
            return 'ログアウト';
        }
        return 'ログイン';
    }

    public function buttonPath($login_flg) 
    {
        if ($login_flg) {
            return '/member/signout';
        }
        return '/member/login';
    }
}