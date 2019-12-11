<?php


namespace app\controllers;

use Yii;
use yii\rest\ActiveController;

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
            return [
                'gallery' => $gallery,
            ];
        } else {
            return [
                'HTTP status code 404' => 'Zvolená galéria neexistuje',
            ];
        }
    }

    public function actionListAll()
    {
        $modelClass = $this->modelClass;
        $query = $modelClass::find()->with('image')->all();
        return [
                'galleries' => $query,
            ];
    }

    /**
     * @return array
     */
    public function actionInsert()
    {
        $requestParam = Yii::$app->getRequest()->getBodyParam('name');
        if (!$requestParam) {
            return [
                'HTTP status code 400' => 'Chybne zadaný request - nevhodný obsah podľa schémy.',
            ];
        }
        $modelClass = $this->modelClass;
        $path = rawurlencode($requestParam);
        $gallery = $modelClass::findOne(['path' => $path]);
        if ($gallery) {
            return [
                'HTTP status code 409' => 'Galéria so zadaným názvom už existuje',
            ];
        }
        else {
            $newGallery = new $modelClass();
            $newGallery->path = $path;
            $newGallery->save();
            return $newGallery;
        }
    }

    public function actionErase($path)
    {
        $encodePath = rawurlencode($path);
        $modelClass = $this->modelClass;
        $gallery = $modelClass::findOne(['path' => $encodePath]);
        if ($gallery) {
            $gallery->delete();
            return [
                'HTTP status code 200' => 'Galéria/obrázok bola úspešne vymazaná',
            ];
        } else {
            return [
                'HTTP status code 404' => 'Zvolená galéria/obrázok neexistuje',
            ];
        }
    }
}