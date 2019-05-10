<?php namespace ss\components\cats\controllers;

class Svc extends \Controller
{
    public $singleton = true;

    // pagesSorter

    private $orders = [];

    public function setOrder()
    {
        $cat = $this->data['cat'];

        $this->orders[$cat->id] = $this->data['order'];
    }

    public function getOrder()
    {
        $cat = $this->data['cat'];

        return $this->orders[$cat->id] ?? [];
    }

    // pagesPublisher

    private $publishedLists = [];

    public function setPublishedList()
    {
        $cat = $this->data['cat'];

        $this->publishedLists[$cat->id] = $this->data['published_list'];
    }

    public function getPublishedList() // softlink to ss-components-pages-publisher
    {
        $cat = $this->data['cat'];

        if (!isset($this->publishedLists[$cat->id])) {
            $this->publishedLists[$cat->id] = aread($this->_protected('data', '\ss\components\pagesPublisher\app~:cat_' . $cat->id . '/pages_published.php')) ?? [];
        };

        return $this->publishedLists[$cat->id];
    }
}
