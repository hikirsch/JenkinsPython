<?php
/**
 * This file can be used as a global configuration for the application. It will load after the env-config.php file
 * has been loaded.
 */

// since this config file is always one level above the root, define the project root as being one level below
define( "PROJECT_PATH", realpath( dirname( __FILE__ ) . "/.." ) );

define( "APP_CONF_PATH", PROJECT_PATH . "/config" );
define( "APP_LIB_PATH", PROJECT_PATH . "/lib" );
define( "APP_CLASSES_PATH", PROJECT_PATH . "/classes" );

define( "APP_MODEL_PATH", APP_CLASSES_PATH . "/sampleProject/Model" );
define( "APP_VIEWS_PATH", APP_CLASSES_PATH . "/sampleProject/Views" );

define( "LIB_PROPEL_PATH", PROJECT_PATH . "/lib/Propel-1.6.8" );
define( "LIB_SAVANT3_PATH", PROJECT_PATH . "/lib/Savant3-3.0.1" );
