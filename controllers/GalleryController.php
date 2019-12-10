<?php


namespace app\controllers;

use app\models\Gallery;
use Yii;
use yii\rest\ActiveController;
use yii\db\BaseActiveRecord;

class GalleryController extends ActiveController
{
    public $modelClass = 'app\models\Gallery';

    public function actionNew()
    {
        $modelClass = $this->modelClass;

        $query = $modelClass::find()->with('image')->all();

        return [
            'galleries' => $query,
        ];
    }
}