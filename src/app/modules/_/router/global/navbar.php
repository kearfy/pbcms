<?php
  class Navbar {
    public $config;
    public $app;
    public $position;
    public $classes;
    public $brand;
    public $items;

    public function __construct() {
      $this->app = new App;
      $this->config = $this->app->jdb('open', 'sys/router/navbar');

      if ($this->config['config']['position'] == 'default') {
        $this->position = '';
      } else if ($this->config['config']['position'] == 'top') {
        $this->position = ' fixed-top';
      } else if ($this->config['config']['position'] == 'bottom') {
        $this->position = ' fixed-bottom';
      } else if ($this->config['config']['position'] == 'sticky') {
        $this->position = ' sticky-top';
      } else {
        $this->position = '';
      }

      $this->classes = 'navbar' . $this->position . ' navbar-expand-lg' . ($this->config['config']['color'] == 'light' ? ' navbar-light bg-light' : ' navbar-dark bg-dark');
      $this->brand = '<a class="navbar-brand" href="' . $this->filterurl($this->config['brand-href']) . '">' . ($this->config['brand-text'] == '>sitename<' ? $this->app->config['details']['name'] : $this->config['brand-text']) . '</a>' . "\n";

    }

    public function filterurl($url) {
      return preg_replace("/\-=(.*?)\=-/", ($this->app->config['details']['ssl'] ? 'https://' : 'http://') . $this->app->config['details']['domain'] . $this->app->config['details']['root'] . '$1', $url);
    }

    public function generateItems() {
      $items = '';
      foreach ($this->config['items'] as $i) {
        if ($i['type'] == 'item') {
          $item = '
          <li class="nav-item' . (!$i['active'] ? '' : ' active') . '">
            <a class="nav-link' . (!$i['disabled'] ? '' : ' disabled') . '" href="' . $this->filterurl($i['href']) . '">' . $i['text'] . '</a>
          </li>
          ';
          if (empty($items)) {
            $items = $item;
          } else {
            $items = $items . "\n" . $item;
          }
        } else if ($i['type'] == 'dropdown') {
          if (isset($i['items']) && !empty($i['items'])) {
            $dItems = '';
            foreach($i['items'] as $di) {
              $dItem = '
              <a class="dropdown-item' . (!$di['disabled'] ? '' : ' disabled') . '" href="' . $this->filterurl($di['href']) . '">' . $di['text'] . '</a>
              ';
              if (empty($dItems)) {
                $dItems = $dItem;
              } else {
                $dItems = $dItems . "\n" . $dItem;
              }
            }
            $item = '
            <li class="nav-item dropdown' . (!$i['active'] ? '' : ' active') . '">
              <a class="nav-link dropdown-toggle' . (!$di['disabled'] ? '' : ' disabled') . '" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ' . $i['text'] . '
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                ' . $dItems . '
              </div>
            </li>
            ';
            if (empty($items)) {
              $items = $item;
            } else {
              $items = $items . "\n" . $item;
            }
          }
        }
      }
      return $items;
    }
  }

  $nav = new Navbar;
?>

<nav class="<?php echo $nav->classes; ?>">
  <?php
  if ($nav->config['config']['container']) {
    ?>
    <div class="container">
    <?php
      echo $nav->brand;
      ?>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <ul class="navbar-nav mr-auto">
        <?php echo $nav->generateItems(); ?>
      </ul>
    </div>
    <?php
  } else {
    echo $nav->brand;
    ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="navbar-nav mr-auto">
      <?php echo $nav->generateItems(); ?>
    </ul>
    <?php
  }
  ?>
</nav>
