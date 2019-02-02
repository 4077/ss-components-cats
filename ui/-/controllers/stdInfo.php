<?php namespace ss\components\cats\ui\controllers;

class StdInfo extends \Controller
{
    private $cat;

    public function __create()
    {
        if ($this->cat = $this->data('cat')) {
            $this->instance_($this->cat->id);
        } else {
            $this->lock();
        }
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $cat = $this->cat;

        if ($name = ss()->cats->getName($cat)) {
            $v->assign('name', [
                'CONTENT' => $name
            ]);
        }

        if ($description = $cat->description) {
            $v->assign('description', [
                'CONTENT' => $description
            ]);
        }

        $this->css();

        $this->widget(':|', [
            'catId' => $cat->id
        ]);

        return $v;
    }
}
