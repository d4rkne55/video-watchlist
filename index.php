<?php
// get project URL path relative to the server document root
$projectDir = basename(__DIR__);
$root = explode($projectDir, $_SERVER['REQUEST_URI']);
$root = $root[0] . $projectDir;

define('ROOT', $root . '/');
unset($root);


// this variable defines the folders which contain Class files to be auto-loaded
$classDirs = array(
    'Classes',
    'Classes/Vendor'
);

/**
 * This is the autoloader, that automatically includes the files for needed Classes
 */
spl_autoload_register(function($class) use ($classDirs) {
    foreach ($classDirs as $dir) {
        // replace backslashes from namespace
        $class = strtr($class, '\\', '/');
        $path = "$dir/$class.class.php";

        if (file_exists($path)) {
            require $path;
            break;
        }
        // just for semantics, not needed
        else continue;
    }
});


Framework\DI::registerService(Framework\ConfigHelper::class, new Framework\ConfigHelper('config.yml'));

/**
 * Instantiate the Router
 * there the routing takes place, which automatically calls the method belonging to the requested URL
 */
new Framework\Router();