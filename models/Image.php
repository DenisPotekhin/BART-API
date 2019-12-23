<?php
declare(strict_types = 1);

namespace app\models;

use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Boolean;
use Yii;
use yii\db\ActiveRecord;

class Image extends ActiveRecord
{
    public static function tableName()
    {
        return 'image';
    }

    public function fields(): array
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

    public function deleteImage(string $fileName): boolean
    {
        unlink(getPlaceToImage($fileName));
    }

    public function rules(): array
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
