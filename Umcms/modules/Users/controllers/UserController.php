<?php

namespace Umcms\modules\Users\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Umcms\models\User;
use Rbac\interfaces\ObservableRbacController;
use zxbodya\yii2\galleryManager\GalleryManagerAction;

/**
 * UserController implements the CRUD actions for User model.
 * @module: Пользователи
 */
class UserController extends Controller implements ObservableRbacController {

	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
			'rbac_controll' => [
				'class' => 'Rbac\behaviours\CheckAccessBehaviour',
			],
		];
	}

	/**
	 * Lists all User models.
	 * @title: Листинг
	 * @return mixed
	 */
	public function actionIndex()
	{
		$dataProvider = new ActiveDataProvider([
			'query' => User::find(),
		]);

		return $this->render('index', [
				'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single User model.
	 * @title: Просмотр
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
				'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @title: Создание
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new User();
		$model->setScenario(User::CREATE_SCENARIO);
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
					'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @title: Обновление
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
					'model' => $model,
			]);
		}
	}

	public function actionUpdateMyProfile($id)
	{

		if (\yii::$app->getUser()->getIdentity()->id != $id) {
			throw new \yii\web\ForbiddenHttpException();
		}

		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(Yii::$app->user->getReturnUrl());
		} else {
			return $this->render('update', [
					'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @title: Удаление
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = User::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	public function getModuleName()
	{
		return 'users';
	}

	public function getActionsAliasArray()
	{
		return [
			'index' => 'Просмотр листинга пользователей',
			'view' => 'Просмотр пользователя',
			'create' => 'Создание пользователей',
			'delete' => 'Удаление пользователей',
			'update' => 'Редактирование пользователей',
			'updatemyprofile' => 'Редактирование своего профиля'
		];
	}
}
