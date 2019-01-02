<?php

define('DEBUG', true); //When true, erros will show, when false errors will be logged

define('DB_NAME', 'mvctut'); //Database name
define("DB_USER", 'root'); //Database username
define('DB_PASSWORD', ''); //Database password
define('DB_HOST', '127.0.0.1'); //Database host address (Use IP for best performance)

define('DEFAULT_CONTROLLER', 'Home'); //default controller if there isn't one defined in the url 
define('DEFAULT_LAYOUT', 'default'); //If no layout is set in the controller, use 'default'

define('SITE_TITLE', 'MVC Framework Tutorial'); //Sets a default site title
define('SITE_BRAND_TEXT', 'MVC Tutorial'); //Sets the text that appears in the main menu brand region

define('PROOT', '/mvctut/'); //set this to '/' for a live server

define('CURRENT_USER_SESSION_NAME', 'kjadKkLLjNNwwefefLLLLD'); //Session name for logged in user
define('REMEMBER_ME_COOKIE_NAME', 'DFDSkklsfeisdlww2223sdfle'); //Cookie Name for logged in user
define('REMEMBER_ME_COOKIE_EXPIRY', 2592000); //Time in seconds (30 days) for remember me cookie to live

define("ACCESS_RESTRICTED", 'Restricted'); //Controller name for restricted redirect