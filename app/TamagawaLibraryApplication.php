<?php

class TamagawaLibraryApplication extends Application
{
    protected $login_action = ['member', 'signin'];

    public function getRootDir()
    {
        return dirname(__FILE__);
    }

    protected function registerRoutes()
    {
        return [
            '/'
                => ['controller' => 'post', 'action' => 'index'], 
            '/post/search'
                => ['controller' => 'post', 'action' => 'search'], 
            '/post/new'
                => ['controller' => 'post', 'action' => 'new'], 
            '/post/create'
                => ['controller' => 'post', 'action' => 'create'], 
            '/post/edit/:id'
                => ['controller' => 'post', 'action' => 'edit'], 
            '/post/update/:id'
                => ['controller' => 'post', 'action' => 'update'], 
            '/post/show/:id'
                => ['controller' => 'post', 'action' => 'show'], 
            '/post/delete/:id'
                => ['controller' => 'post', 'action' => 'destroy'], 
            '/member/login' 
                => ['controller' => 'member', 'action' => 'login'], 
            '/member/signin' 
                => ['controller' => 'member', 'action' => 'signin'], 
            '/member/signup' 
                => ['controller' => 'member', 'action' => 'signup'], 
            '/member/signout' 
                => ['controller' => 'member', 'action' => 'signout'], 
            'member/register'
                => ['controller' => 'member', 'action' => 'register'], 
            'comment/post'
                => ['controller' => 'comment', 'action' => 'post'], 
            'like/switch'
                => ['controller' => 'like', 'action' => 'switch'], 
        ];
    }

    protected function configure()
    {
        $this->db_manager->connect('master', [
            'dsn'      => 'mysql:dbname=fishing-share;host=localhost;charset=utf8mb4', 
            'user'     => 'root', 
            'password' => 'root', 
        ]);
    }
}