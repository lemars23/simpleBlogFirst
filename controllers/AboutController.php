<?php

use Controllers\Controller;

class AboutController extends Controller
{
    protected $header = "/structs/header.php";
    // Path footer для homecontroller
    protected $footer = "/structs/footer.php";

    public function index()
    {
        $this->view->getView('aboutIndex');
    }

    public function top() 
    {
        $this->view->getView('aboutIndex');
    }
}