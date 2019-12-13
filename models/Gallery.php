<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Gallery extends ActiveRecord
{
    public static function tableName()
    {
        return 'gallery';
    }

    public function beforeDelete()
    {
        foreach ($this->image as $q) {
            $q->deleteImage($this->path . '%2F' . $q->path);
            $q->delete();
        }
        return parent::beforeDelete();
    }

    public function fields()
    {
        return [
            'path',
            'name' => function ()   {
                return rawurldecode($this->path);
            },
            'images' => function ()   {
                return $this->image;
            },
        ];
    }

    public function rules()
    {
        return [
            [['path'], 'required'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    public function getImage()
    {
        return $this->hasMany(Image::className(), ['gallery_id' => 'id']);
    }
}
