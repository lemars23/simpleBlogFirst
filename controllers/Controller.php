<?php
namespace Controllers;

use Views\View as View;

class Controller 
{
    // array: параметры 
    protected $params;
    // string: вид
    protected $view;
    
    // string: path header
    protected $header;
    // string: path header
    protected $footer;

    public function __construct(array $params)
    {
        // this->params = получить параметры
        $this->params = $params;
        // this->view = объект view
        $this->view = new View;
        // Присвоить path header в view из controller
        $this->view->header = $this->header;
        // Присвоить path footer в view из controller
        $this->view->footer = $this->footer;
    }

}