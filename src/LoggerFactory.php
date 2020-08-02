<?php

namespace Vjik\Yii2\Cycle\Debug;

use Vjik\Yii2\Psr\LoggerProxy\LoggerProxy;
use Yii;

class LoggerFactory
{

    public function __invoke()
    {
        $logger = new LoggerProxy(Yii::getLogger());
        $logger->setDefaultCategory('cycle-orm');
        $logger->setPrepareMessage(function ($message, $context) {
            if (isset($context['elapsed'])) {
                return 'Query (' . $context['elapsed'] . ' µs):' . "\n" . $message;
            }
            return null;
        });
        return $logger;
    }
}
