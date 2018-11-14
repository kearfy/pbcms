<?php
  if (file_exists(__DIR__ . '/classes.php')) {
    require_once __DIR__ . '/classes.php';
    if (file_exists(__DIR__ . '/internal/core.php')) { require_once __DIR__ . '/internal/core.php'; } else { echo  'Whoops, an internal error occured, this page will soon be more detailed and styled'; die; }
    $app = new App;

    //script your code for your custom plugins in here...

  } else {
    echo 'Whoops, an internal error occured, this page will soon be more detailed and styled';
    die;
  }
?>
