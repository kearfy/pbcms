<?php
  class App {
    public $config;

    public function __construct() {
      $this->config = (file_exists(__DIR__ . '/internal/db/sys/config.json') ? json_decode(file_get_contents(__DIR__ . '/internal/db/sys/config.json'), true) : false);
    }

    public function file($function, $file, $content = false) {
      $dir = '';
      if (substr($file, 0, 1) == '/') {
        $file = substr($file, 1);
      }

      if (strpos($file, '/')) {
        $dir = implode('/', array_slice(explode('/', $file), 0, -1));
      }

      if ($function == 'exists') {
        if (file_exists(__DIR__ . '/' . $file)) {
          return true;
        } else {
          return false;
        }
      } else if ($function == 'content') {
        if ($this->file('exists', $file)) {
          $path = __DIR__ . '/' . $file;
          $file = fopen($path, "r");
          if (filesize($path) > 0) {
            $content = fread($file, filesize($path));
          } else {
            $content = '';
          }
          return $content;
          fclose($file);
        } else {
          return false;
        }
      } else if ($function == 'print') {
        if (!$this->file('content', $file)) {
          return false;
        } else {
          echo htmlentities($this->file('content', $file));
          return true;
        }
      } else if ($function == 'require') {
        if (!$this->file('exists', $file)) {
          return false;
        } else {
          require_once(__DIR__ . '/' . $file);
          return true;
        }
      } else if ($function == 'make') {
        if (!$this->file('exists', $file)) {
          chmod(__DIR__ . '/' . $dir, 755);
          $file = fopen(__DIR__ . '/' . $file, "wb");
          fclose($file);
          return true;
        } else {
          return false;
        }
      } else if ($function == 'setcontent') {
        if (!$this->file('exists', $file)) {
          return false;
        } else {
          if (!$content) {
            return false;
          } else {
            $this->file('empty', $file);
            $this->file('addcontent', $file, $content);
            $file = fopen(__DIR__ . '/' . $file, "wb");
            fwrite($file, $content);
            fclose($file);
          }
        }
      } else if ($function == 'empty') {
        if (!$this->file('exists', $file)) {
          return false;
        } else {
          $file = fopen(__DIR__ . '/' . $file, "r+");
          ftruncate($file, 0);
          fclose($file);
        }
      } else if ($function == 'delete') {
        if (!$this->file('exists', $file)) {
          return false;
        } else {
          if (is_writable(__DIR__ . '/' . $file)) {
            unlink(__DIR__ . '/' . $file);
            return true;
          } else {
            return false;
          }
        }
      } else {
        return false;
      }
    }
  }
?>
