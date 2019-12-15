<?php

function fileEnCode($path, $filename)
{
    return rawurlencode($path . '/') . $filename;
}

function getGalleryName($fullpath)
{
    return substr($fullpath, 0, stripos(rawurldecode($fullpath), '/'));
}

// NOTE: I use method (getPlaceToImage) for definition placement for looking for image
function getPlaceToUploadImage($fileName)
{
    $savePath = Yii::getAlias('@webroot') . Yii::$app->params['uploadsPath'];
    if (!file_exists($savePath)) {
        mkdir($savePath, 0777, true);
    }
    return $savePath . $fileName;
}

// NOTE: Method for definition placement for looking for image
function getPlaceToImage($fileName)
{
    $pathSave = Yii::getAlias('@webroot') . Yii::$app->params['uploadsPath'];
    return $pathSave . $fileName;
}