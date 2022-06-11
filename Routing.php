<?php
/*
*   Роутинг приложения
*/
namespace Route;

class Routing
{
    // Вызов функций при создание объекта
    public function __construct()
    {
        $this->callDefaultController($this->convertValuesForController($this->divideURI()));
    }
    // Запрет на копирование объекта
    private function __clone(){}
    // Получить uri страницы
    private function getURI()
    {
        // Возвращает супер глобальную переменную Server с ключом uri
        return $_SERVER['REQUEST_URI'];
    }
    // Разделение uri
    private function divideURI()
    {
        if($this->getURI() !== "/") {
            $uri = explode("/", $this->getURI());
            array_shift($uri);
            return $uri;
        } else {
            return ["home"];
        }
    }
    // Возврат контроллера, действия и параметров
    private function convertValuesForController(array $controller)
    {
        $newController = array_splice($controller, 0, 1)[0];
        $newControllerAction = array_splice($controller, 0, 1)[0] ?? 'index';
        $newControllerActionParams = array_splice($controller, 0);
        return [
            "controller" => $newController,
            "action" => $newControllerAction,
            "params" => $newControllerActionParams
        ];
    }
    // Существование файла
    private function fileExist(string $path) 
    {
        if(file_exists($path)) {
            return true;
        } else {
            return false;
        }
    }
    // Существование контроллера
    private function controllerExist(string $controllerName) 
    {
        if(class_exists($controllerName)) {
            return true;
        } else {
            return false;
        }
    }
    // Существование действия
    private function actionExist(string $controllerName, string $actionName) 
    {
        if(method_exists($controllerName, $actionName)) {
            return true;
        } else {
            return false;
        }
    }
    // Проверка home контроллера на существование действия
    private function actionHomeControllerExist(string $method)
    {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/controllers/HomeController.php');
        if(method_exists("HomeController", $method)) {
            return true;
        } else {
            return false;
        }
    }
    // Вызов контроллера
    private function callController(string $controller, string $action, ?array $params, string $controllerPath)
    {
        // Проверка на существование файла
        if($this->fileExist($controllerPath)) {
            // Подключение файла
            require_once($controllerPath);
            // Проверка на существование класса контроллера
            if($this->controllerExist($controller)) {
                // Проверка на существование действия контроллера
                if($this->actionExist($controller, $action)) {
                    // Подключаю класс View
                    require_once($_SERVER['DOCUMENT_ROOT'] . "/views/View.php");
                    // Создание контроллера, передаются параметры
                    $newController = new $controller($params);
                    // Вызов действия контроллера
                    $newController->$action();
                }
            }
        }
    }
    // Вызов контроллера, первый вызов по url controller, если не находит то проверка и вызов homecontroller-а
    private function callDefaultController(array $controller) 
    {
        $action = $controller["controller"];
        
        if($this->fileExist($_SERVER['DOCUMENT_ROOT'] . '/controllers/' . ucfirst($controller["controller"]) . "Controller.php")) {
            $controllerPath = $_SERVER['DOCUMENT_ROOT'] . '/controllers/' . ucfirst($controller["controller"]) . "Controller.php";
            
            require_once($controllerPath);

            if($this->controllerExist(ucfirst($controller["controller"]) . "Controller")) {
                $mainController = ucfirst($controller["controller"]) . "Controller";

                if($this->actionExist(ucfirst($controller["controller"]) . "Controller", $controller["action"])) {
                    $action = $controller["action"];
                    $params = $controller["params"];

                    $this->callController($mainController, $action, $params, $controllerPath);
                }
            }
        } else {
            $action = $controller["controller"];
            $mainController = "HomeController";
            $params = array_merge([$controller["action"]], $controller["params"]);
            $controllerPath = $_SERVER['DOCUMENT_ROOT'] . '/controllers/HomeController.php';

            
            if($this->actionHomeControllerExist($action)) {
                $this->callController($mainController, $action, $params, $controllerPath);
            }
        }
    }
}