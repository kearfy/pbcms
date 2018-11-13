<?php
  if (file_exists(__DIR__ . '/app/app.php')) {
    require_once __DIR__ . '/app/app.php';
  } else {
    echo 'Whoops, an internal error occured, this page will soon be more detailed and styled';
    die;
  }
?>
