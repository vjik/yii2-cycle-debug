<?php
/**
 * @var $panel yii\debug\panels\DbPanel
 * @var $queryCount int
 * @var $queryTime int
 */
if ($queryCount) {
    ?>
    <div class="yii-debug-toolbar__block">
        <a href="<?= $panel->getUrl() ?>"
           title="Executed <?= $queryCount ?> database queries which took <?= $queryTime ?>.">
            ORM
            <span class="yii-debug-toolbar__label yii-debug-toolbar__label_info"><?= $queryCount ?></span>
            <span class="yii-debug-toolbar__label"><?= $queryTime ?></span>
        </a>
    </div>
    <?php
}
