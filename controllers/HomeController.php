<?php
use Controllers\Controller;

use Models\ArticlesModel;

class HomeController extends Controller
{
    // Path header для homecontroller
    protected $header = "/structs/header.php";
    // Path footer для homecontroller
    protected $footer = "/structs/footer.php";

    // Homecontroller index
    public function index()
    {
        $art = new ArticlesModel();

        $result = $art->getAll();

        $id = $result[0]->getCreatedAt();


        $this->view->getView(
            'home', 
            [
                "title" => "HomePage#1",
                "e" => [123, 321321],
                "path" => "Hello Name",
                "result" => $id
            ]
        );
    }

    public function article()
    {
        $art = new ArticlesModel();
        $article = $art->getOneArticle($this->params[0]);

        $id = $article->getId();
        $title = $article->getTitle();
        $text = $article->getText();
        $date = $article->getCreatedAt();
        
        
        $this->view->getView("article", 
        [
            "title" => "Aticle #" . $id,
            "titlePage" => $title,
            "text" => $text,
            "date" => $date
        ]);
    
    }



  
}
