<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace app\core;

/**
 * Class View
 *
 * @package app\core
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
        $layout = Application::$app->layout;

        if(Application::$app->controller){
            $layout = Application::$app->controller->layout;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {

        extract($params);

        ob_start();


        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}