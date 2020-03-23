Quiz service for yii2
=====================
Quiz server for yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist it-yakutia/yii2-quiz "*"
```

or add

```
"it-yakutia/yii2-quiz": "*"
```

to the require section of your `composer.json` file.

Add migration path in your console config file:

```
'migrationPath' => [
    ...
    '@vendor/it-yakutia/quiz/src/migrations',
],
```

Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= Url::toRoute(['/quiz/quiz/index']); ?>
```