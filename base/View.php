<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\base;

use Izi;

/**
 * Class View
 *
 * @package izi
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class View
{
    public string $title = '';


    public function renderView($view, $params)
    {
        $viewContent = $this->renderOnlyView($view, $params);

        // Layout render last
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent()
    {
        $layout = Izi::$app->layout;

        if(Izi::$app->controller){
            $layout = Izi::$app->controller->layout;
        }

        ob_start();
        include_once Izi::getAlias('@webroot') . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {

        extract($params);

        ob_start();


        include_once Izi::getAlias('@webroot') . "/views/$view.php";
        return ob_get_clean();
    }
}