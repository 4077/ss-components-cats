<?php namespace ss\components\cats\cp\controllers\tile;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    private function triggerUpdate($catId)
    {
        pusher()->trigger('ss/container/' . $catId . '/update_pivot');
    }

    public function reload()
    {
        $this->c('<:reload', [], true);
    }

    public function toggleOpenInNewTab()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/open_in_new_tab', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function selectTemplate()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/template', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function updateImageDimension()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $field = $this->data('field');
            $value = $this->data('value');

            if (in($field, 'width, height') && is_numeric($value)) {
                ss()->cats->apComponentPivotData($pivot, path('tile/image', $field), $value);
                ss()->cats->resetImagesCache($pivot->cat);

                ss()->cats->resetProductsImagesCache(\ss\models\Cat::find(26141)); // todo hardcode категория образцов плитки

                $this->e('ss/cats/containers/updateImageDimension')->trigger(['pivot' => $pivot]); /// todo

                $this->widget('<:|', 'savedHighlight', $field);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }
}
