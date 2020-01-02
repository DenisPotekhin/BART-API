<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\Gallery;
use Yii;
use yii\imagine\Image;


class ImageController extends BaseController
{
    public $modelClass = 'app\models\Image';

    public function actions()
    {
        $actions = parent::actions();
        // NOTE: disable all default actions
        unset($actions['index'], $actions['create'], $actions['view'], $actions['update'], $actions['delete']);
        return $actions;
    }

    // NOTE: return resize format file from base file
    public function getResizeFile(int $width, int $height, string $fileName)
    {
        $size = getimagesize($fileName);
        $imageWidth = ($width == 0) ? $size[0] : $width;
        $imageHeight = ($height == 0) ? $size[1] : $height;
        return Image::resize($fileName, $imageWidth, $imageHeight);
    }

    // NOTE: GET image (name = $name) with width and height from gallery (name = $path)
    public function actionImageFile(int $width, int $height, string $path, string $name)
    {
        $modelClass = $this->modelClass;
        $fileName = fileEnCode($path, $name);
        // NOTE: I use method (getPlaceToImage) for definition placement for looking for image
        $filePath = getPlaceToImage($fileName);
        $galleryId = Gallery::findOne(['path' => rawurlencode($path)]);
        $image = $modelClass::findOne(['path' => $name, 'gallery_id' => $galleryId->id]);
        if (file_exists($filePath) && $image) {
            Yii::$app->response->statusCode = 200;
            return Yii::$app->response
                ->sendFile($this->getResizeFile($width, $height, $filePath)->show('jpg'));
        } else {
            Yii::$app->response->statusCode = 404;
            return [
                'HTTP status code 404' => 'Obrázok sa nenašiel',
            ];
        }
    }
}
