<p align="center">
    <img src="https://avatars0.githubusercontent.com/u/47758579" height="100px">   
    <h1 align="center">Panel "Cycle ORM" for Yii2 Debug</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/vjik/yii2-cycle-debug/v/stable.png)](https://packagist.org/packages/vjik/yii2-cycle-debug)
[![Total Downloads](https://poser.pugx.org/vjik/yii2-cycle-debug/downloads.png)](https://packagist.org/packages/vjik/yii2-cycle-debug)

![](https://i.ibb.co/18vqR14/yii2-cycle-debug.png)

## Installation

The preferred way to install this extension is through [composer](https://getcomposer.org/download/):

```
composer require vjik/yii2-cycle-debug --dev
```

Add logger to Cycle ORM config:

```php
'vjik/yii2-cycle' => [
    'dbal' => [
        'query-logger' => new \Vjik\Yii2\Cycle\Debug\LoggerFactory(),
        …
    ],
    …
],
``` 

Add panel to Debug config:

```php
'modules' => [
    'debug' => [
        'class' => \yii\debug\Module::class,
        'panels' => [
            'orm' => \Vjik\Yii2\Cycle\Debug\OrmPanel::class,
        ],
    ],
    …
],
``` 
