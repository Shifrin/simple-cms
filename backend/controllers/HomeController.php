<?php
namespace backend\controllers;

use One;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Home controller (Default Controller)
 */
class HomeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (One::app()->user->isGuest) {
            $this->layout = $action->id == 'error' ? 'error' : 'public';
        }

        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
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
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['dashboard/index']);
    }

    /**
     * Show user login form
     * If login successful, the browser will be redirected to the previous page
     *
     * @return array|string|Response
     */
    public function actionLogin()
    {
        $this->layout = 'login';

        if (!One::app()->user->isGuest) {
            return $this->goHome();
        }

        // Redirect to the frontend login page
        return $this->redirect(One::app()->frontUrlManager->createUrl(['login']));

//        $model = new LoginForm();
//
//        // Perform Ajax based validation
//        if (One::app()->request->isAjax && $model->load(One::app()->request->post())) {
//            One::app()->response->format = Response::FORMAT_JSON;
//            return ActiveForm::validate($model);
//        }
//
//        if ($model->load(One::app()->request->post()) && $model->login()) {
//            $model->updateTimeAndIp();
//            One::app()->session->setFlash('success', 'Welcome back ' . One::app()->user->identity->getName() . '!');
//            return $this->goBack();
//        } else {
//            return $this->render('login', [
//                'model' => $model,
//            ]);
//        }
    }

    /**
     * Logout the user.
     * After logout it will redirect user to the home page.
     *
     * @return Response
     */
    public function actionLogout()
    {
        // Redirect to the frontend login page
//        return $this->redirect(One::app()->frontUrlManager->createUrl(['logout']));
        One::app()->user->logout();
        One::app()->session->setFlash('success', 'You have been successfully logged out.');
//        return $this->redirect(One::app()->frontUrlManager->createUrl(['/']));
        return $this->goHome();
    }
}
