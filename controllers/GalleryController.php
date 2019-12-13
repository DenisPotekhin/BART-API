<?php


namespace app\controllers;

use app\models\Image;
use Yii;
use yii\rest\ActiveController;
use yii\web\UploadedFile;

class GalleryController extends ActiveController
{
    public $modelClass = 'app\models\Gallery';

    public function actions()
    {
        $actions = parent::actions();
        // disable all default actions
        unset($actions['index'], $actions['create'], $actions['view'], $actions['update'], $actions['delete']);
        return $actions;
    }

    public function actionListOne($path)
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

    public function actionListAll()
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

    public function actionInsert()
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

    public function actionErase($path)
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

    public function actionEraseFile($path, $name)
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

    public function actionInsertFile($path)
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
            $image = new Image();
            $image->gallery_id = $gallery->id;
            //get the uploaded file name
            $fileName = $uploadedFile->name;
            $image->path = $fileName;
            //pathinfo() returns more info about the $uploadFile
            $pathInfo = pathinfo($uploadedFile);
            //create a new filename to avoid file collission
            $fileName = $path . '%2F' . $pathInfo['filename'];
            //get extension
            $modifiedAt = date('Y-m-d H:i:s');
            $image->modified_at = $modifiedAt;
            $extension  = $uploadedFile->getExtension();
            //directory to save the image
            $savePath = Yii::getAlias('@webroot') . Yii::$app->params['uploadsPath'] ;
            //check if dir already exists
            if (!file_exists($savePath)) {
                //make dir ,give permissions
                mkdir($savePath, 0777, true);
            }
            //save file
            $fileUploadPath = $savePath . $fileName. '.' . $extension;
            $image->save();
            $uploadedFile->saveAs($fileUploadPath);
            Yii::$app->response->statusCode = 201;
            $response = array(
                'uploaded' => $image,
            );
        } else {
            Yii::$app->response->statusCode = 400;
            $response = array(
                'HTTP status code 404' =>'Chybný request - nenašiel sa súbor pre upload',
            );
        }
        return $response;
    }
}