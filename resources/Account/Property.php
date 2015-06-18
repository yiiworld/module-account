<?php
/**
 * Property.php
 * @author Revin Roman
 */

namespace resources\Account;

/**
 * Class Property
 * @package resources\User
 *
 * @property integer $account_id
 * @property string $key
 * @property string $value
 * @property integer $created_at
 * @property integer $updated_at
 */
class Property extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'yii\behaviors\TimestampBehavior',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            /** type validators */
            [['key', 'value'], 'string'],
            [['account_id', 'created_at', 'updated_at'], 'integer'],

            /** semantic validators */
            [['account_id', 'key'], 'required'],
            [['key', 'value'], 'filter', 'filter' => 'str_clean'],

            /** default values */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_id' => \Yii::t('account', 'User'),
            'key' => \Yii::t('account', 'Key'),
            'value' => \Yii::t('account', 'Value'),
            'created_at' => \Yii::t('account', 'Created at'),
            'updated_at' => \Yii::t('account', 'Updated at'),
        ];
    }

    /**
     * @param integer $account_id
     * @param string $key
     * @param mixed $value
     * @return static
     * @throw \InvalidArgumentException
     */
    public static function push($account_id, $key, $value)
    {
        /** @var static $Property */
        $Property = static::find()
            ->byAccountId($account_id)
            ->byKey($key)
            ->one();

        if (empty($Property)) {
            $Property = new static;
        }

        $Property->setAttributes([
            'account_id' => $account_id,
            'key' => $key,
            'value' => (string)$value,
        ]);

        $Property->validate() && $Property->save();

        return $Property;
    }

    /**
     * @return \resources\Account\queries\AccountPropertyQuery
     */
    public static function find()
    {
        return new \resources\Account\queries\AccountPropertyQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_property}}';
    }
}