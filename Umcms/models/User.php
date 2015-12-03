<?php

namespace Umcms\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use Rbac\behaviours\SaveUserRole;
use Rbac\models\RbacAbstractUserActiveRecord;
use Uploads\behaviors\UploadBehaviour;
use zxbodya\yii2\galleryManager\GalleryBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password password
 */
class User extends RbacAbstractUserActiveRecord implements IdentityInterface {

	const STATUS_DISABLED = 0;
	const STATUS_ENABLED = 10;
	const UPDATE_SCENARIO = 'on update';
	const CREATE_SCENARIO = 'on create';

	protected $roleObject = null;

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			TimestampBehavior::className(),
			SaveUserRole::className(),
			'uploadFile' => [
				'class' => UploadBehaviour::className(),
				'fields' => ['image'],
				'allowedFileExtentions' => ['png', 'jpg']
			],
			
			'multipleUploadFile' => [
				'class' => UploadBehaviour::className(),
				'fields' => ['file_collection_id'],
				'allowedFileExtentions' => ['png', 'jpg'],
				'multiple' => true
			],
			
			'galleryBehavior' => [
				'class' => GalleryBehavior::className(),
				'type' => 'user',
				'extension' => 'jpg',
				'directory' => Yii::getAlias('@webroot') . '/files/User/gallery',
				'url' => Yii::getAlias('@web') . '/files/User/gallery',
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id, 'status' => self::STATUS_ENABLED]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username, 'status' => self::STATUS_ENABLED]);
	}

	/**
	 * Finds user by Email
	 *
	 * @param string $email
	 * @return static|null
	 */
	public static function findByEmail($email)
	{
		return static::findOne(['email' => $email, 'status' => self::STATUS_ENABLED]);
	}

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token password reset token
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token)
	{
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne([
				'password_reset_token' => $token,
				'status' => self::STATUS_ACTIVE,
		]);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 * @return boolean
	 */
	public static function isPasswordResetTokenValid($token)
	{
		if (empty($token)) {
			return false;
		}

		$timestamp = (int) substr($token, strrpos($token, '_') + 1);
		$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		return $timestamp + $expire >= time();
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
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password)
	{

		return Yii::$app->security->validatePassword($password, $this->password_hash);
	}

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function setPassword($password)
	{

		if (strlen($password) === 0) {
			return;
		}

		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}

	public function getPassword()
	{
		return '';
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['password_hash'], 'validatePasswordHash', 'on' => self::CREATE_SCENARIO, 'skipOnEmpty' => false],
			[['username', 'password_hash', 'email'], 'required'],
			[['status'], 'integer'],
			[['username', 'password_hash', 'password', 'password_reset_token', 'email'], 'string', 'max' => 255],
			[['email'], 'unique'],
			[['email'], 'email'],
			[['image', 'password', 'file_collection_id'], 'safe'],
			['status', 'default', 'value' => self::STATUS_ENABLED],
			['status', 'in', 'range' => [self::STATUS_ENABLED, self::STATUS_DISABLED]],
		];
	}

	public function validatePasswordHash($field)
	{
		if (is_null($this->$field)) {
			$this->addError('password', 'Пароль не может быть пустым');
		}
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'username' => 'Имя',
			'email' => 'Email',
			'status' => 'Статус',
			'password' => 'Пароль',
			'newPassword' => 'Новый пароль',
			'role' => 'Группа',
			'image' => 'Изображение пользователя'
		];
	}

	public static function getUserStatusList()
	{
		return [
			self::STATUS_DISABLED => 'Неактивный',
			self::STATUS_ENABLED => 'Активен',
		];
	}

	public function getStatusForView()
	{
		return self::getUserStatusList()[$this->status];
	}

	public function getRoleDescription()
	{

		$rolesArray = array_values(\yii::$app->authManager->getRolesByUser($this->id));
		if (count($rolesArray) == 1) {
			return $rolesArray[0]->description;
		}
		return '';
	}

}
