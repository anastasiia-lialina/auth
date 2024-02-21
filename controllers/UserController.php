<?php

namespace app\controllers;

use app\models\User;
use app\models\UserSessions;
use Yii;
use yii\web\Controller;
use \yii\web\Response;

//В идеале наследовать от yii/rest/ActiveController, если было бы нормальное апи а не куча действий в одном action
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    protected function verbs()
    {
        return [
            'auth' => ['GET'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        Yii::$app->set('response', [
            'class' => Response::class,
            'format' => 'json',
        ]);

        return parent::beforeAction($action);
    }

    public function actionAuth()
    {
        try {
            $requestParams = Yii::$app->getRequest()->getBodyParams();
            if (empty($requestParams)) {
                $requestParams = Yii::$app->getRequest()->getQueryParams();
            }

            if (User::verifySig($requestParams) === false) {
                /**
                 * Тут лучше сделать выброс ошибки через throw
                 */
                $this->getResult('Ошибка авторизации в приложении', 'error_key', 400);
            }

            $model = User::findOne($requestParams['id'] ?? 0);
            if ($model === null) {
                $model = new User();
            }

            $model->load($requestParams, '');
            $model->id = $requestParams['id'] ?? 0;
            if ($model->save()) {

                //Сохраняем токен
                $userSession = $model->userSessions ?? new UserSessions();
                $userSession->access_token = $requestParams['access_token'] ?? '';
                $userSession->user_id = $model->id;
                $userSession->save();

                $result = [
                    'access_token' => $userSession->access_token,
                    'user_info' => [
                        'id' => $model->id,
                        'first_name' => $model->first_name,
                        'last_name' => $model->last_name,
                        'city' => $model->city,
                        'country' => $model->country,
                    ],
                ];
                return $this->getResult( '', '', 200, $result);
            }

            $error = $model->getFirstErrors();
            return $this->getResult( array_shift($error), 'Params error', 400);

        } catch (\Throwable $e) {
            return $this->getResult('Server error', 'Server error', 500);
        }
     }

     protected function getResult(?string $error = '', string $errorKey = '', int $statusCode = 200, array $data = []): array
     {
         Yii::$app->response->statusCode = $statusCode;
         $errors = [
             'error' => $error,
             'error_key' => $errorKey,
         ];

         return array_merge($data, $errors);
     }
}
