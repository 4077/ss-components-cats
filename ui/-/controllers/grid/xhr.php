<?php namespace ss\components\cats\ui\controllers\grid;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function reload()
    {
        if ($cat = \ss\models\Cat::find($this->data('cat_id'))) {
            $this->c('<:reload', ['cat' => $cat], 'pivot');
        }
    }

    public function loadTiles()
    {
        if ($cat = \ss\models\Cat::find($this->data('cat_id'))) {
            $this->c('<:loadTiles', ['cat' => $cat], 'pivot, offset');
        }
    }

    public function pageDialog()
    {
        if ($cat = $this->unxpackModel('cat')) {
            if (ss()->cats->isEditable($cat)) {
                $this->c('\ss\cats\cp dialogs:page|ss/cats', [
                    'cat' => $cat
                ]);
            }
        }
    }

    public function arrange()
    {
        if ($cat = $this->unxpackModel('cat') and $this->dataHas('sequence array')) {
            foreach ($this->data['sequence'] as $n => $nodeId) {
                if (is_numeric($n) && $node = \ss\models\Cat::find($nodeId)) {
                    $node->update(['position' => ($n + 1) * 10]);
                }
            }

            $this->se('ss/pages/any/arrange_pages')->trigger();

            pusher()->trigger('ss/cat/update_cats', [
                'id' => $cat->parent_id
            ]);

            pusher()->trigger('ss/tree/update_cats', [
                'id' => $cat->tree_id
            ]);
        }
    }
}
