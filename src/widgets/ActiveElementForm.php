<?php
namespace vuelte\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidCallException;

class ActiveElementForm extends \yii\widgets\ActiveForm
{
    function init()
    {
        if (!isset($this->fieldConfig['class']))
            $this->fieldConfig['class'] = ActiveElementField::className();

        parent::init(); // TODO: Change the autogenerated stub
    }

    /*function run()
    {
        if (!empty($this->_fields)) {
            throw new InvalidCallException('Each beginField() should have a matching endField() call.');
        }

        //获取内容
        $content = ob_get_clean();

        //创建el-from标签
        $options = array_merge($this->options,["method" => $this->method]);
        echo Html::beginTag("el-form", $options);

        //创建原始的Yii-From，用于CSRF
        echo Html::beginForm($this->action, $this->method, []);
        if ($this->enableClientScript) {$this->registerClientScript();}
        echo Html::endForm();

        //输出内容
        echo $content;

        //结束el-from标签
        echo Html::endTag("el-form");
    }*/
}
?>