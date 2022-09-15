<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $auth_key
 * @property int|null $access_token_expired_at
 * @property string|null $password_hash
 * @property string|null $password_reset_token
 * @property string|null $email
 * @property string|null $unconfirmed_email
 * @property int|null $confirmed_at
 * @property string|null $registration_ip
 * @property int|null $last_login_at
 * @property string|null $last_login_ip
 * @property int|null $blocked_at
 * @property int|null $status
 * @property int|null $role
 * @property string|null $user_type
 * @property string|null $social_provider_id
 * @property string|null $social_type
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Notification[] $notifications
 * @property UserAdditionalInfo $userAdditionalInfo
 * @property UserInAppTransaction[] $userInAppTransactions
 * @property UserToken[] $userTokens
 * @property UserVerificationCode[] $userVerificationCodes
 */
class Admin extends \yii\db\ActiveRecord
{
    const ROLE_USER = 10;
    const ROLE_TRAINER = 50;
    const ROLE_TRAINEE = 60;
    const ROLE_ADMIN = 99;
    const STATUS_DELETED = -1;
    const STATUS_DISABLED = 0;
    const STATUS_PENDING = 1;
    const STATUS_ACTIVE = 10;

    // public $permissions;
    // public $authKey;
    

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /** @inheritdoc */
    public function behaviors()
    {
        // TimestampBehavior also provides a method named touch() that allows you to assign the current timestamp to the specified attribute(s) and save them to the database. For example,
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    // public function rules()
    // {
    //     return [
    //         [['access_token_expired_at', 'confirmed_at', 'last_login_at', 'blocked_at', 'status', 'role', 'created_at', 'updated_at'], 'integer'],
    //         [['user_type'], 'string'],
    //         [['username'], 'string', 'max' => 200],
    //         [['auth_key', 'password_hash', 'password_reset_token', 'email', 'unconfirmed_email', 'social_provider_id', 'social_type'], 'string', 'max' => 255],
    //         [['registration_ip', 'last_login_ip'], 'string', 'max' => 20],
    //     ];
    // }
    /**
     * @inheritdoc
     */
    public $repeatpass;
    public function rules()
    {
        return [
            ['username', 'unique','message'=>'Username has already been taken.'],
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'length' => [3, 15]],
            [
                'username',
                'match',
                'pattern' => '/^[A-Za-z0-9._-]{3,15}$/',
                'message' => 'Your username can only contain alphanumeric characters, underscores and dashes.'
            ],
            // ['username', 'validateUsername', 'on' => 'register'],

            ['email', 'unique','message'=>'Email has already been taken.'],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],

            [['password_hash'], 'required'],
            [['password_hash','repeatpass'], 'string', 'min' => 6],
            ['repeatpass','compare','compareAttribute'=>'password_hash'],

            [['confirmed_at', 'blocked_at', 'last_login_at'], 'datetime', 'format' => 'php:U'],
            [['last_login_ip', 'registration_ip'], 'ip'],
            ['user_type', 'safe'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_PENDING, self::STATUS_DISABLED]],

            ['role', 'default', 'value' => self::ROLE_ADMIN],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_TRAINER, self::ROLE_ADMIN, self::ROLE_TRAINEE]],

            // ['permissions', 'validatePermissions'],
            [['access_token'], 'safe'],
        ];
    }
    public function scenarios() {
        $scenarios = parent::scenarios();
        return $scenarios;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'access_token_expired_at' => 'Access Token Expired At',
            'password_hash' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'unconfirmed_email' => 'Unconfirmed Email',
            'confirmed_at' => 'Confirmation time',
            'registration_ip' => 'Registration IP',
            'last_login_at' => 'Last Login At',
            'last_login_ip' => 'Last Login Ip',
            'blocked_at' => 'Blocked At',
            'status' => 'Status',
            'role' => 'Role',
            'user_type' => 'User Type',
            'social_provider_id' => 'Social Provider ID',
            'social_type' => 'Social Type',
            'created_at' => 'Registration time',
            'updated_at' => 'Updated At',
            'repeatpass' => 'Repeat Password'
        ];
    }

    /**
     * Gets query for [[Notifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserAdditionalInfo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAdditionalInfo()
    {
        return $this->hasOne(UserAdditionalInfo::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserInAppTransactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserInAppTransactions()
    {
        return $this->hasMany(UserInAppTransaction::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserTokens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserTokens()
    {
        return $this->hasMany(UserToken::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserVerificationCodes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserVerificationCodes()
    {
        return $this->hasMany(UserVerificationCode::className(), ['user_id' => 'id']);
    }
    
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    // public function validateUsername($attribute, $params)
    // {
    //     // get post type - POST or PUT
    //     $request = Yii::$app->request;

    //     // if POST, mode is create
    //     if ($request->isPost) {
    //         // check username is already taken

    //         $existingUser = User::find()
    //             ->where(['username' => $this->$attribute])
    //             ->count();
    //         if ($existingUser > 0) {
    //             $this->addError($attribute, Lx::t('model', 'The username has already been taken.'));
    //         }
    //     } elseif ($request->isPut) {
    //         // get current user
    //         $user = User::findIdentityWithoutValidation($this->id);
    //         if ($user == null) {
    //             $this->addError($attribute, Lx::t('model', 'The system cannot find requested user.'));
    //         } else {
    //             // check username is already taken except own username
    //             $existingUser = User::find()
    //                 ->where(['=', 'username', $this->$attribute])
    //                 ->andWhere(['!=', 'id', $this->id])
    //                 ->count();
    //             if ($existingUser > 0) {
    //                 $this->addError($attribute, Lx::t('model', 'The username has already been taken.'));
    //             }
    //         }
    //     } else {
    //         // unknown request
    //         $this->addError($attribute, Lx::t('model', 'Unknown request'));
    //     }
    // }
}
