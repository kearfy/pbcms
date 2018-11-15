<?php
  class App {
    public $config;

    public function __construct() {
      $this->config = (file_exists(__DIR__ . '/internal/jdb/sys/config.json') ? json_decode(file_get_contents(__DIR__ . '/internal/jdb/sys/config.json'), true) : false);
    }

    public function file($function, $file, $content = false) {
      $file = (substr($file, 0, 1) == '/' ? substr($file, 1) : $file);
      if (strpos($file, '/')) {
        $parts = explode('/', $file);
        if (strpos(end($parts), '.')) {
          $dir = implode('/', array_slice($parts, 0, -1)) . '/';
          $filename = array_pop($parts);
          $type = 'file';
        } else {
          $dir = $file . '/';
          $filename = '';
          $type = 'dir';
        }
      } else {
        if (strpos($file, '.')) {
          $type = 'file';
          $filename = $file;
          $dir = '';
        } else {
          $type = 'dir';
          $filename = '';
          $dir = '';
        }
      }

      if ($function == 'exists') {
        if ($type == 'file') {
          if (file_exists(__DIR__ . '/' . $file)) {
            return true;
          } else {
            return false;
          }
        } else {
          if (is_dir(__DIR__ . '/' . $dir)) {
            return true;
          } else {
            return false;
          }
        }
      } else if ($function == 'content') {
        if ($type == 'dir') {
          return false;
        } else {
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
        }
      } else if ($function == 'print') {
        if ($type == 'dir') {
          return false;
        } else {
          if (!$this->file('content', $file)) {
            return false;
          } else {
            echo htmlentities($this->file('content', $file));
            return true;
          }
        }
      } else if ($function == 'require') {
        if ($type == 'dir') {
          return false;
        } else {
          if (!$this->file('exists', $file)) {
            return false;
          } else {
            require_once(__DIR__ . '/' . $file);
            return true;
          }
        }
      } else if ($function == 'make') {
        if ($type == 'dir') {
          if (!$this->file('exists', $dir)) {
            mkdir(__DIR__ . '/' . $dir, 0774);
          } else {
            return false;
          }
        } else {
          if (!$this->file('exists', $file)) {
            if (!$this->file('exists', $dir)) {
              $this->file('make', $dir);
            }
            chmod(__DIR__ . '/' . $dir, 755);
            $file = fopen(__DIR__ . '/' . $file, "wb");
            fclose($file);
            return true;
          } else {
            return false;
          }
        }
      } else if ($function == 'setcontent') {
        if ($type == 'dir') {
          return false;
        } else {
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
        }
      } else if ($function == 'empty') {
        if ($type == 'dir') {
          return false;
        } else {
          if (!$this->file('exists', $file)) {
            return false;
          } else {
            $file = fopen(__DIR__ . '/' . $file, "r+");
            ftruncate($file, 0);
            fclose($file);
          }
        }
      } else if ($function == 'delete') {
        if ($type == 'dir') {
          if (!$this->file('exists', $dir)) {
            return false;
          } else {
            $files = glob(__DIR__ . '/' . $dir . '*', GLOB_MARK);
            foreach ($files as $f) {
              $l = strlen(__DIR__);
              $path = substr($f, $l);
              echo $path;
              $this->file('delete', $path);
            }
            rmdir(__DIR__ . '/' . $file);
          }
        } else {
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
        }
      } else {
        return false;
      }
    }

    public function json($function, $data) {
      if ($function == 'decode') {
        return json_decode($data, true);
      } else if ($function == 'encode') {
        return json_encode($data, JSON_PRETTY_PRINT);
      } else if ($function == 'print') {
        $this->setheader('json');
        print_r($data);
      }
    }

    public function jdb($function, $file, $data = false) {
      $jdbp = $this->config['jdb']['path'];
      $jdbp = (substr($jdbp, -1) !== '/' ? $jdbp . '/' : $jdbp);
      $file = (substr($file, -5) !== '.json' ? $file . '.json' : $file);
      $file = (substr($file, 0, 1) == '/' ? substr($file, 1) : $file);
      if (strpos($file, '/')) {
        $parts = explode('/', $file);
        $dir = implode('/', array_slice($parts, 0, -1)) . '/';
        $filename = array_pop($parts);
      } else {
        $dir = '';
        $filename = $file;
      }

      if ($function == 'exists') {
        if (!$this->file('exists', $jdbp . $file)) {
          return false;
        } else {
          return true;
        }
      } else if ($function == 'open') {
        if (!$this->jdb('exists', $file)) {
          return false;
        } else {
          return $this->json('decode', $this->file('content', $jdbp . $file));
        }
      } else if ($function == 'new') {
        if (!$this->jdb('exists', $file)) {
          return $this->file('make', $jdbp . $file);
        } else {
          return false;
        }
      } else if ($function == 'update') {
        if (!$this->jdb('exists', $file)) {
          return false;
        } else {
          if (!$data) {
            return false;
          } else {
            return $this->file('setcontent', $jdbp . $file, $this->json('encode', $data));
          }
        }
      } else if ($function == 'empty') {
        if (!$this->jdb('exists', $file)) {
          return false;
        } else {
          return $this->file('empty', $jdbp . $file);
        }
      } else if ($function == 'delete') {
        if (!$this->jdb('exists', $file)) {
          return false;
        } else {
          return $this->file('delete', $jdbp . $file);
        }
      } else {
        return false;
      }
    }

    public function setheader($input, $custom = false) {
      if (!$custom) {
        switch ($input) {
          case 'json' :
            header('Content-Type: application/json');
            break;
        }
      } else {
        header('Content-Type: ' . $input);
      }
    }
  }
?>
