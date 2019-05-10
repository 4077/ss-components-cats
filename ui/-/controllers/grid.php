<?php namespace ss\components\cats\ui\controllers;

class Grid extends \Controller
{
    private $cat;

    private $pivot;

    public function __create()
    {
        $this->cat = $this->data('cat');
        $this->pivot = $this->unpackModel('pivot');

        $this->instance_($this->cat->id);
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $ss = ss();

//        /**
//         * @var $cTileApp \ss\components\cats\ui\controllers\tile\App
//         */
//        $cTileApp = $this->c('@tile/app');

        $cat = $this->cat;
        $pivot = $this->pivot;

        $pivotXPack = xpack_model($pivot);

        $globalEditable = $ss->globalEditable();
        $catEditable = $ss->cats->isEditable($cat);

        $pivotData = _j($pivot->data);

        if (ap($pivotData, 'grid/name_display')) {
            $v->assign('name', [
                'CONTENT' => ss()->cats->getName($cat)
            ]);
        }

        if (ap($pivotData, 'grid/description_display')) {
            $v->assign('description', [
                'CONTENT' => $cat->description
            ]);
        }

        $limit = false;

        if ($limitEnabled = ap($pivotData, 'grid/limit/enabled')) {
            $limit = ap($pivotData, 'grid/limit/value');
        }

        $pages = $this->getVisiblePages($cat, $limit);

        foreach ($pages as $page) {
            $v->assign('tile', [
                'CONTENT' => $this->tileView($page, $pivot)
            ]);
        }

        $offset = count($pages);

        if ($limitEnabled) {
            if ($this->pagesCount > $offset) {
                $loadLimit = ap($pivotData, 'grid/limit/load_value');

                $loadLimit = $loadLimit == '∞' ? PHP_INT_MAX : $loadLimit;

                $less = $this->pagesCount - $offset;

                $v->assign('load_more_button', [
                    'LOAD_LIMIT'        => $less <= $loadLimit ? $less : $loadLimit,
                    'LESS_COUNT'        => $less,
                    'LESS_HIDDEN_CLASS' => $less <= $loadLimit ? 'hidden' : ''
                ]);
            }
        }

        if ($globalEditable && $catEditable) {
            $this->c('\std\ui sortable:bind', [
                'selector'       => $this->_selector('|') . ' > .tiles',
                'path'           => '>xhr:arrange',
                'items_id_attr'  => 'cat_id',
                'data'           => [
                    'cat' => xpack_model($cat)
                ],
                'plugin_options' => [
                    'distance' => 15
                ]
            ]);
        }

        $this->css();

        $this->widget(':|', [
            '.e'          => [
                'ss/container/' . $cat->id . '/update_name'        => 'renderName',
                'ss/container/' . $cat->id . '/update_description' => 'renderDescription',
                // 'ss/tree/' . $cat->tree_id . '/update_pages'       => 'mr.reload',
                // 'ss/container/' . $cat->id . '/update_pivot'       => 'mr.reload',
            ],
            '.r'          => [
                'reload'    => $this->_abs('>xhr:reload', [
                    'cat_id' => $cat->id,//?
                    'pivot'  => $pivotXPack
                ]),
                'loadTiles' => $this->_abs('>xhr:loadTiles', [
                    'cat_id' => $cat->id,//?
                    'pivot'  => $pivotXPack
                ])
            ],
            'offset'      => $offset,
            'catId'       => $cat->id,
            'parentCatId' => $cat->parent_id
        ]);

        return $v;
    }

    public function tileView($page, $pivot)
    {
        $v = $this->v('>tile');

        /**
         * @var $cTileApp \ss\components\cats\ui\controllers\tile\App
         */
        $cTileApp = $this->c('@tile/app');

        $v->assign([
                       'CAT_ID'  => $page->id,
                       'CONTENT' => $this->c('@tile:view', $cTileApp->renderTileData($page, $pivot))
                   ]);

        $globalEditable = ss()->globalEditable();

        if ($globalEditable) {
            $v->assign('cp');

            if (ss()->cats->isEditable($page)) {
                $v->assign([
                               'PAGE_DIALOG_BUTTON' => $this->c('\std\ui button:view', [
                                   'path'  => '>xhr:pageDialog',
                                   'data'  => [
                                       'cat' => xpack_model($page)
                                   ],
                                   'class' => 'product_dialog button',
                                   'icon'  => 'fa fa-cog'
                               ])
                           ]);
            }

            $v->assign('not_published_mark', [
                'HIDDEN_CLASS' => $page->published ? 'hidden' : ''
            ]);

            if ($page->hiddenByPublisher) {
                $v->assign('hidden_by_publisher_mark', [
                    'HIDDEN_CLASS' => $page->published ? 'hidden' : ''
                ]);
            }
        }

        return $v;
    }

    public function loadTiles()
    {
        $offset = $this->data('offset');

        $cat = $this->cat;
        $pivot = $this->pivot;

        $pivotData = _j($pivot->data);

        $limit = ap($pivotData, 'grid/limit/value');
        $loadLimit = ap($pivotData, 'grid/limit/load_value');

        $loadLimit = $loadLimit == '∞' ? PHP_INT_MAX : $loadLimit;

        $pages = $this->getVisiblePages($cat, $loadLimit, $offset);

        $output = '';
        foreach ($pages as $n => $page) {
            $output .= $this->tileView($page, $pivot)->render();
        }

        $this->jquery('|')->find(".tiles")->append($output);

        $less = $this->pagesCount - $offset - $loadLimit;

        $this->widget('|', 'loadDataUpdate', [
            'offset' => $offset + count($pages),
            'limit'  => $less <= $loadLimit ? $less : $loadLimit,
            'less'   => $less
        ]);
    }

    private $pagesCount;

    private function getVisiblePages($cat, $limit = false, $offset = 0)
    {
        $ss = ss();

        $globalEditable = $ss->globalEditable();

        $pages = table_rows_by_id($cat->containedPages()->orderBy('position')->get());

        // pagesSorter {
        $order = $this->c('^ svc:getOrder', [
            'cat' => $cat
        ]);

        if ($order) {
            $pages = map($pages, $order);
        }
        // }

        // pagesPublisher {
        $publishedList = $this->c('^ svc:getPublishedList', [
            'cat' => $cat
        ]);
        // }

        $output = [];

        foreach ($pages as $page) {
            if ($publishedList) {
                $page->hiddenByPublisher = !($publishedList[$page->id] ?? true);
            } else {
                $page->hiddenByPublisher = false;
            }

            $catVisible = $page->enabled && (($page->published && !$page->hiddenByPublisher) || $globalEditable);

            if ($catVisible) {
                $output[] = $page;
            }
        }

        $this->pagesCount = count($output);

        if ($limit) {
            $output = array_slice($output, $offset, $limit);
        }

        return $output;
    }
}
