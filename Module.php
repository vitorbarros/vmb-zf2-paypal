<?php
<<<<<<< HEAD
/**
 * This file is placed here for compatibility with Zendframework 2's ModuleManager.
 * It allows usage of this module even without composer.
 * The original Module.php is in 'src/DoctrineDataFixtureModule' in order to respect PSR-0
 */
require_once __DIR__ . '/src/VMBPayPal/Module.php';
=======
namespace VMBPayPal;

class Module
{
    public function getConfig()
    {

        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array()
        );
    }
}
>>>>>>> c8fa923049b00a862779c00e979f2de7442d58c8
