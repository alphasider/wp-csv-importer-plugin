<?php

  spl_autoload_register('mymotorist_autoload');

  function mymotorist_autoload($classname){
    $path = plugin_dir_path(__DIR__) . 'class';
    $full_path = $path . $classname . '.php';
    include_once $full_path;
  }