<?php

namespace Vjik\Yii2\Cycle\Debug;

use yii\data\ArrayDataProvider;
use yii\debug\components\search\Filter;
use yii\debug\models\search\Base;

class SearchModel extends Base
{

    /**
     * @var string query type of the input search value
     */
    public $queryType;

    /**
     * @var int message attribute input search value
     */
    public $message;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['queryType', 'message'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'queryType' => 'Query Type',
            'message' => 'Query / Message',
        ];
    }

    /**
     * Returns data provider with filled models. Filter applied if needed.
     * @param array $models data to return provider for
     * @return ArrayDataProvider
     */
    public function search($models)
    {
        $dataProvider = new ArrayDataProvider([
            'allModels' => $models,
            'pagination' => false,
            'sort' => [
                'attributes' => ['seq', 'queryDuration', 'queryType', 'message'],
            ],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $filter = new Filter();
        $this->addCondition($filter, 'queryType', true);
        $this->addCondition($filter, 'message', true);
        $dataProvider->allModels = $filter->filter($models);

        return $dataProvider;
    }
}
