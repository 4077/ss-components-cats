<?php namespace ss\components\cats\cp\controllers\grid;

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

    public function toggleName()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/name_display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleDescription()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/description_display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleLimit()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/limit/enabled', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function updateLimitValue()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/limit/value', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);

            $this->widget('<:|', 'savedHighlight', 'limit_value');
        }
    }

    public function updateLimitLoadValue()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            if (!$value || $value != (int)$value) {
                $value = '∞';
            }

            ss()->cats->apComponentPivotData($pivot, 'grid/limit/load_value', $value);

            $this->triggerUpdate($pivot->cat_id);

            $this->widget('<:|', 'savedHighlight', 'limit_load_value');
        }
    }
}
