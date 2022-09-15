<?php

namespace common\models;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int $user_id
 * @property string $uuid
 * @property string $title
 * @property string $message
 * @property string $type
 * @property string $app_type
 * @property string $is_read
 * @property int $created_at
 * @property int $created_by
 * @property string $push_request
 * @property string $push_response
 *
 * @property User $user
 */

class Notification extends \yii\db\ActiveRecord
{
    public $q;
    public $page = 1;
    public $per_page = 10;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }
    public function behaviors()
    {
        return [           
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                   \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
            [
                'class' => \yii\behaviors\BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page', 'per_page'], 'integer'],
            [['user_id', 'uuid', 'title', 'app_type'], 'required'],
            [['user_id', 'created_at', 'created_by'], 'integer'],
            [['uuid', 'message', 'type', 'is_read', 'push_request', 'push_response','q'], 'string'],
            [['title', 'app_type'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'uuid' => 'Uuid',
            'title' => 'Title',
            'message' => 'Message',
            'type' => 'Type',
            'app_type' => 'App Type',
            'is_read' => 'Is Read',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'push_request' => 'Push Request',
            'push_response' => 'Push Response',
        ];
    }
    public function getItem()
    {
       
        $query =  Notification::find()->joinWith(['userOtherInfo'])->where(['notification.user_id'=>\Yii::$app->user->id]);           
        $query->orderBy('id DESC')->asArray();
        $page = $this->page > 0 ? ($this->page - 1) : 0;
        $pageSize = (int)$this->per_page;

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => true,
                'page' => $page,
                'pageParam' => 'page',
                'defaultPageSize' => $pageSize,
                'pageSizeLimit' => [10, 50, 100],
                'pageSizeParam' => 'per_page',
                'validatePage' => true,
                'params' => '',
            ]
        ]);

        $models   = $provider->getModels();
        $pagination = array_intersect_key(
            (array)$provider->pagination,
            array_flip(
                \Yii::$app->params['paginationParams']
            )
        );

        $totalPage                  = $pagination['totalCount'] / $pageSize;   
        $pagination['totalPage']    = $totalPage;
        $pagination['currentPage']  = $this->page;
        $pagination['isMore']       = $totalPage <= $this->page ? false:true;

        
        $data  = [];
        foreach ($models as $key => $value) {
            
            if(isset($value['userOtherInfo']['photo'])){
                $data[$key]['photo']  =  $value['userOtherInfo']['photo'];
            }else{
                $data[$key]['photo']  =   \yii\helpers\Url::to('img_assets/notification.png', $schema = true);
            }


            $data[$key]['id']            =  $value['id'];     
            $data[$key]['title']         =  $value['title'];     
            $data[$key]['message']       =  $value['message'];
            $data[$key]['type']          =  $value['type'];   
            $data[$key]['user_id']       =  $value['user_id'];              
            $data[$key]['from_user_id']  =  $value['from_user_id'];              
            $data[$key]['created_at']    =  $value['created_at'];  
            
        }
        return [
            'rows' => $data,
            'pagination' => $pagination,
        ];
    }
  
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function getUserOtherInfo()
    {
        return $this->hasOne(UserAdditionalInfo::className(), ['user_id' => 'from_user_id']);
    }
   
}
