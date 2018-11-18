<?php
  class Pbcrouter {
    public $url;
    public $controller;
    public $handler;
    public $app;
    public $pages;
    public $redirect;
    public $config;

    public function __construct() {
      $this->app = new App;
      $this->config = $this->app->jdb('open', 'sys/router/config');
      $this->pages = $this->app->jdb('open', 'sys/router/pages');
      $this->redirect = (!isset($_POST['app_redirect']) ? false : $_POST['app_redirect']);
      $this->url = (!isset($_GET['app_url']) ? 'home/index' : $_GET['app_url']);
      $this->url = (substr($this->url, 0, 1) == '/' ? substr($this->url, 1) : $this->url);
      $this->url = (substr($this->url, -1) == '/' ? substr($this->url, 0, -1) : $this->url);
      $this->url = (substr($this->url, 0, 1) == '\\' ? substr($this->url, 1) : $this->url);
      $this->url = (substr($this->url, -1) == '\\' ? substr($this->url, 0, -1) : $this->url);
      $this->controller = $this->urlpart(0);
      $this->handler = $this->urlpart(1);
    }

    protected function urlpart($part = 0) {
      $url = $this->url;
      $url = (strpos($url, '/') ? explode('/', $url)[$part] : false);
      return $url;
    }

    protected function exists($function, $name = false) {
      if ($function == 'controller') {
        $name = (!$name ? $this->controller : $name);
        if (isset($this->pages[$name])) {
          return true;
        } else {
          return false;
        }
      } else if ($function == 'handler') {
        $name = (!$name ? $this->handler : $name);
        if (isset($this->pages[$this->controller][$name])) {
          return true;
        } else {
          return false;
        }
      } else if ($function == 'defaultController') {
        if (isset($this->pages['default'])) {
          return true;
        } else {
          return false;
        }
      } else if ($function == 'defaultHandler') {
        if (isset($this->pages[$this->controller]['default'])) {
          return true;
        } else {
          return false;
        }
      }
    }

    public function get($type) {
      if ($type == 'controller') {
        return $this->controller;
      } else if ($type == 'handler') {
        return $this->handler;
      } else if ($type == 'defaultController') {
        if (!$this->exists('defaultController')) {
          return false;
        } else {
          return $this->pages['default'];
        }
      } else if ($type == 'defaultHandler') {
        if (!$this->exists('defaultHandler')) {
          return false;
        } else {
          return $this->pages[$this->controller]['default'];
        }
      } else if ($type == 'page') {
        if (!$this->exists('controller')) {
          if ($this->exists('defaultController')) {
            $defaultController = $this->get('defaultController');
            $this->set('controller', $defaultController);
          } else {
            http_response_code(404);
            die;
          }
        }

        if (!$this->exists('handler')) {
          if ($this->exists('defaultHandler')) {
            $defaultHandler = $this->get('defaultHandler');
            $this->set('handler', $defaultHandler);
          } else {
            http_response_code(404);
            die;
          }
        }

        $page = $this->pages[$this->controller][$this->handler];

        if ($page['maintenance']) {
          $this->set('controller', $this->config['defaults']['maintenance']['controller']);
          $this->set('function', $this->config['defaults']['maintenance']['handler']);
        }

        $page = $this->pages[$this->controller][$this->handler];
        return $page;
      } else if ($type == 'params') {
        $url = $this->url;
        $amount = count(explode('/', $url)) - 2;
        if ($amount > 0) {
          for ($i = $amount; $i > 0; $i--) {
            $param[$i] = $this->urlpart($i + 1);
          }
        } else {
          $param = false;
        }
        return $param;
      }
    }

    public function set($type, $input) {
      if ($type == 'controller') {
        $this->controller = $input;
      } else if ($type == 'handler') {
        $this->handler = $input;
      } else if ($type == 'url') {
        $this->url = $input;
      }
    }

    public function load($url = false) {
      $this->url = (!$url ? $this->url : $url);
      $page = $this->get('page');
      $param = $this->get('params');
      $redirect = $this->redirect;
      require_once __DIR__ . '/skeleton.php';
    }

    public function setup() {
      $pages = array(
        "default" => "home",
        "home" => array(
          "default" => "index",
          "index" => array(
            "title" => "home",
            "target" => "home.php",
            "css" => "rable.css/home",
            "js" => "rable.js/home",
            "maintenance" => false
          )
        )
      );
      $config = array(
        "defaults" => array(
          "404" => array(
            "controller" => "err",
            "handler" => "404",
            "url" => "err/404"
          ),
          "403" => array(
            "controller" => "err",
            "handler" => "403",
            "url" => "err/403"
          ),
          "maintenance" => array(
            "controller" => "err",
            "handler" => "maintenance",
            "url" => "err/maintenance"
          )
        )
      );
      $navbar = array();
      $footer = array();

      $this->app->jdb('new', 'sys/router/pages');
      $this->app->jdb('new', 'sys/router/config');
      $this->app->jdb('new', 'sys/router/navbar');
      $this->app->jdb('new', 'sys/router/footer');

      $this->app->jdb('update', 'sys/router/pages', $pages);
      $this->app->jdb('update', 'sys/router/config', $config);
      $this->app->jdb('update', 'sys/router/navbar', $navbar);
      $this->app->jdb('update', 'sys/router/footer', $footer);
    }
  }
?>
