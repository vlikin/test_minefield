<?php
/**
 * @file It defines the system processors.
 */

/**
 * Implements the load hook.
 *
 * @param $class
 */
function project_autoload($class) {
    $allowed_namespaces = ['Miner'];
    if (!in_array(explode('\\', $class)[0], $allowed_namespaces)) {
        return;
    }

    $path = dirname(__FILE__) . '/' .  str_replace('\\', '/', $class) . '.php';
    require_once($path);
}

spl_autoload_register('project_autoload');