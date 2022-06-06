<?php
namespace Views;

class View
{
    // string: path header
    public $header;
    // string: path footer
    public $footer;
    
    // view: Подключить шаблон если он существует
    private function getFile(?string $file, ?array $data = [])
    {
        extract($data, EXTR_PREFIX_SAME, "another");

        if($this->verifyViewExist($file)) {    
            require_once($_SERVER['DOCUMENT_ROOT'] . "/views/" . $file);
        }
    }

    // true: Проверка на существование файла 
    private function verifyViewExist(string $file)
    {
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/views/" . $file)) {
            return true;
        } else {
            return false;
        }
    }
    // true: Вызов вида либо ошибка
    private function callView(string $viewPath)
    {
        if($this->verifyViewExist($viewPath)) {
            return true;
        } else {
            die("<h1 class='error'>View path is wrong</h1>");
        }
    }
    // view: Получить вид
    public function getView(string $viewPath, ?array $data = [])
    {
        $viewPath = ucfirst($viewPath) . "View.php";
        if($this->callView($viewPath)) {
            // variables: Создание переменных из аргумента data
            extract($data, EXTR_PREFIX_SAME, "another");
            
            // view: Получить шаблон header
            $getHeader = function(?string $header = "") use ($title) {
                $header = (empty($header)) ? $this->header : $header;
                
                $this->getFile($header, ["title" => $title]);
            };

            // view: Получить шаблон footer
            $getFooter = function(?string $footer = "") {
                $footer = (empty($footer)) ? $this->footer : $footer;

                $this->getFile($footer);
            };
            
            // view: Получить главный шаблон
            require_once($viewPath);
        } 
    }
}