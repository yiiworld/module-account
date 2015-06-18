<?php
/**
 * SignInAssetBundle.php
 * @author Revin Roman
 */

namespace cookyii\modules\Account\backend\_assets;

/**
 * Class SignInAssetBundle
 * @package cookyii\modules\Account\backend\_assets
 */
class SignInAssetBundle extends \yii\web\AssetBundle
{

    public $sourcePath;

    public $css = [
        'signin.css',
    ];

    public $js = [
        'SignInController.js',
    ];

    public $depends = [
        'backend\_assets\AppAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/_sources';

        parent::init();
    }
}