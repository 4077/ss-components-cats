<?php namespace ss\components\cats\ui\controllers;

class Tile extends \Controller
{
    private $cat;

    public function __create()
    {
        if ($this->cat = $this->unpackModel('cat')) {
            $this->instance_($this->cat->id);
        } else {
            $this->lock();
        }
    }

    public function reload()
    {
        $cat = $this->cat;

        $pivot = $this->unpackModel('pivot');

        $this->data(false, $this->c('>app')->renderTileData($cat, $pivot));

        $this->jquery($this->_selector($this->data('template') . ':|'))->replace($this->view());
    }

    public function view()
    {
        $v = $this->v($this->data('template') . '|');

        $cat = $this->cat;

        $v->assign([
                       'CLASS'             => $this->data('class'),
                       'NAME'              => ss()->cats->getShortName($cat),
                       'DESCRIPTION'       => $cat->description,
                       'URL'               => abs_url($cat->route_cache),
                       'BLANK_TARGET_ATTR' => $this->data('open_in_new_tab') ? attr('target', '_blank') : ''
                   ]);

        $image = $this->c('\std\images~:first', [
            'model'       => $cat,
            'query'       => $this->data('image/query') ?: '225 200 fill',
            'href'        => $this->data('image/href'),
            'cache_field' => 'images_cache'
        ]);

        if ($image) {
            $v->assign('IMAGE', $image->view);
        }

//        $this->se('ss/cat/any/update_images')->rebind('>app:dropCache');

        $css = $this->data('css') or
        $css = ':\css\std~';

        $cssVars = [
            'imageWidth'  => ($this->data('image/width') ?: 225) . 'px',
            'imageHeight' => ($this->data('image/height') ? $this->data('image/height') . 'px' : 'auto')
        ];

        $cssVarsMd5 = '_' . jmd5($cssVars); // '_' - защита от преобразования в число когда первые сиволы равны "0b"

        $cssVars['vmd5'] = $cssVarsMd5;

        $v->assign('VMD5', $cssVarsMd5);

        $this->css($css . '|' . $cssVarsMd5)->setVars($cssVars);

        return $v;
    }
}
