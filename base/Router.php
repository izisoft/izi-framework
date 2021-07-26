<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\base;
use Izi;
use izi\exceptions\NotFoundException;

/**
 * Class Router
 *
 * @package izi
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class Router
{
    public Request $request;
    public Response $response;
    protected array $routers = [];

    /**
     * Router constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct()
    {
        $this->request = Izi::$app->request;
        $this->response = Izi::$app->response;
    }

    public function get($path, $callback)
    {
        $this->routers['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routers['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routers[$method][$path] ?? false;
        if($callback === false){
            $this->response->setStatusCode(404);
            throw new NotFoundException();
        }

        if(is_string($callback)){
            return $this->renderView($callback);
        }
        if(is_array($callback)){
            /** @var Controller $controller */
            $controller = new $callback[0];
            Izi::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware){
                $middleware->execute();
            }
        }
        return call_user_func($callback, $this->request, $this->response);

    }

    public function renderView($view, $params)
    {
        return Izi::$app->view->renderView($view, $params);
    }

    public function renderContent($viewContent)
    {
        return Izi::$app->view->renderContent($viewContent);
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

}