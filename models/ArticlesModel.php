<?php
namespace Models;

class ArticlesModel extends Model 
{
    protected $table = "articles";

    protected $id;

    protected $title;

    protected $text;

    protected $user_id;

    protected $created_at;

    public function getAll() 
    {
        return Model::execute("SELECT * FROM `{$this->table}`", [], ArticlesModel::class);
    }

    public function getOneArticle(int $id)
    {
        return Model::execute("SELECT * FROM `{$this->table}` WHERE `id` = :id", ["id" => $id], ArticlesModel::class)[0];
    }

    public function getId() 
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
}