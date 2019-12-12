<?php


namespace app\controllers;

use app\models\Image;
use Yii;
use yii\rest\ActiveController;
use tpmanc\imagick\Imagick;

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

    public function actionImageFile($width, $height, $path, $name)
    {
        $fileName = fileEnCode($path , $name);
        $pathSave = Yii::getAlias('@webroot') . Yii::$app->params['uploadsPath'];
        $filePath = $pathSave . $fileName;
    //    $img = Imagick::open($filePath);
    //    $img->resize($width, $height);
        if (file_exists($filePath)) {
            Yii::$app->response->statusCode = 200;
            return Yii::$app->response->sendFile($filePath);
        } else {
            Yii::$app->response->statusCode = 404;
            return [
                'HTTP status code 404' => 'Obrázok sa nenašiel',
            ];
        }
    }
}