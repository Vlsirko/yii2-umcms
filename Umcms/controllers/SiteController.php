<?php
namespace Umcms\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use Umcms\models\LoginForm;
use yii\filters\VerbFilter;
use zxbodya\yii2\galleryManager\GalleryManagerAction;
use Umcms\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			'rbac_controll' =>[
				'class' => 'Rbac\behaviours\CheckAccessBehaviour',
			],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'gallery' => [
				'class' => GalleryManagerAction::className(),
				// mappings between type names and model classes (should be the same as in behaviour)
				'types' => [
					'user' => User::className()
				]
			],
        ];
    }
	
    public function actionIndex()
    {
        return $this->render('index');
    }
	
	public function getViewPath()
	{
		return __DIR__ . '/../views/site';
	}

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	
}
