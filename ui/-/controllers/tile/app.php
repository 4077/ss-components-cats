<?php namespace ss\components\cats\ui\controllers\tile;

class App extends \Controller
{
    public function dropCache() // если будет кеширование плиток категорий
    {
        if ($cat = \ss\models\Cat::find($this->data('cat_id'))) {

        }
    }

    public function renderTileData(\ss\models\Cat $cat, \ss\models\CatComponent $pivot)
    {
        $pivotData = _j($pivot->data);

        $data = $pivotData['tile'] ?? [];

        if (isset($data['template'])) {
            $templates = dataSets()->get('ss/components/cats:tiles_templates');

            $data['template'] = $templates[$data['template']]['path'] ?? '';
        } else {
            $data['template'] = '';
        }

        $data['css'] = $data['template'];

        if (isset($data['image'])) {
            $data['image']['query'] = ($data['image']['width'] ?? '-') . ' ' . ($data['image']['height'] ?? '-') . ' ' . ($data['image']['mode'] ?? 'fill');
        }

        $data['cat'] = $cat;
        $data['pivot'] = $pivot;

        return $data;
    }
}
