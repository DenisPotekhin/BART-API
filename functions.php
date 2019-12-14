<?php

function fileEnCode($path, $filename) {
    return rawurlencode($path . '/') . $filename;
}

function getGalleryName($fullpath) {
    return substr($fullpath, 0, stripos(rawurldecode($fullpath), '/'));
}

function getPlaceToUploadImage($fileName) {

    $savePath = Yii::getAlias('@webroot') . Yii::$app->params['uploadsPath'] ;
    if (!file_exists($savePath)) {
        mkdir($savePath, 0777, true);
    }
    return $savePath . $fileName;
}