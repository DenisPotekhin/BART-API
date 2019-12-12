<?php


namespace app\controllers;

use app\models\Gallery;
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
        $size = getimagesize($fileName);
        $imageWidth = ($width == 0) ? $size[0] : $width;
        $imageHeight = ($height == 0) ? $size[1] : $height;
        return Image::resize($fileName, $imageWidth, $imageHeight);
    }

    public function actionImageFile($width, $height, $path, $name)
    {
        $modelClass = $this->modelClass;
        $fileName = fileEnCode($path , $name);
        $pathSave = Yii::getAlias('@webroot') . Yii::$app->params['uploadsPath'];
        $filePath = $pathSave . $fileName;
        $galleryId = Gallery::findOne(['path' => $path]);
        $image = $modelClass::findOne(['path' => $name, 'gallery_id' => $galleryId->id]);
        if (file_exists($filePath) && $image) {
            $fileResize = $pathSave . 'resize' . $fileName;
            Yii::$app->response->statusCode = 200;
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