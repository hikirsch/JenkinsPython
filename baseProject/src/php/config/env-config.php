<?php
/**
 * This file should be used to store any sort of environment specific configuration, this
 * could include things such as database settings, google analytics code, enable minification, etc.
 */

define( "APP_DEFAULT_HOSTNAME", $_SERVER["SERVER_NAME"] );

define( "APP_BASE_URL", "http://" . $_SERVER["SERVER_NAME"] );

define( "APP_SHOW_EXCEPTION", true );
define( "APP_SHOW_SEO_DUMP", true );

define( "USE_PLOVR", true );
