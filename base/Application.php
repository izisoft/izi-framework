<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\base;

use izi\db\Database;
use izi\db\DbModel;

/**
 * Class Application
 * @package izi
 * @author Giàng A Tỉn <vantruong1898@gmail.com>
 * @since 1.0
 * @var $userClass UserModel
 */
class Application
{
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';

    protected array $eventListeners = [];

    public string $layout = 'main';
    public string $userClass;
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?DbModel $user;
    public ?Controller $controller = null;
    public View $view;
    public static Application $app;

    /**
     * Application constructor.
     * @param $rootPath
     * @param array $config
     */
    public function __construct($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->userClass = $config['user']['class'];
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
        $this->view = new View();

        $primaryValue = $this->session->get('user');
        if($primaryValue){
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }else{
            $this->user = null;
        }

    }

    /**
     *
     */
    public function run()
    {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try{
            echo $this->router->resolve();
        }catch (\Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    /**
     * @param $eventName
     * @param $callback
     */
    public function on($eventName, $callback)
    {
        $this->eventListeners[$eventName][] = $callback;
    }

    /**
     * @param $eventName
     */
    public function triggerEvent($eventName)
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback){
            call_user_func($callback);
        }
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @param DbModel $user
     * @return bool
     */
    public function login(DbModel $user): bool
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    /**
     * Logout && clear user session
     */
    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    /**
     * @return bool
     */
    public static function isGuest(): bool
    {
        return !self::$app->user;
    }
}