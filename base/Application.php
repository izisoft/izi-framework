<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\base;

use Izi;

/**
 * @package izi\base
 * @author Giàng A Tỉn <vantruong1898@gmail.com>
 * @since 1.0
 */

/**
 * Class Application
 * @property-read \izi\db\Connection $db
 * @property-read \izi\web\Router $router
 * @property-read \izi\web\Response $response
 * @property-read \izi\web\Session $session
 * @property-read \izi\web\View $view
 * @property-read \izi\web\Controller $controller
 *
 */
abstract class Application extends Module
{

    /**
     * @event Event an event raised before the application starts to handle a request.
     */
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    /**
     * @event Event an event raised after the application successfully handles a request (before the response is sent out).
     */
    const EVENT_AFTER_REQUEST = 'afterRequest';
    /**
     * Application state used by [[state]]: application just started.
     */
    const STATE_BEGIN = 0;
    /**
     * Application state used by [[state]]: application is initializing.
     */
    const STATE_INIT = 1;
    /**
     * Application state used by [[state]]: application is triggering [[EVENT_BEFORE_REQUEST]].
     */
    const STATE_BEFORE_REQUEST = 2;
    /**
     * Application state used by [[state]]: application is handling the request.
     */
    const STATE_HANDLING_REQUEST = 3;
    /**
     * Application state used by [[state]]: application is triggering [[EVENT_AFTER_REQUEST]]..
     */
    const STATE_AFTER_REQUEST = 4;
    /**
     * Application state used by [[state]]: application is about to send response.
     */
    const STATE_SENDING_RESPONSE = 5;
    /**
     * Application state used by [[state]]: application has ended.
     */
    const STATE_END = 6;

    protected array $eventListeners = [];

//    public string $layout = 'main';
//    public string $userClass;
//    public static string $ROOT_DIR;
//    public Router $router;
//    public Request $request;
//    public Response $response;
//    public Session $session;
//    public Database $db;
//    public  $user = false;
//    public ?Controller $controller = null;
//    public View $view;
//    public static Application $app;

    /////////////////////


    public $extensions = [];

    public $bootstrap = [];
    /**
     * @var int the current application state during a request handling life cycle.
     * This property is managed by the application. Do not modify this property.
     */
    public $state;
    /**
     * @var array list of loaded modules indexed by their class names.
     */
    public $loadedModules = [];

    /**
     * Application constructor.
     * @param $rootPath
     * @param array $config
     */
    public function __construct(array $config)
    {
        Izi::$app = $this;

        static::setInstance($this);

        $this->state = self::STATE_BEGIN;

        $this->preInit($config);

        Component::__construct($config);
    }

    /**
     * @param $config
     * @throws InvalidConfigException
     */
    public function preInit(&$config)
    {
        if (!isset($config['id'])) {
            throw new InvalidConfigException('The "id" configuration for the Application is required.');
        }
        if (isset($config['basePath'])) {
            $this->setBasePath($config['basePath']);
            unset($config['basePath']);
        } else {
            throw new InvalidConfigException('The "basePath" configuration for the Application is required.');
        }

        if (isset($config['vendorPath'])) {
            $this->setVendorPath($config['vendorPath']);
            unset($config['vendorPath']);
        } else {
            // set "@vendor"
            $this->getVendorPath();
        }
        if (isset($config['runtimePath'])) {
            $this->setRuntimePath($config['runtimePath']);
            unset($config['runtimePath']);
        } else {
            // set "@runtime"
            $this->getRuntimePath();
        }

        if (isset($config['timeZone'])) {
            $this->setTimeZone($config['timeZone']);
            unset($config['timeZone']);
        } elseif (!ini_get('date.timezone')) {
            $this->setTimeZone('UTC');
        }

        if (isset($config['container'])) {
            $this->setContainer($config['container']);

            unset($config['container']);
        }

        // merge core components with custom components
        foreach ($this->coreComponents() as $id => $component) {
            if (!isset($config['components'][$id])) {
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id]) && !isset($config['components'][$id]['class'])) {
                $config['components'][$id]['class'] = $component['class'];
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {

        $this->state = self::STATE_INIT;
        $this->bootstrap();
    }

    /**
     * @throws InvalidConfigException
     */
    protected function bootstrap()
    {
        if ($this->extensions === null) {
            $file = Izi::getAlias('@vendor/izisoft/izi-framework/extensions.php');
            $this->extensions = is_file($file) ? include $file : [];
        }
        foreach ($this->extensions as $extension) {
            if (!empty($extension['alias'])) {
                foreach ($extension['alias'] as $name => $path) {
                    Izi::setAlias($name, $path);
                }
            }
            if (isset($extension['bootstrap'])) {
                $component = Izi::createObject($extension['bootstrap']);
                if ($component instanceof BootstrapInterface) {
                    Izi::debug('Bootstrap with ' . get_class($component) . '::bootstrap()', __METHOD__);
                    $component->bootstrap($this);
                } else {
                    Izi::debug('Bootstrap with ' . get_class($component), __METHOD__);
                }
            }
        }

        foreach ($this->bootstrap as $mixed) {
            $component = null;
            if ($mixed instanceof \Closure) {
                Izi::debug('Bootstrap with Closure', __METHOD__);
                if (!$component = call_user_func($mixed, $this)) {
                    continue;
                }
            } elseif (is_string($mixed)) {
                if ($this->has($mixed)) {
                    $component = $this->get($mixed);
                } elseif ($this->hasModule($mixed)) {
                    $component = $this->getModule($mixed);
                } elseif (strpos($mixed, '\\') === false) {
                    throw new InvalidConfigException("Unknown bootstrapping component ID: $mixed");
                }
            }

            if (!isset($component)) {
                $component = Izi::createObject($mixed);
            }

            if ($component instanceof BootstrapInterface) {
                Izi::debug('Bootstrap with ' . get_class($component) . '::bootstrap()', __METHOD__);
                $component->bootstrap($this);
            } else {
                Izi::debug('Bootstrap with ' . get_class($component), __METHOD__);
            }
        }
    }

    /**
     * @return mixed|null
     */
    public function getDb()
    {
        return $this->get('db');
    }

    /**
     *
     */
    public function run()
    {
//        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        $this->state = self::STATE_BEFORE_REQUEST;
        $this->trigger(self::EVENT_BEFORE_REQUEST);
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
    public function on($eventName, $callback, $data = NULL, $append = true)
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
        return false;
    }

    private $_vendorPath;

    /**
     * Returns the directory that stores vendor files.
     * @return string the directory that stores vendor files.
     * Defaults to "vendor" directory under [[basePath]].
     */
    public function getVendorPath()
    {
        if ($this->_vendorPath === null) {
            $this->setVendorPath($this->getBasePath() . DIRECTORY_SEPARATOR . 'vendor');
        }

        return $this->_vendorPath;
    }

    /**
     * Sets the directory that stores vendor files.
     * @param string $path the directory that stores vendor files.
     */
    public function setVendorPath($path)
    {
        $this->_vendorPath = Izi::getAlias($path);
        Izi::setAlias('@vendor', $this->_vendorPath);
        Izi::setAlias('@bower', $this->_vendorPath . DIRECTORY_SEPARATOR . 'bower');
        Izi::setAlias('@npm', $this->_vendorPath . DIRECTORY_SEPARATOR . 'npm');
    }

    private $_runtimePath;

    /**
     * Returns the directory that stores runtime files.
     * @return string the directory that stores runtime files.
     * Defaults to the "runtime" subdirectory under [[basePath]].
     */
    public function getRuntimePath()
    {
        if ($this->_runtimePath === null) {
            $this->setRuntimePath($this->getBasePath() . DIRECTORY_SEPARATOR . 'runtime');
        }

        return $this->_runtimePath;
    }

    /**
     * Sets the directory that stores runtime files.
     * @param string $path the directory that stores runtime files.
     */
    public function setRuntimePath($path)
    {
        $this->_runtimePath = Izi::getAlias($path);
        Izi::setAlias('@runtime', $this->_runtimePath);
    }

    public function getTimeZone()
    {
        return date_default_timezone_get();
    }

    /**
     * Sets the time zone used by this application.
     * This is a simple wrapper of PHP function date_default_timezone_set().
     * Refer to the [php manual](https://secure.php.net/manual/en/timezones.php) for available timezones.
     * @param string $value the time zone used by this application.
     * @see https://secure.php.net/manual/en/function.date-default-timezone-set.php
     */
    public function setTimeZone($value)
    {
        date_default_timezone_set($value);
    }

    public function coreComponents()
    {
        return [
            'log' => ['class' => 'izi\log\Dispatcher'],
//            'view' => ['class' => 'izi\web\View'],
//            'formatter' => ['class' => 'izi\i18n\Formatter'],
//            'i18n' => ['class' => 'izi\i18n\I18N'],
//            'mailer' => ['class' => 'izi\swiftmailer\Mailer'],
//            'urlManager' => ['class' => 'izi\web\UrlManager'],
//            'assetManager' => ['class' => 'izi\web\AssetManager'],
//            'security' => ['class' => 'izi\base\Security'],
        ];
    }
}