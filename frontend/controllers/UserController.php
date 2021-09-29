<?php

namespace frontend\controllers;

use common\models\LoginForm;
use common\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use One;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * UserController
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!One::app()->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(One::app()->request->post())) {
            if ($model->login()) {
                $model->updateTimeAndIp();
                One::app()->session->setFlash('success', 'Welcome back ' . One::app()->user->identity->getName() . '!');

                return $this->goBack();
            } else {
                One::app()->response->format = Response::FORMAT_JSON;

                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        One::app()->user->logout();
        One::app()->session->setFlash('success', 'You have been successfully logged out.');

        return $this->redirect(['login']);
    }

    /**
     * Signs user up.
     *
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionSignup()
    {
        if (!One::app()->user->isGuest) {
            One::app()->session->setFlash('info', 'Sign up is not allowed for logged in users.');

            return $this->goHome();
        }

        $model = new SignupForm();

        if ($model->load(One::app()->request->post())) {
            if ($model->signup()) {
                $model->sendVerificationEmail();
                One::app()->session->setFlash('success', 'You have successfully signed up, please check your email for further assistance.');

                return $this->goHome();
            } else {
                One::app()->response->format = Response::FORMAT_JSON;

                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('signup', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Requests password reset.
     *
     * @return array|string|Response
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(One::app()->request->post())) {
            if ($model->validate()) {
                if ($model->sendEmail()) {
                    One::app()->session->setFlash('success', 'Please check your email for further instructions.');
                } else {
                    One::app()->session->setFlash('error', 'Unable to request for change your password, please try again.');
                }

                return $this->refresh();
            } else {
                One::app()->response->format = Response::FORMAT_JSON;

                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('requestPasswordReset', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return array|string|Response
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            One::app()->session->setFlash('error', $e->getMessage());
            return $this->goHome();
        }

        if ($model->load(One::app()->request->post())) {
            if ($model->validate()) {
                if ($model->resetPassword()) {
                    One::app()->session->setFlash('success', 'Password successfully changed.');
                } else {
                    One::app()->session->setFlash('error', 'Unable to change your password, please try again.');
                }

                return $this->redirect(['login']);
            } else {
                One::app()->response->format = Response::FORMAT_JSON;

                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('resetPassword', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Verify user's email address.
     *
     * @param string $token
     * @return Response
     */
    public function actionVerifyEmail($token)
    {
        if (!One::app()->user->isGuest) {
            One::app()->session->setFlash('error', "Verification can\'t be proceed, you already logged in.");

            return $this->goHome();
        }

        if (empty($token) || !is_string($token)) {
            One::app()->session->setFlash('error', 'Verification token required to verify your account.');

            return $this->goHome();
        }

        if (User::isEmailVerificationTokenValid($token)) {
            One::app()->session->setFlash('success', 'Account successfully verified, Now you can able to login.');

            return $this->redirect(['login']);
        } else {
            One::app()->session->setFlash('error', 'Invalid verification token or already verified.');

            return $this->goHome();
        }
    }
}