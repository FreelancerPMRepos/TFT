<?php

namespace common\models;

use Yii;


/**
 * This is the model class for table "cms".
 *
 * @property int $id
 * @property string $title
 * @property string $html_body For Website
 * @property string $app_body For Mobile Application
 * @property string $meta_tile
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $slug
 */
class Cms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'html_body', 'app_body','slug'], 'required'],
            [['html_body', 'app_body', 'meta_tile', 'meta_keyword', 'meta_description'], 'string'],
            [['title','slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'slug' => Yii::t('app', 'Slug'),            
            'html_body' => Yii::t('app', 'For Website'),
            'app_body' => Yii::t('app', 'For Mobile Application'),
            'meta_tile' => Yii::t('app', 'Meta Tile'),
            'meta_keyword' => Yii::t('app', 'Meta Keyword'),
            'meta_description' => Yii::t('app', 'Meta Description'),
        ];
    }
}
