<?php

namespace backend\controllers;

use common\models\AuthItem;
use One;
use common\models\PasswordResetForm;
use common\models\PermissionForm;
use common\models\RoleForm;
use common\models\UserProfile;
use common\models\User;
use common\models\UserSearch;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\rbac\Item;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
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
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!One::app()->user->can('User')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(One::app()->request->queryParams);
        $authManager = One::app()->authManager;
        $permissionDataProvider = new ArrayDataProvider([
            'allModels' => $authManager->getPermissions()
        ]);
        $roleDataProvider = new ArrayDataProvider([
            'allModels' => $authManager->getRoles()
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'permissionDataProvider' => $permissionDataProvider,
            'roleDataProvider' => $roleDataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (!One::app()->user->can('User')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!One::app()->user->can('Create User')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $model = new User();

        if ($model->load(One::app()->request->post()) && $model->save()) {
            One::app()->session->setFlash('success', 'User created successfully.');
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
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!One::app()->user->can('Update User')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $model = $this->findModel($id);

        if ($model->load(One::$app->request->post()) && $model->save()) {
            One::app()->session->setFlash('success', 'User updated successfully.');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!One::app()->user->can('Delete User')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $this->findModel($id)->delete();
        One::app()->session->setFlash('success', 'User deleted successfully.');
        return $this->redirect(['index']);
    }

    public function actionCreatePermission()
    {
        if (!One::app()->user->can('Webmaster')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $model = new PermissionForm();

        if ($model->load(One::app()->request->post())) {
            if ($model->save()) {
                One::app()->session->setFlash('success', 'Permission created successfully.');
                return $this->redirect(['index', 'tab' => 'permissions']);
            } else {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('create-permission', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdatePermission($name)
    {
        if (!One::app()->user->can('Webmaster')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $model = new PermissionForm();
        $permission = One::app()->authManager->getPermission($name);

        $model->_isNewRecord = false;
        $model->_oldName = $permission->name;
        $model->name = $permission->name;
        $model->parent = PermissionForm::getParent($permission->name);
        $model->description = $permission->description;
        $model->rule_name = array_search($permission->ruleName, AuthItem::ruleList());
        $model->data = $permission->data;

        if ($model->load(One::app()->request->post())) {
            if ($model->save()) {
                One::app()->session->setFlash('success', 'Permission updated successfully.');
                return $this->redirect(['index', 'tab' => 'permissions']);
            } else {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('update-permission', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeletePermission($name)
    {
        if (!One::app()->user->can('Webmaster')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $authManager = One::app()->authManager;
        $item = $authManager->getPermission($name);

        $authManager->remove($item);
        One::app()->session->setFlash('success', 'Permission deleted successfully.');
        return $this->redirect(['index', 'tab' => 'permissions']);
    }

    public function actionCreateRole()
    {
        if (!One::app()->user->can('Webmaster')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $model = new RoleForm();

        if ($model->load(One::app()->request->post())) {
            if ($model->save()) {
                One::app()->session->setFlash('success', 'Role created successfully.');
                return $this->redirect(['index', 'tab' => 'roles']);
            } else {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('create-role', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionUpdateRole($name)
    {
        if (!One::app()->user->can('Webmaster')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $model = new RoleForm();
        $role = One::app()->authManager->getRole($name);

        $model->_isNewRecord = false;
        $model->_oldName = $role->name;
        $model->name = $role->name;
        $model->parent = RoleForm::getParent($role->name);
        $model->description = $role->description;
        $model->rule_name = array_search($role->ruleName, AuthItem::ruleList());
        $model->data = $role->data;

        if ($model->load(One::app()->request->post())) {
            if ($model->save()) {
                One::app()->session->setFlash('success', 'Role updated successfully.');
                return $this->redirect(['index', 'tab' => 'roles']);
            } else {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('update-role', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteRole($name)
    {
        if (!One::app()->user->can('Webmaster')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $authManager = One::app()->authManager;
        $item = $authManager->getRole($name);

        $authManager->remove($item);
        One::app()->session->setFlash('success', 'Role deleted successfully.');
        return $this->redirect(['index', 'tab' => 'roles']);
    }

    public function actionAssignPermission($name)
    {
        if (!One::app()->user->can('Webmaster')) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        if (!One::app()->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $assigned = [];
        $child = One::app()->authManager->getChildren($name);
        $permissions = PermissionForm::permissionNestedList();

        foreach ($child as $key => $children) {
            if ($children->type == Item::TYPE_PERMISSION) {
                $assigned[$key] = $children;
            }
        }

        if (One::app()->request->isPost) {
            $role = One::app()->authManager->getRole($name);
            $permissionName = One::app()->request->post('permission_name');
            $checked = One::app()->request->post('checked');

            if ($permissionName != null && $checked != null) {
                $permission = One::app()->authManager->getPermission($permissionName);

                if ($checked == 'true') {
                    $success = One::app()->authManager->addChild($role, $permission);
                } else {
                    $success = One::app()->authManager->removeChild($role, $permission);
                }

                One::app()->response->format = Response::FORMAT_JSON;
                return ['success' => $success];
            }
        }

        return $this->renderAjax('_assignmentmodal', [
            'permissions' => $permissions,
            'assigned' => $assigned,
            'roleName' => $name
        ]);
    }

    /**
     * Allow users to update their own profile
     * @param $id integer User id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionProfile($id)
    {
        $user = User::findOne($id);
        $model = $user->profile;

        if (!One::app()->user->can('User Profile', ['profile' => $model])) {
            throw new ForbiddenHttpException(One::t('app', 'You are not allowed to perform this action.'));
        }

        $passwordReset = new PasswordResetForm([
            'user' => $user
        ]);
        $passwordReset->scenario = PasswordResetForm::PROFILE_UPDATE;

        if ($model->load(One::app()->request->post())) {
            if ($model->save()) {
                One::app()->session->setFlash('success', 'Your profile successfully saved.');
                return $this->refresh();
            } else {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['errors' => $model->getErrors()];
            }
        } elseif ($passwordReset->load(One::app()->request->post())) {
            if ($passwordReset->resetPassword()) {
                One::app()->session->setFlash('success', 'Your password successfully changed.');
                return $this->refresh();
            } else {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['errors' => $passwordReset->getErrors()];
            }
        } else {
            return $this->render('profile', [
                'model' => $model,
                'passwordReset' => $passwordReset,
            ]);
        }
    }

    public function actionPictureUpload($id)
    {
        if (One::app()->request->isPost) {
            $model = UserProfile::findOne($id);

            if (!One::app()->user->can('User Profile', ['profile' => $model])) {
                throw new ForbiddenHttpException(One::t('app', 'You are not allowed to perform this action.'));
            }

            $file = UploadedFile::getInstanceByName('file');

            if (empty($file)) {
                One::app()->response->format = Response::FORMAT_JSON;
                return [One::t('app', 'No file selected for upload.')];
            }

            $uploadDirectory = One::getAlias('@frontendWebroot') . '/uploads/source/users/';

            if (FileHelper::createDirectory(FileHelper::normalizePath($uploadDirectory))) {
                $fileName = strtolower(One::app()->security->generateRandomString(12) . '_' . time());
                $filePath = "{$uploadDirectory}{$fileName}.{$file->extension}";

                if ($file->saveAs($filePath)) {
                    $model->deleteUserImage();
                    $fileUrl = "/uploads/source/users/{$fileName}.{$file->extension}";
                    $model->user_image = One::app()->frontUrlManager->createUrl([$fileUrl]);

                    $model->updateAttributes(['user_image']);
                    One::app()->response->format = Response::FORMAT_JSON;
                    return ['link' => $fileUrl];
                }
            }

            One::app()->response->format = Response::FORMAT_JSON;
            return [One::t('app', 'Unable to upload file at the moment.')];
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPictureDelete($id)
    {
        if (One::app()->request->isPost) {
            $model = UserProfile::findOne(['id' => $id]);

            if (!One::app()->user->can('User Profile', ['profile' => $model])) {
                throw new ForbiddenHttpException(One::t('app', 'You are not allowed to perform this action.'));
            }

            if ($model !== null && $model->deleteUserImage()) {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['success' => true];
            } else {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['success' => false, 'message' => One::t('app', 'Unable to delete the picture.')];
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
}
