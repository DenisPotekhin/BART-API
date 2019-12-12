<?php

namespace app\models;

use Yii;
use app\models\Image;

/**
 * This is the model class for table "gallery".
 *
 * @property int $id
 * @property string $path
 */
class Gallery extends \yii\db\ActiveRecord
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
            // название поля совпадает с именем атрибута
            'path',
            //
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

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
        ];
    }

    public function getImage()
    {
        return $this->hasMany(Image::className(), ['gallery_id' => 'id']);
    }
}
