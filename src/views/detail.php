<?php

use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $panel \Vjik\Yii2\Cycle\Debug\OrmPanel
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \Vjik\Yii2\Cycle\Debug\SearchModel
 */

echo Html::tag('h1', $panel->getName());

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'cycle-orm-panel-detailed-grid',
    'options' => ['class' => 'detail-grid-view table-responsive'],
    'filterModel' => $searchModel,
    'filterUrl' => $panel->getUrl(),
    'pager' => [
        'linkContainerOptions' => [
            'class' => 'page-item'
        ],
        'linkOptions' => [
            'class' => 'page-link'
        ],
        'disabledListItemSubTagOptions' => [
            'tag' => 'a',
            'href' => 'javascript:;',
            'tabindex' => '-1',
            'class' => 'page-link'
        ]
    ],
    'columns' => [
        [
            'attribute' => 'seq',
            'label' => 'Time',
            'value' => function ($model) {
                $millisecondsDiff = (int)(($model['timestamp'] - (int)$model['timestamp']) * 1000);
                return date('H:i:s.', $model['timestamp']) . sprintf('%03d', $millisecondsDiff);
            },
            'headerOptions' => [
                'class' => 'sort-numerical'
            ]
        ],
        [
            'attribute' => 'queryDuration',
            'value' => function ($model) {
                return $model['queryDuration'] !== null ? sprintf('%.1f ms', $model['queryDuration']) : '';
            },
            'options' => [
                'width' => '10%',
            ],
            'headerOptions' => [
                'class' => 'sort-numerical'
            ]
        ],
        [
            'attribute' => 'queryType',
            'value' => function ($model) {
                return $model['queryType'] !== null ? $model['queryType'] : '';
            },
            'filter' => $panel->getTypes(),
        ],
        [
            'attribute' => 'message',
            'value' => function ($model) {
                return $model['message'];
            },
            'options' => [
                'width' => '60%',
            ],
        ]
    ],
]);
