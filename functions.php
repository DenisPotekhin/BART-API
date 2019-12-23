<?php
declare(strict_types = 1);

function fileEnCode(string $path, string $filename): string
{
    return rawurlencode($path . '/') . $filename;
}

function getGalleryName(string $fullpath): string
{
    return substr($fullpath, 0, stripos(rawurldecode($fullpath), '/'));
}

// NOTE: I use method (getPlaceToImage) for definition placement for looking for image
function getPlaceToUploadImage(string $fileName): string
{
    $savePath = Yii::getAlias('@webroot') . Yii::$app->params['uploadsPath'];
    if (!file_exists($savePath)) {
        mkdir($savePath, 0777, true);
    }
    return $savePath . $fileName;
}

// NOTE: Method for definition placement for looking for image
function getPlaceToImage(string $fileName): string
{
    $pathSave = Yii::getAlias('@webroot') . Yii::$app->params['uploadsPath'];
    return $pathSave . $fileName;
}
