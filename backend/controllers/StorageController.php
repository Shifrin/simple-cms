<?php


namespace backend\controllers;

use common\models\Attachment;
use yii\base\InvalidConfigException;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;


/**
 * StorageController Class File
 *
 * Add description here
 *
 * @author Mohammad Shifreen
 * @project Arab Photo Store
 * @copyright 2016 Mohammed Shifreen <mshifreen@gmail.com>
 */
class StorageController extends Controller
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
                        'roles' => ['Webmaster']
                    ]
                ],
            ],
        ];
    }

    /**
     * Find folders inside giving directory or default directory.
     *
     * @param null $directory
     * @return array
     * @throws InvalidConfigException
     */
    protected function findFolders($directory = null)
    {
        $uploadDirectory = \One::app()->fileManager->getUploadDirectory();
        $directoryPath = $directory == null ? $uploadDirectory : $directory;
        $items = [];

        if (!is_dir($directoryPath)) {
            throw new InvalidConfigException("'$directoryPath' is not a valid directory.");
        }

        $handle = opendir($directoryPath);

        if ($handle === false) {
            throw new InvalidConfigException("Unable to open the storage directory '$directoryPath'");
        }

        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = $directoryPath . DIRECTORY_SEPARATOR . $file;
            $replacedPath = str_replace('\\', '/', str_replace($uploadDirectory, '', $path));

            if (!is_file($path)) {
                $items[] = [
                    'name' => $file,
                    'type' => FileHelper::getMimeType($path),
                    'url' => null,
                    'path' => $replacedPath,
                    'size' => null,
                    'items' => $this->countFiles($path)
                ];
            }
        }

        closedir($handle);

        return $items;
    }

    /**
     * Find files inside giving directory or default directory.
     *
     * @param null $directory
     * @param boolean $recursive
     * @return array
     * @throws InvalidConfigException
     */
    protected function findFiles($directory = null, $recursive = false)
    {
        $uploadDirectory = \One::app()->fileManager->getUploadDirectory();
        $directoryPath = $directory == null ? $uploadDirectory : $directory;
        $items = [];

        if (!is_dir($directoryPath)) {
            throw new InvalidConfigException("'$directoryPath' is not a valid directory.");
        }

        $handle = opendir($directoryPath);

        if ($handle === false) {
            throw new InvalidConfigException("Unable to open the storage directory '$directoryPath'");
        }

        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = $directoryPath . DIRECTORY_SEPARATOR . $file;
            $replacedPath = str_replace('\\', '/', str_replace($uploadDirectory, '', $path));
            $url = Url::to('@web/uploads' . $replacedPath);

            if (is_file($path)) {
                $items[] = [
                    'name' => $file,
                    'type' => FileHelper::getMimeType($path),
                    'url' => $url,
                    'path' => $replacedPath,
                    'size' => filesize($path),
                    'items' => null
                ];
            } else {
                if ($recursive) {
                    $items = array_merge($items, $this->findFiles($path, true));
                }
            }
        }

        closedir($handle);

        return $items;
    }

    /**
     * Count files inside a directory.
     *
     * @param $directory
     * @return int
     */
    protected function countFiles($directory)
    {
        return count(FileHelper::findFiles($directory, ['recursive' => false]));
    }

    /**
     * Build breadcrumbs.
     *
     * @param $path
     * @return array
     */
    protected function buildBreadcrumbs($path)
    {
        $breadcrumbs = [
            'homeLink' => false,
            'links' => [
                [
                    'label' => '<i class="ion-ios-home-outline"></i>',
                ]
            ],
            'encodeLabels' => false,
            'itemTemplate' => "<li>{link}</li>",
            'options' => [
                'class' => 'breadcrumb file-breadcrumb'
            ],
        ];

        if ($path !== null) {
            $breadcrumbs['links'] = [
                [
                    'label' => '<i class="ion-ios-home-outline"></i>',
                    'url' => ['storage/index'],
                    'template' => "<li>{link}</li>",
                ]
            ];
            $paths = explode('/', $path);

            foreach ($paths as $single) {
                if ($single) {
                    if ($single == end($paths)) {
                        $breadcrumbs['links'][] = $single;
                    } else {
                        $path = preg_replace('~/+~', '/', $path);
                        $explode = explode("/$single/", $path);
                        $breadcrumbs['links'][] = [
                            'label' => $single,
                            'url' => Url::to(['storage/index', 'path' => $explode[0] . "/$single"])
                        ];
                    }
                }
            }
        }

        return $breadcrumbs;
    }

    /**
     * List all files inside giving path or default path.
     *
     * @param null $path
     * @return string
     * @throws InvalidConfigException
     */
    public function actionIndex($path = null)
    {
        $uploadDirectory = \One::app()->fileManager->getUploadDirectory();
        $breadcrumbs = $this->buildBreadcrumbs($path);
        $path = $path != null ? $uploadDirectory . DIRECTORY_SEPARATOR . $path : $path;
        $folders = $this->findFolders($path);
        $files = $this->findFiles($path);
        $allFiles = array_merge($folders, $files);

        return $this->render('index', [
            'files' => $allFiles,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function actionFileManager()
    {
        if (!\One::app()->request->isAjax) {
            throw new BadRequestHttpException('The requested page doesn\'t exist.');
        }

        $uploadDirectory = \One::app()->fileManager->getUploadDirectory();
//        $files = FileHelper::findFiles($uploadDirectory, [
//            'only' => ['*.jpg', '*.jpeg', '*.png']
//        ]);
        $files = $this->findFiles($uploadDirectory, true);

        return $this->renderAjax('_file-manager', ['files' => $files]);
    }

    /**
     * Create a new folder.
     *
     * @return array
     * @throws \yii\base\Exception
     */
    public function actionNewFolder()
    {
        $this->enableCsrfValidation = false;

        $uploadDirectory = \One::app()->fileManager->getUploadDirectory();
        $folderName = \One::app()->request->post('folder', null);

        if ($folderName == null || strpbrk($folderName, "\\/?%*:|\"<>") !== false) {
            \One::app()->response->format = Response::FORMAT_JSON;
            return ['error' => 'Please write a valid folder name'];
        } else {
            $path = \One::app()->request->post('path', null);
            $redirect = $path == null ? ['index'] : ['index', 'path' => $path];

            if ($path !== null) {
                $path = $uploadDirectory . DIRECTORY_SEPARATOR . $path .
                    DIRECTORY_SEPARATOR . $folderName;
            } else {
                $path = $uploadDirectory . DIRECTORY_SEPARATOR . $folderName;
            }

            if (!file_exists($path) && FileHelper::createDirectory($path)) {
                \One::app()->session->setFlash('success', 'Folder created successfully.');
            } else {
                \One::app()->session->setFlash('error', 'Folder already exist for the given name.');
            }

            $this->redirect($redirect);
        }
    }

    /**
     * Rename a file.
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function actionRename()
    {
        $this->enableCsrfValidation = false;

        $uploadDirectory = \One::app()->fileManager->getUploadDirectory();
        $fileName = \One::app()->request->post('fileName', null);
        $oldName = \One::app()->request->post('oldName', null);

        if ($fileName == null || $oldName == null) {
            \One::app()->response->format = Response::FORMAT_JSON;
            return ['error' => 'No files were selected.'];
        } else {
            $path = \One::app()->request->post('path', null);
            $redirect = $path == null ? ['index'] : ['index', 'path' => $path];

            if ($path !== null) {
                $fullPath = $uploadDirectory . DIRECTORY_SEPARATOR . $path .
                    DIRECTORY_SEPARATOR;
            } else {
                $fullPath = $uploadDirectory . DIRECTORY_SEPARATOR;
            }

            if (!is_dir($fullPath . $oldName)) {
                $type = FileHelper::getMimeType($fullPath . $oldName);
                $explode = explode('.', $oldName);

                /* @var $fileReference Attachment */
                $fileReference = Attachment::find()->where([
                    'name' => $explode[0],
                    'type' => $type,
                    'path' => $path == '' ? null : preg_replace('~/+~', '/', $path)
                ])->one();

                if ($fileReference == null) {
                    \One::app()->response->format = Response::FORMAT_JSON;
                    return ['error' => 'File reference not found and fails to update file name.'];
                }
            }

            if (rename($fullPath . $oldName, $fullPath . $fileName)) {
                if (isset($fileReference)) {
                    $fileReference->name = str_replace('.' . $explode[1], '', $fileName);
                    $fileReference->update();
                }

                \One::app()->session->setFlash('success', 'File name updated successfully.');
            } else {
                \One::app()->session->setFlash('error', 'File name not updated successfully.');
            }

            $this->redirect($redirect);
        }
    }

    /**
     * Delete a file.
     *
     * @return array
     * @throws InvalidConfigException
     * @throws \Exception
     * @throws \yii\base\ErrorException
     */
    public function actionDelete()
    {
        $this->enableCsrfValidation = false;

        $uploadDirectory = \One::app()->fileManager->getUploadDirectory();
        $files = \One::app()->request->post('files', null);

        if ($files == null || empty($files)) {
            \One::app()->response->format = Response::FORMAT_JSON;
            return ['error' => 'No files were selected.'];
        } else {
            $path = \One::app()->request->post('path', null);
            $redirect = $path == null ? ['index'] : ['index', 'path' => $path];
            $deleted = false;

            if ($path !== null) {
                $fullPath = $uploadDirectory . DIRECTORY_SEPARATOR . $path .
                    DIRECTORY_SEPARATOR;
            } else {
                $fullPath = $uploadDirectory . DIRECTORY_SEPARATOR;
            }

            foreach ($files as $file) {
                $filePath = FileHelper::normalizePath($fullPath . $file);
                $path = $path == '' ? null : preg_replace('~/+~', '/', $path);

                if (is_dir($filePath)) {
                    $path = $path == '' ? null : $path . '/' . $file;
                    FileHelper::removeDirectory($filePath);
                    $deleted = true;
                    Attachment::deleteAll(['LIKE', 'path', "$path%", false]);
                } else {
                    $type = FileHelper::getMimeType($filePath);
                    $explode = explode('.', $file);
                    $deleted = unlink($filePath);
                    /* @var $fileReference Attachment */
                    $fileReference = Attachment::find()->where([
                        'name' => $explode[0],
                        'type' => $type,
                        'path' => $path
                    ])->one();

                    if ($fileReference !== null) {
                        $fileReference->delete();
                    }
                }
            }

            if ($deleted) {
                \One::app()->session->setFlash('success', 'Files deleted successfully.');
            } else {
                \One::app()->session->setFlash('error', 'All selected files or some files not deleted successfully.');
            }

            $this->redirect($redirect);
        }
    }

    /**
     * Manage file uploads.
     *
     * @return array
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpload()
    {
        if (!\One::app()->request->isAjax) {
            throw new BadRequestHttpException('The requested page doesn\'t exist.');
        }

        $post = \One::app()->request->post();
        $files = UploadedFile::getInstancesByName('file');

        if (count($files) == 0) {
            \One::app()->response->format = Response::FORMAT_JSON;
            return ['No files selected for upload.'];
        }

        $path = isset($post['path']) && $post['path'] == 'null' ? '' : $post['path'];
        $uploadDirectory = FileHelper::normalizePath(\One::app()->fileManager->getUploadDirectory() .
            DIRECTORY_SEPARATOR . $path);
        $savedFiles = [];

        if (!file_exists($uploadDirectory)) {
            FileHelper::createDirectory($uploadDirectory);
        }

        foreach($files as $file) {
            /* @var $file UploadedFile */
            $fileName = Inflector::slug(str_replace(".$file->extension", '', $file->name));
            $filePath = $uploadDirectory . DIRECTORY_SEPARATOR . "{$fileName}.{$file->extension}";
            $file->saveAs($filePath);
            $savedFiles[] = $this->saveFileReference($file, $fileName, $path);
        }

        \One::app()->session->setFlash('success', 'Files uploaded successfully.');
        \One::app()->response->format = Response::FORMAT_JSON;
        return ['link' => $savedFiles];
    }

    /**
     * Save file reference into the database.
     *
     * @param $file UploadedFile
     * @param $fileName string
     * @param $path string
     * @return string
     */
    protected function saveFileReference($file, $fileName, $path)
    {
        $attachment = new Attachment();
        $attachment->name = $fileName;
        $attachment->ext = $file->extension;
        $attachment->size = $file->size;
        $attachment->type = $file->type;

        if ($path !== '') {
            $attachment->path = preg_replace('~/+~', '/', $path);
        }

        $attachment->save();

        return $attachment->getFileUrl();
    }
}