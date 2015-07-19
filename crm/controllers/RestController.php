<?php
/**
 * RestController.php
 * @author Revin Roman
 */

namespace cookyii\modules\Account\crm\controllers;

use cookyii\modules\Account;

/**
 * Class RestController
 * @package cookyii\modules\Account\crm\controllers
 */
class RestController extends \cookyii\rest\Controller
{

    /**
     * @inheritdoc
     */
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['in'],
                'roles' => ['?', '@'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'in' => ['POST'],
        ];
    }

    /**
     * @return array
     */
    public function actionIn()
    {
        $result = [
            'result' => false,
            'message' => [
                'title' => \Yii::t('account', 'Sign In'),
                'text' => \Yii::t('account', 'Unknown error.'),
            ]
        ];

        $SignInForm = new Account\crm\forms\SignInForm();

        if ($SignInForm->load(Request()->post()) && $SignInForm->validate() && $SignInForm->login()) {
            $result = [
                'result' => true,
                'message' => [
                    'title' => \Yii::t('account', 'Sign In'),
                    'text' => \Yii::t('account', 'Welcome!'),
                ],
                'redirect' => UrlManager()->createUrl(['/']),
            ];
        }

        if ($SignInForm->hasErrors()) {
            $result = [
                'result' => false,
                'message' => [
                    'title' => \Yii::t('account', 'Sign In'),
                    'text' => \Yii::t('account', 'Form errors.'),
                ],
                'errors' => $SignInForm->getFirstErrors(),
            ];
        }

        return $result;
    }
}