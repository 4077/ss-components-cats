<?php namespace ss\components\cats\cp\controllers;

class Tile extends \Controller
{
    private $pivot;

    public function __create()
    {
        if ($this->pivot = $this->unpackModel('pivot')) {
            $this->instance_($this->pivot->id);
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

        $pivot = $this->pivot;
        $pivotXPack = xpack_model($pivot);

        $pivotData = _j($pivot->data);

        $tileData = ap($pivotData, 'tile');

        $openInNewTab = ap($tileData, 'open_in_new_tab');

        $v->assign([
                       'TEMPLATE_SELECTOR'           => $this->templateSelectorView(),
                       'IMAGE_WIDTH'                 => ap($tileData, 'image/width'),
                       'IMAGE_HEIGHT'                => ap($tileData, 'image/height'),
                       'OPEN_IN_NEW_TAB_VIEW_TOGGLE' => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleOpenInNewTab',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$openInNewTab
                           ],
                           'class'   => 'toggle ' . ($openInNewTab ? 'enabled' : ''),
                           'content' => $openInNewTab ? 'да' : 'нет'
                       ])
                   ]);

        $this->css();

        $this->widget(':|', [
            'paths' => [
                'updateImageDimension'  => $this->_p('>xhr:updateImageDimension'),
                'updateCartbuttonLabel' => $this->_p('>xhr:updateCartbuttonLabel')
            ],
            'pivot' => $pivotXPack
        ]);

        $this->e('ss/cats/containers/updatePivot')->rebind(':reload');

        return $v;
    }

    public function templateSelectorView()
    {
        $pivot = $this->pivot;

        $templates = dataSets()->get('ss/components/cats:tiles_templates');

        $items = [];
        foreach ($templates as $name => $data) {
            $items[$name] = $data['label'];
        }

        return $this->c('\std\ui select:view', [
            'path'     => '>xhr:selectTemplate',
            'data'     => [
                'pivot' => xpack_model($pivot)
            ],
            'items'    => $items,
            'selected' => ss()->cats->apComponentPivotData($pivot, 'tile/template')
        ]);
    }
}
