<?php

namespace common\models\database;

use Yii;

/**
 * This is the model class for table "test".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $alias
 * @property string $img
 * @property integer $like
 * @property integer $sort
 * @property integer $type
 * @property integer $price
 * @property integer $updated_at
 * @property integer $created_at
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'alias', 'img', 'updated_at', 'created_at'], 'required'],
            [['description'], 'string'],
            [['like', 'sort', 'type', 'price'], 'integer'],
            [['title', 'alias', 'img'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'alias' => 'Alias',
            'img' => 'Img',
            'like' => 'Like',
            'sort' => 'Sort',
            'type' => 'Type',
            'price' => 'Price',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
