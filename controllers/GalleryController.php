<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\Image;
use Yii;
use yii\web\UploadedFile;

class GalleryController extends BaseController
{
    public $modelClass = 'app\models\Gallery';

    public function actions()
    {
        $actions = parent::actions();
        // NOTE: disable all default actions
        unset($actions['index'], $actions['create'], $actions['view'], $actions['update'], $actions['delete']);
        return $actions;
    }

    // NOTE: GET - index gallery (name = $path) with images
    public function actionListOne(string $path): array
    {
        $encodePath = rawurlencode($path);
        $modelClass = $this->modelClass;
        $gallery = $modelClass::findOne(['path' => $encodePath]);
        if ($gallery) {
            Yii::$app->response->statusCode = 200;
            $response = [
                'gallery' => $gallery,
            ];
        } else {
            Yii::$app->response->statusCode = 404;
            $response = [
                'HTTP status code 404' => 'Zvolená galéria neexistuje',
            ];
        }
        return $response;
    }

    // NOTE: GET - index all galleries with images
    public function actionListAll(): array
    {
        $modelClass = $this->modelClass;
        $query = $modelClass::find()->with('image')->all();
        if ($query) {
            Yii::$app->response->statusCode = 200;
            $response = [
                'galleries' => $query,
            ];
        } else {
            Yii::$app->response->statusCode = 500;
            $response = [
                'HTTP status code 500' => 'Nedefinovaná chyba',
            ];
        }
        return $response;
    }

    // NOTE: POST add new gallery (name = 'name')
    public function actionInsert(): array
    {
        $requestParam = Yii::$app->getRequest()->getBodyParam('name');
        if (!$requestParam) {
            Yii::$app->response->statusCode = 400;
            $response = [
                'HTTP status code 400' => 'Chybne zadaný request - nevhodný obsah podľa schémy.',
            ];
            return $response;
        }
        $modelClass = $this->modelClass;
        $path = rawurlencode($requestParam);
        $gallery = $modelClass::findOne(['path' => $path]);
        if ($gallery) {
            Yii::$app->response->statusCode = 409;
            $response = [
                'HTTP status code 409' => 'Galéria so zadaným názvom už existuje',
            ];
        }
        else {
            $newGallery = new $modelClass();
            $newGallery->path = $path;
            $newGallery->save();
            Yii::$app->response->statusCode = 201;
            $response = $newGallery;
        }
        return $response;
    }

    // NOTE: DELETE gallery (name = $path) with images in gallery
    public function actionErase(string $path): array
    {
        $encodePath = rawurlencode($path);
        $modelClass = $this->modelClass;
        $gallery = $modelClass::findOne(['path' => $encodePath]);
        if ($gallery) {
            $gallery->delete();
            Yii::$app->response->statusCode = 200;
            $response = [
                'HTTP status code 200' => 'Galéria/obrázok bola úspešne vymazaná',
            ];
        } else {
            Yii::$app->response->statusCode = 404;
            $response = [
                'HTTP status code 404' => 'Zvolená galéria/obrázok neexistuje',
            ];
        }
        return $response;
    }

    // NOTE: DELETE image (name = $name) in gallery (name = $path)
    public function actionEraseFile(string $path, string $name): array
    {
        $encodePath = rawurlencode($path);
        $modelClass = $this->modelClass;
        $gallery = $modelClass::findOne(['path' => $encodePath]);
        if ($gallery) {
            $image = Image::findOne(['path' => $name]);
            if ($image) {
                $image->deleteImage($encodePath . '%2F' . $name);
                $image->delete();
                Yii::$app->response->statusCode = 200;
                $response = [
                    'HTTP status code 200' => 'Obrázok bol úspešne vymazan',
                ];
            } else {
                Yii::$app->response->statusCode = 404;
                $response = [
                    'HTTP status code 404' => 'Zvolená obrázok neexistuje',
                ];
            }
        } else {
            Yii::$app->response->statusCode = 404;
            $response = [
                'HTTP status code 404' => 'Zvolená galéria neexistuje',
            ];
        }
        return $response;
    }

    // NOTE: POST add image ('image' = file) in gallery (name = $path)
    public function actionInsertFile(string $path): array
    {
        $encodePath = rawurlencode($path);
        $modelClass = $this->modelClass;
        $gallery = $modelClass::findOne(['path' => $encodePath]);
        if (!$gallery) {
            Yii::$app->response->statusCode = 404;
            $response = [
                'HTTP status code 404' => 'Galéria pre upload sa nenašla',
            ];
            return $response;
        }
        $uploadedFile = UploadedFile::getInstanceByName('image');
        if ($uploadedFile) {
            $fileName = $uploadedFile->name;
            // NOTE: I use encode file according template fileName = rawurlencode(fullpath)
            // for example fullpath = 'Hockey/hockey7.jpg' => fileName = 'Hokkey%2Fhockey7.jpg'
            $fileEnCodeName = fileEnCode($encodePath, $fileName);
            // NOTE: I use method (getPlaceToUploadImage) for choice upload placement, I realized my method,
            // but you can reinit it
            if ($uploadedFile->saveAs(getPlaceToUploadImage($fileEnCodeName))) {
                 $image = new Image();
                 $image->gallery_id = $gallery->id;
                 $image->path = $fileName;
                 $image->modified_at = time();
                 $image->save();
                 Yii::$app->response->statusCode = 201;
                 $response = [
                     'uploaded' => $image,
                 ];
            } else {
                Yii::$app->response->statusCode = 500;
                $response = [
                    'HTTP status code 500' => 'Nedefinovaná chyba',
                ];
            }
        } else {
            Yii::$app->response->statusCode = 400;
            $response = [
                'HTTP status code 404' =>'Chybný request - nenašiel sa súbor pre upload',
            ];
        }
        return $response;
    }

    public function actionError()
    {
        Yii::$app->response->statusCode = 404;
        return [
            'HTTP status code 404' => 'URL not found',
        ];
    }

}
