<?php

namespace Vjik\Yii2\Cycle\Debug;

use Cycle\ORM\ORMInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\debug\Panel;
use yii\log\Logger;

class OrmPanel extends Panel
{

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'Cycle ORM';
    }

    /**
     * @inheritDoc
     */
    public function getSummary()
    {
        return Yii::$app->getView()->renderFile(__DIR__ . '/views/summary.php', [
            'panel' => $this,
            'queryCount' => $this->getQueryCount(),
            'queryTime' => number_format($this->getTotalQueryTime()) . ' ms',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getDetail()
    {
        $searchModel = new SearchModel();
        $searchModel->load(Yii::$app->getRequest()->getQueryParams());

        $dataProvider = $searchModel->search($this->getOrmMessages());
        $dataProvider->getSort()->defaultOrder = [
            'seq' => SORT_ASC
        ];

        return Yii::$app->getView()->renderFile(__DIR__ . '/views/detail.php', [
            'panel' => $this,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @return int
     */
    private function getQueryCount(): int
    {
        $count = 0;
        foreach ($this->getOrmMessages() as $ormMessage) {
            if ($ormMessage['queryType'] !== null) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @return float
     */
    private function getTotalQueryTime(): float
    {
        $time = 0;
        foreach ($this->getOrmMessages() as $ormMessage) {
            if ($ormMessage['queryDuration'] !== null) {
                $time += $ormMessage['queryDuration'];
            }
        }
        return (float)$time;
    }

    /**
     * Returns array query types
     * @return array
     */
    public function getTypes(): array
    {
        return array_reduce(
            $this->getOrmMessages(),
            function ($result, $ormMessage) {
                if ($ormMessage['queryType']) {
                    $result[$ormMessage['queryType']] = $ormMessage['queryType'];
                }
                return $result;
            },
            []
        );
    }

    /**
     * @var array
     */
    private $ormMessages;

    /**
     * @return array [seq, timestamp, levelName, message, queryType, queryDuration]
     */
    private function getOrmMessages()
    {
        if ($this->ormMessages === null) {
            $this->ormMessages = [];
            foreach (isset($this->data['messages']) ? $this->data['messages'] : [] as $seq => $message) {
                $this->ormMessages[] = $this->makeOrmMessage($seq, $message);
            }
        }

        return $this->ormMessages;
    }

    /**
     * @param int $seq
     * @param array $message
     * @return array [seq, timestamp, levelName, message, queryType, queryDuration]
     */
    private function makeOrmMessage(int $seq, array $message): array
    {
        $ormMessage = [
            'seq' => $seq,
            'timestamp' => $message[3],
            'levelName' => Logger::getLevelName($message[1]),
            'message' => null,
            'queryType' => null,
            'queryDuration' => null,
        ];

        if (preg_match('/^Query \(([\d\.]+) ms\):\s+(.*)/us', $message[0], $matches)) {
            $ormMessage['message'] = $matches[2];
            $ormMessage['queryDuration'] = (float)$matches[1];
            $ormMessage['queryType'] = $this->getQueryType($ormMessage['message']);
        } else {
            $ormMessage['message'] = $message[0];
        }

        return $ormMessage;
    }

    /**
     * Returns database query type.
     * @param string $query
     * @return string query type such as select, insert, delete, etc.
     */
    private function getQueryType(string $query): string
    {
        preg_match('/^([a-zA-z]*)/', ltrim($query), $matches);
        return $matches ? mb_strtoupper($matches[0], 'utf8') : '';
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        return [
            'messages' => $this->getLogMessages(0, ['cycle-orm'])
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        try {
            Yii::$container->get(ORMInterface::class);
        } catch (InvalidConfigException $exception) {
            return false;
        }

        return parent::isEnabled();
    }
}
