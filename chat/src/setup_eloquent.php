<?php
  use \Illuminate\Database\Capsule\Manager as Capsule;
  use \Illuminate\Events\Dispatcher;
  use \Illuminate\Container\Container;

  date_default_timezone_set("Africa/Nairobi");

  $container = new Container();
  $capsule = new Capsule;

  $capsule->addConnection([
     'driver' => 'mysql',
     'host' => 'localhost',
      'database' => 'newsocial',
      'username' => "root",
      'password' => "",
      'charset' => 'utf8',
      'prefix' => '',
      'collation' => 'utf8_unicode_ci'
  ]);

  $capsule->setEventDispatcher(new Dispatcher($container));
  $capsule->setAsGlobal();
  $capsule->bootEloquent();