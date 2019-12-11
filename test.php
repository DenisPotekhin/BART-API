<?php

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
    return $newGallery
        }
