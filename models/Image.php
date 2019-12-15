<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Image extends ActiveRecord
{
    public static function tableName()
    {
        return 'image';
    }

    public function fields()
    {
        return [
            'path',
            'fullpath' => function () {
                return $this->gallery->path . '/' . $this->path;
            },
            'name' => function () {
                return ucfirst(substr($this->path, 0, stripos($this->path, '.')));
            },
            'modified' => function () {
                return date('c', $this->modified_at);
            },
        ];
    }

    public function deleteImage($fileName)
    {
        unlink(getPlaceToImage($fileName));
    }

    public function rules()
    {
        return [
            [['gallery_id', 'path', 'modified_at'], 'required'],
            [['gallery_id'], 'integer'],
            [['modified_at'], 'safe'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    public function getGallery()
    {
        return $this->hasOne(Gallery::className(), ['id' => 'gallery_id']);
    }
}
