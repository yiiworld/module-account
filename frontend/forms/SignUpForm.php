<?php
/**
 * SignUpForm.php
 * @author Revin Roman
 * @link https://rmrevin.com
 */

namespace cookyii\modules\Account\frontend\forms;

use rmrevin\yii\rbac\RbacFactory;

/**
 * Class SignUpForm
 * @package cookyii\modules\Account\frontend\forms
 */
class SignUpForm extends \yii\base\Model
{

    use \cookyii\db\traits\PopulateErrorsTrait;

    public $name;
    public $email;
    public $password;
    public $password_app;

    public $loginAfterRegister = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            /** type validators */
            [['email'], 'email'],
            [['name', 'email', 'password', 'password'], 'string'],

            /** semantic validators */
            [['name', 'email', 'password', 'password_app'], 'required'],
            [['name', 'email'], 'filter', 'filter' => 'str_clean'],
            [['email'], 'unique', 'targetClass' => \cookyii\modules\Account\resources\Account::className(), 'targetAttribute' => 'email'],
            [['password'], 'compare', 'compareAttribute' => 'password_app'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('account', 'Name'),
            'email' => \Yii::t('account', 'Email'),
            'password' => \Yii::t('account', 'Password'),
            'password_app' => \Yii::t('account', 'Password approve'),
        ];
    }

    /**
     * @return array
     */
    public function formAction()
    {
        return ['/account/rest/up'];
    }

    /**
     * @return bool
     */
    public function register()
    {
        $Account = new \cookyii\modules\Account\resources\Account;
        $Account->setAttributes([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'activated_at' => time(),
        ]);

        $Account->validate() && $Account->save();

        if (!$Account->hasErrors()) {
            $Account->notificationHelper
                ->sendSignUpEmail();

            AuthManager()->assign(RbacFactory::Role(\common\Roles::USER), $Account->id);

            if ($this->loginAfterRegister) {
                User()->login($Account, SignInForm::REMEMBER_TIME);
            }
        }

        if ($Account->hasErrors()) {
            $this->populateErrors($Account, 'name');
        }

        return !$Account->hasErrors();
    }
}