<?php

namespace common\models;

use Yii;
use Firebase\JWT\JWT;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\rbac\Permission;
use yii\web\Request as WebRequest;
use lajax\translatemanager\helpers\Language as Lx;
use app\models\UserSocialAuth;

use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property int $access_token_expired_at
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $unconfirmed_email
 * @property int $confirmed_at
 * @property string $registration_ip
 * @property int $last_login_at
 * @property string $last_login_ip
 * @property int $blocked_at
 * @property int $status
 * @property int $role
 * @property string $user_type
 * @property int $created_at
 * @property int $updated_at
 *
 * @property FollowerFollowing[] $followerFollowings
 * @property FollowerFollowing[] $followerFollowings0
 * @property Notification[] $notifications
 * @property UserAdditionalInfo[] $userAdditionalInfos
 * @property UserBankDetail[] $userBankDetails
 * @property UserSocialAuth[] $userSocialAuths
 * @property UserToken[] $userTokens
 * @property UserVerificationCode[] $userVerificationCodes
 * @property Video[] $videos
 * @property VideoComment[] $videoComments
 * @property VideoView[] $videoViews
 * @property VideoVote[] $videoVotes
 * @property Winners[] $winners
 */
class UserData extends \yii\db\ActiveRecord
{
    const ROLE_USER = 10;
    const ROLE_STAFF = 50;
    const ROLE_ADMIN = 99;
    const STATUS_DELETED = -1;
    const STATUS_DISABLED = 0;
    const STATUS_PENDING = 1;
    const STATUS_ACTIVE = 10;


    public $q;
    public $page = 1;
    public $per_page = 20;
    public $country_id = "";

    /** @var  array $permissions to store list of permissions */
    public $permissions;
    public $authKey;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
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
            'confirmed_at' => 'Confirmed At',
            'registration_ip' => 'Registration Ip',
            'last_login_at' => 'Last Login At',
            'last_login_ip' => 'Last Login Ip',
            'blocked_at' => 'Blocked At',
            'status' => 'Status',
            'role' => 'Role',
            'user_type' => 'User Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public function getItem()
    {   
        
        $queryParams    = [];      
        $query          = UserData::find()->joinWith(['userAdditionalInfos'])->where(['user.status'=>10,'user.role'=>10]);          
        if($this->q){
            // $query->andFilterWhere(['like', 'user.username', $this->q]); 
            $query->andFilterWhere(['LIKE', 'user.username', $this->q.'%',false]);

        }
        $query->asArray();
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
                'params' => $queryParams,
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
        $Edit  = 0;
        foreach ($models as $key => $value) {
            $data[$key]['username']         =  !empty($value['username'])?$value['username']:Lx::t('model','Unknown');
            $data[$key]['photo']            =  !empty($value['userAdditionalInfos']['photo'])?$value['userAdditionalInfos']['photo']:Yii::$app->params['placehoder_photo'];
            $data[$key]['user_id']          =  $value['id'];
            $data[$key]['user_url']         =  '/profile/'.$value['id'];         
        }
        return [
            'rows' => $data,
            'pagination' => $pagination,
        ];
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

    public function fields()
    {
        $fields = [
            'id',
            'username',
            'email',
            'unconfirmed_email',
            'role',
            'role_label' => function () {
                return $this->getRoleLabel();
            },
            'last_login_at',
            'last_login_ip',
            'confirmed_at',
            'blocked_at',
            'status',
            'status_label' => function () {
                $statusLabel = '';
                switch ($this->status) {
                    case self::STATUS_ACTIVE:
                        $statusLabel = Yii::t('app', 'Active');
                        break;
                    case self::STATUS_PENDING:
                        $statusLabel = Yii::t('app', 'Waiting Confirmation');
                        break;
                    case self::STATUS_DISABLED:
                        $statusLabel = Yii::t('app', 'Disabled');
                        break;
                    case self::STATUS_DELETED:
                        $statusLabel = Yii::t('app', 'Deleted');
                        break;
                }
                return $statusLabel;
            },
            'page',
            'created_at',
            'updated_at',
        ];

        // If role is staff and admin, then return permissions
        if ($this->role == self::ROLE_STAFF || $this->role == self::ROLE_ADMIN) {
            $fields['permissions'] = function () {
                $authManager = Yii::$app->authManager;

                /** @var Permission[] $availablePermissions */
                $availablePermissions = $authManager->getPermissions();

                /** @var array $tmpPermissions to store permissions assigned to the staff */
                $tmpPermissions = [];
                /** @var Permission[] $userPermissions */
                $userPermissions = $authManager->getPermissionsByUser($this->getId());
                if (!empty($availablePermissions)) {
                    /**
                     * @var string $permissionKey
                     * @var Permission $permission
                     */
                    foreach ($availablePermissions as $permissionKey => $permission) {
                        $tmpPermission = [
                            'name' => $permission->name,
                            'description' => $permission->description,
                            'checked' => false,
                        ];

                        if (!empty($userPermissions)) {
                            foreach ($userPermissions as $userPermissionKey => $userPermission) {
                                if ($userPermission->name == $permission->name) {
                                    $tmpPermission['checked'] = true;
                                    break;
                                }
                            }
                        }

                        $tmpPermissions[] = $tmpPermission;
                    }
                }

                return $tmpPermissions;
            };
        }

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['page', 'per_page'], 'integer'],
            [['q'], 'string', 'max' => 50],
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'length' => [3, 15]],
            [
                'username',
                'match',
                'pattern' => '/^[A-Za-z0-9._-]{3,15}$/',
                // 'message' => Lx::t(
                //     'model',
                //     'Your username can only contain alphanumeric characters, underscores and dashes.'
                // )
                'message' => 'Your username can only contain alphanumeric characters, underscores and dashes.'
            ],
            ['username', 'validateUsername', 'on' => 'register'],
            ['email', 'unique'],
            
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            // ['email', 'validateEmail','on' => 'register'],
            ['email', 'unique'],
            ['password_hash', 'required'],
            ['password_hash', 'string', 'min' => 6],
            ['password_hash', 'validatePasswordSubmit'],
            [['confirmed_at', 'blocked_at', 'last_login_at'], 'datetime', 'format' => 'php:U'],
            [['last_login_ip', 'registration_ip'], 'ip'],
            ['user_type', 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_PENDING, self::STATUS_DISABLED]],

            ['role', 'default', 'value' => self::ROLE_ADMIN],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_STAFF, self::ROLE_ADMIN]],

            ['permissions', 'validatePermissions'],
            [['access_token', 'permissions','page','status'], 'safe'],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    /**
     * Validate email
     *
     * @param $attribute
     * @param $params
     */
    public function validateEmail($attribute, $params)
    {
        // get post type - POST or PUT
        $request = Yii::$app->request;

        // if POST, mode is create
        if ($request->isPost) {
            // check username is already taken

            $existingUser = User::find()
                ->where(['email' => $this->$attribute])
                ->count();

            if ($existingUser > 0) {
                $this->addError($attribute, Lx::t('model', 'The email has already been taken.'));
            }
        } elseif ($request->isPut) {
            // get current user
            $user = User::findIdentityWithoutValidation($this->id);

            if ($user == null) {
                $this->addError($attribute, Lx::t('model', 'The system cannot find requested user.'));
            } else {
                // check username is already taken except own username
                $existingUser = User::find()
                    ->where(['=', 'email', $this->$attribute])
                    ->andWhere(['!=', 'id', $this->id])
                    ->count();
                if ($existingUser > 0) {
                    $this->addError($attribute, Lx::t('model', 'The email has already been taken.'));
                }
            }
        } else {
            // unknown request
            $this->addError($attribute, Lx::t('model', 'Unknown request'));
        }
    }

    /**
     * Validate username
     *
     * @param $attribute
     * @param $params
     */
    public function validateUsername($attribute, $params)
    {
        // get post type - POST or PUT
        $request = Yii::$app->request;

        // if POST, mode is create
        if ($request->isPost) {
            // check username is already taken

            $existingUser = User::find()
                ->where(['username' => $this->$attribute])
                ->count();
            if ($existingUser > 0) {
                $this->addError($attribute, Lx::t('model', 'The username has already been taken.'));
            }
        } elseif ($request->isPut) {
            // get current user
            $user = User::findIdentityWithoutValidation($this->id);
            if ($user == null) {
                $this->addError($attribute, Lx::t('model', 'The system cannot find requested user.'));
            } else {
                // check username is already taken except own username
                $existingUser = User::find()
                    ->where(['=', 'username', $this->$attribute])
                    ->andWhere(['!=', 'id', $this->id])
                    ->count();
                if ($existingUser > 0) {
                    $this->addError($attribute, Lx::t('model', 'The username has already been taken.'));
                }
            }
        } else {
            // unknown request
            $this->addError($attribute, Lx::t('model', 'Unknown request'));
        }
    }

    /**
     * Validate whether password is submitted or not
     *  Only required to submit the password on creation
     *
     * @param $attribute
     * @param $params
     */
    public function validatePasswordSubmit($attribute, $params)
    {
        // get post type - POST or PUT
        $request = Yii::$app->request;

        // if POST, mode is create
        if ($request->isPost) {
            if ($this->$attribute == '') {
                $this->addError($attribute, Lx::t('model', 'The password is required.'));
            }
        } elseif ($request->isPut) {
            // No action required
        }
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function beforeSave($insert)
    {
        // Convert username to lower case
        $this->username = strtolower($this->username);

        // Fill unconfirmed email field with email if empty
        if ($this->unconfirmed_email == '') {
            $this->unconfirmed_email = $this->email;
        }

        // Fill registration ip with current ip address if empty
        if ($this->registration_ip == '') {
            $this->registration_ip = Yii::$app->request->userIP;
        }

        // Fill auth key if empty
        if ($this->auth_key == '') {
            $this->generateAuthKey();
        }

        return parent::beforeSave($insert);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $authManager = Yii::$app->authManager;

        // ---- Start to process role
        // When insert new user, assign new role
        if ($insert == true) {
            $roleName = $this->getRoleName();

            $authItem = $authManager->getRole($roleName);
            $authManager->assign($authItem, $this->getId());
        } else {
            // When update existing user, revoke old role and assign new role
            if (isset($changedAttributes['role']) === true) {
                // Get role name
                $roleName = $this->getRoleName();
                $authManager->revokeAll($this->getId());
                $authItem = $authManager->getRole($roleName);
                $authManager->assign($authItem, $this->getId());
            }
        }
        // ---- Finish to process role

        // ---- Start to process permissions
        if (!empty($this->permissions)) {
            // permissions only allow to be entered if the role is staff
            if ($this->role == self::ROLE_STAFF) {
                $existingPermissions = $authManager->getPermissionsByUser($this->getId());
                foreach ($this->permissions as $permissionKey => $permission) {
                    if ($permission['checked'] == true) {
                        // If not assigned, then add to permission
                        if (isset($existingPermissions[$permission['name']]) == false) {
                            $authItem = $authManager->getPermission($permission['name']);
                            $authManager->assign($authItem, $this->getId());
                        }
                    } else {
                        // If assigned already, then remove from permission
                        if (isset($existingPermissions[$permission['name']]) == true) {
                            $authItem = $authManager->getPermission($permission['name']);
                            $authManager->revoke($authItem, $this->getId());
                        }
                    }
                }
            } else {
                // if role is changed and remove all
                $existingPermissions = $authManager->getPermissionsByUser($this->getId());
                if (!empty($existingPermissions)) {
                    foreach ($existingPermissions as $permissionName => $permission) {
                        $authItem = $authManager->getPermission($permissionName);
                        $authManager->revoke($authItem, $this->getId());
                    }
                }
            }
        }

        // ---- Start to process permissions
        return parent::afterSave($insert, $changedAttributes);
    }

    private function getRoleName()
    {
        $roleName = '';
        switch ($this->role) {
            case self::ROLE_USER:
                $roleName = 'user';
                break;
            case self::ROLE_STAFF:
                $roleName = 'trainer';
                break;
            case self::ROLE_ADMIN:
                $roleName = 'admin';
                break;
        }
        return $roleName;
    }

    /**
     * Validate permissions array
     *
     * @param $attribute
     * @param $params
     */
    public function validatePermissions($attribute, $params)
    {
        if (!empty($this->$attribute)) {
            $authManager = Yii::$app->authManager;
            // Get existing permissions
            $existingPermissions = $authManager->getPermissions();

            // Loop attributes
            foreach ($this->$attribute as $permissionKey => $permission) {
                // Validate attributes in the array
                if (array_key_exists('name', $permission) === false ||
                    array_key_exists('description', $permission) === false ||
                    array_key_exists('checked', $permission) === false) {
                    $this->addError($attribute, Lx::t('model', 'The permission is not valid format.'));
                } elseif (isset($existingPermissions[$permission['name']]) == false) {
                    // Validate name
                    $this->addError(
                        $attribute,
                        Lx::t('model',
                            'The permission name \'' . $permission['name'] . '\' is not valid.'
                        )
                    );
                } elseif (is_bool($permission['checked']) === false) {
                    // Validate checked
                    $this->addError(
                        $attribute,
                        Lx::t('model',
                            'The permission checked \'' . $permission['checked'] . '\' is not valid.'
                        )
                    );
                }
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowerFollowings()
    {
        return $this->hasMany(FollowerFollowing::className(), ['follower_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowerFollowings0()
    {
        return $this->hasMany(FollowerFollowing::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAdditionalInfos()
    {
       
        return $this->hasOne(UserAdditionalInfo::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserBankDetails()
    {
        return $this->hasMany(UserBankDetail::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSocialAuths()
    {
        return $this->hasOne(UserSocialAuth::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTokens()
    {
        return $this->hasMany(UserToken::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVerificationCodes()
    {
        return $this->hasMany(UserVerificationCode::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoComments()
    {
        return $this->hasMany(VideoComment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoViews()
    {
        return $this->hasMany(VideoView::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoVotes()
    {
        return $this->hasMany(VideoVote::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(AppsCountries::className(), ['id' => 'country_id']);
    }
}
