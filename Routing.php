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
    private function callController(array $controller)
    {
        // Имя контроллера без Controller без заглавного символа
        $controllerName = $controller["controller"];
        // Имя контроллера с Controller с заглавным символом
        $controllerExist = ucfirst($controllerName) . "Controller";
        // Путь контроллера
        $controllerPath = $_SERVER['DOCUMENT_ROOT'] . '/controllers/' . $controllerExist . '.php';

        
        // Проверка на существование файла
        if($this->fileExist($controllerPath)) {
            // Подключение файла
            require_once($controllerPath);
            // Проверка на существование класса контроллера
            if($this->controllerExist($controllerExist)) {
                // Действие контроллера
                $actionExist = $controller["action"];
                // Проверка на существование действия контроллера
                if($this->actionExist($controllerExist, $actionExist)) {
                    // Подключаю класс View
                    require_once($_SERVER['DOCUMENT_ROOT'] . "/views/View.php");
                    // Параметры действия контроллера
                    $paramsExist = $controller["params"];
                    // Создание контроллера, передаются параметры
                    $newController = new $controllerExist($paramsExist);
                    // Вызов действия контроллера
                    $newController->$actionExist();
                }
            }
        }
    }
    // Вызов контроллера, с первым шагом проверкой home контроллера и последующими других 
    private function callDefaultController(array $controller) 
    {
        // Имя контроллера без Controller без заглавного символа
        $action = $controller["controller"];
        
        if($this->actionHomeControllerExist($action)) {
            require_once($_SERVER['DOCUMENT_ROOT'] . "/controllers/Controller.php");
            // Подключаю класс View
            require_once($_SERVER['DOCUMENT_ROOT'] . "/views/View.php");
            // Вызов контроллера
            $controllerExist = $controller["controller"] = "HomeController";
            // Вызов действия
            $actionExist = $controller["action"] = $action;
            // Вызов параметров
            $paramsExist = $controller["params"] = array_slice($this->divideURI(), 1);            
            // Создание контроллера, передаются параметры
            $newController = new $controllerExist($paramsExist);
            // Вызов действия контроллера
            $newController->$actionExist();
        } else {
            $this->callController($this->convertValuesForController($this->divideURI()));
        }


    }
    
}