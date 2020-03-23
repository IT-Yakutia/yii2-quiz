<?php

namespace ityakutia\quiz;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface {

    public function bootstrap($app)
    {
        $app->setModule('quiz', 'ityakutia\quiz\Module');
    }
}