<?php


namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\imagine\Image;


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

    public function resizeFile($width, $height, $fileName)
    {

        return Image::resize($fileName, $width, $height);
    }

    public function actionImageFile($width, $height, $path, $name)
    {
        $fileName = fileEnCode($path , $name);
        $pathSave = Yii::getAlias('@webroot') . Yii::$app->params['uploadsPath'];
        $filePath = $pathSave . $fileName;
    //    $img = Imagick::open($filePath);
    //    $img->resize($width, $height);
    //    return $this->resizeFile($width, $height, $filePath);
        if (file_exists($filePath)) {
            $fileResize = $pathSave . 'resize' . $fileName;
            Yii::$app->response->statusCode = 200;
    //        $this->resizeFile($width, $height, $filePath)->save($fileResize);
            return Yii::$app->response
                ->sendFile($this->resizeFile($width, $height, $filePath)->show('jpg'));
        } else {
            Yii::$app->response->statusCode = 404;
            return [
                'HTTP status code 404' => 'Obrázok sa nenašiel',
            ];
        }
    }
}