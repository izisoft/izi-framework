<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\web;

/**
 * Class Application
 *
 * @package izi\web
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class Application extends \izi\base\Application
{

    /**
     * {@inheritdoc}
     */
    public function coreComponents()
    {
        return array_merge(parent::coreComponents(), [
            'request' => ['class' => 'izi\web\Request'],
            'response' => ['class' => 'izi\web\Response'],
            'session' => ['class' => 'izi\web\Session'],
            'router' => ['class' => 'izi\web\Router'],
            'controller' => ['class' => 'izi\web\Controller'],
            'view' => ['class' => 'izi\web\View'],

//            'user' => ['class' => 'izi\web\User'],
//            'errorHandler' => ['class' => 'izi\web\ErrorHandler'],
        ]);
    }
}