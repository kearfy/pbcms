<?php
  class App {
    public $config;

    public function __construct() {
      $this->config = (file_exists(__DIR__ . '/internal/db/sys/config.json') ? json_decode(file_get_contents(__DIR__ . '/internal/db/sys/config.json'), true) : false);
    }
  }
?>
