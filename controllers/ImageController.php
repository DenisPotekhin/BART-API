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

    public function actionImageFile($width, $height, $fullpath, $fullname)
    {
        //      $encodePath = rawurlencode($path);
        return [
            'image' => $width . $height . $fullpath . $fullname,
        ];
        $modelClass = $this->modelClass;
    }
}