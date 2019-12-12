<?php

namespace app\models;

use Yii;
use app\models\Gallery;
/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int $gallery_id
 * @property string $path
 * @property string $modified_at
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    public function fields()
    {
        return [
            // название поля совпадает с именем атрибута
            'path',

            'fullpath' => function ()   {
                return $this->gallery->path . '/' . $this->path;
            },
            //
            'name' => function ()   {
                return ucfirst(substr($this->path, 0, stripos($this->path, '.')));
            },
            'modified' => function ()   {
                return $this->modified_at;
            },
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function deleteImage($fileName)
    {
        $repoPath = Yii::getAlias('@webroot') . Yii::$app->params['uploadsPath'] ;
        $fileDeletePath = $repoPath . $fileName;
        unlink($fileDeletePath);
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gallery_id' => 'Gallery ID',
            'path' => 'Path',
            'modified_at' => 'Modified At',
        ];
    }

    public function getGallery()
    {
        return $this->hasOne(Gallery::className(), ['id' => 'gallery_id']);
    }
}
