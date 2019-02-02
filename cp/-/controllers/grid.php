<?php namespace ss\components\cats\cp\controllers;

class Grid extends \Controller
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

        $gridData = ap($pivotData, 'grid');

        $limitEnabled = ap($gridData, 'limit/enabled');

        $nameDisplay = ap($gridData, 'name_display');
        $descriptionDisplay = ap($gridData, 'description_display');

        $v->assign([
                       'DISPLAY_NAME_TOGGLE'        => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleName',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$nameDisplay
                           ],
                           'class'   => 'toggle ' . ($nameDisplay ? 'enabled' : ''),
                           'content' => $nameDisplay ? 'да' : 'нет'
                       ]),
                       'DISPLAY_DESCRIPTION_TOGGLE' => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleDescription',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$descriptionDisplay
                           ],
                           'class'   => 'toggle ' . ($descriptionDisplay ? 'enabled' : ''),
                           'content' => $descriptionDisplay ? 'да' : 'нет'
                       ]),
                       'LIMIT_TOGGLE'               => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleLimit',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$limitEnabled
                           ],
                           'class'   => 'toggle ' . ($limitEnabled ? 'enabled' : ''),
                           'content' => $limitEnabled ? 'вкл.' : 'выкл.'
                       ]),
                       'LIMIT_VALUE'                => ap($gridData, 'limit/value'),
                       'LIMIT_LOAD_VALUE'           => ap($gridData, 'limit/load_value'),
                       'TILE'                       => $this->c_('@tile:view')
                   ]);

        if ($limitEnabled) {
            $v->assign('limit_enabled');
        }

        $this->css();

        $this->widget(':|', [
            '.e' => [
                'ss/container/' . $pivot->cat_id . '/update_pivot' => 'mr.reload'
            ],
            '.r' => [
                'updateLimitValue'     => $this->_abs('>xhr:updateLimitValue', ['pivot' => $pivotXPack]),
                'updateLimitLoadValue' => $this->_abs('>xhr:updateLimitLoadValue', ['pivot' => $pivotXPack]),
                'reload'               => $this->_abs('>xhr:reload', ['pivot' => $pivotXPack])
            ]
        ]);

        return $v;
    }
}
