<?php


namespace app\controllers;

use app\models\Image;
use Yii;
use yii\rest\ActiveController;

class ImageController extends ActiveController
{
    public $modelClass = 'app\models\Image';

    public function actions()
    {
        $actions = parent::actions();

        // disable all default actions
        unset($actions['index'], $actions['create'], $actions['view'], $actions['update'], $actions['delete']);

        return $actions;
    }

    public function actionFileImage($width, $height, $fullpath)
    {
  //      $encodePath = rawurlencode($path);
        return [
            'image' => $fullpath,
        ];
        $modelClass = $this->modelClass;
    }

    public function actionList($fullpath)
    {
        //      $encodePath = rawurlencode($path);
        return [
            'image' => $fullpath . 'test',
        ];
        $modelClass = $this->modelClass;
    }
}