<?php
namespace vuelte\widgets;

use Yii;
use yii\helpers\Html;

/**
 * 放弃使用
 * Class ActiveElementField
 * @package vuelte\widgets
 */
class ActiveElementField extends \yii\widgets\ActiveField {
    /**
     * init
     */
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        //css
        $this->registerUploadCss();
        $this->registerAvatarCss();
    }

    /**
     * @param string $name
     * @param array $params
     * @return ActiveElementField
     */
    public function __call($name, $params)
    {
        if(substr($name,0,3) == "el_"){
            //get element
            $name = substr($name,3);
            $name = preg_replace_callback('/([A-Z]{1})/',function($matches){return '-'.strtolower($matches[0]);},$name);
            //create element
            $element = $this->el_element("el-".$name, $params[0]);
            return $this->el_formItem($element);
        }
        else{
            return parent::__call($name,$params);
        }
    }

    /**
     * @param $content
     * @return string
     */
    private function el_formItem($content){
        //获取 label 和 error
        $label = Html::encode($this->model->getAttributeLabel($this->attribute));
        $error = $this->model->getFirstError($this->attribute);

        //设置Html
        $html[] = Html::beginTag("el-form-item",[
            "label"=>$label,
            "prop"=>$this->attribute,
            "error"=>$error
        ]);
        $html[] = $content;
        $html[] = Html::endTag("el-form-item");

        //返回
        return implode("\n",$html);
    }

    /**
     * @param $element
     * @param array $options
     * @return string
     */
    private function el_element($element, $options = []){
        //$options['value'] = $this->model->{$this->attribute};
        //$options['v-model'] = $v_model ? $v_model : null;
        $options['name'] = Html::getInputName($this->model, $this->attribute);
        $element = Html::tag($element, null, $options);
        return $element;
    }

    //region 元素封装

    /**
     * @param $items
     * @param array $options
     * @return ActiveElementField
     */
    public function el_select($items, $options = []){
        //construct
        $html[] = Html::beginTag("el-select",$options);
        foreach ($items as $key => $value){
            $option_attr = ["label" => $value];
            if(is_string($this->model->{$this->attribute}))
                $option_attr["value"] = "$key";
            else
                $option_attr[":value"] = "$key";
            $html[] = Html::tag("el-option",null,$option_attr);
        }
        $html[] = Html::endTag("el-select");

        //element
        $element = implode("\n",$html);

        //return
        return $this->el_formItem($element);
    }

    /**
     * @param $items
     * @param array $options
     * @return ActiveElementField
     */
    public function el_checkboxList($items, $options = []){
        //construct
        $html[] = Html::beginTag("el-checkbox-group",$options);
        foreach ($items as $key => $value){
            $html[] = Html::tag("el-checkbox",$value,["label" => $key]);
        }
        $html[] = Html::endTag("el-checkbox-group");

        //element
        $element = implode("\n",$html);

        //return
        return $this->el_formItem($element);
    }

    /**
     * @param array $options
     * @return ActiveElementField
     */
    public function el_avatar($options = []){
        //element
        $options = array_merge($options,[
            "class" => 'avatar-uploader',
            ":show-file-list" => "true",
        ]);

        //imageUrl
        $imageUrl = isset($options["imageUrl"]) ? $options["imageUrl"] : null;
        if($imageUrl)
            $content = '<img v-if="'.$imageUrl.'" :src="'.$imageUrl.'" class="avatar"><i v-else class="el-icon-plus avatar-uploader-icon"></i>';
        else
            $content = '<i class="el-icon-plus avatar-uploader-icon"></i>';

        //element
        $element = Html::tag('el-upload', $content, $options);

        //return
        return $this->el_formItem($element);
    }

    /**
     * @param array $options
     * @return ActiveElementField
     */
    public function el_textarea($options = []){
        $options = array_merge($options,["type" => 'textarea']);
        $element = $this->el_element("el-input", $options);
        return $this->el_formItem($element);
    }

    /**
     * @param array $options
     * @return ActiveElementField
     */
    public function el_upload($options = []){
        //element
        $options = array_merge($options,[
            ":show-file-list" => "true",
        ]);

        //content
        $content = isset($options['content']) ? $options['content'] : '';
        unset($options['content']);

        //show
        $show = isset($options['show']) ? $options['show'] : '';
        unset($options['show']);

        //element
        $elements[] = Html::tag('el-upload', $content, $options);
        $elements[] = $show;
        $element = implode("\n",$elements);

        //return
        return $this->el_formItem($element);
    }

    //endregion

    //region 附加样式

    //上传相关的css调整
    private function registerUploadCss(){
        //css
        $view = Yii::$app->getView();
        $view->registerCss(".el-upload__input{
                display: none !important;
            }"
        );
    }

    //上传头像相关css调整
    private function registerAvatarCss(){
        //css
        $view = Yii::$app->getView();
        $view->registerCss(".avatar-uploader .el-upload {
                border: 1px dashed #d9d9d9;
                border-radius: 6px;
                cursor: pointer;
                position: relative;
                overflow: hidden;
            }
            .avatar-uploader .el-upload:hover {
                border-color: #409EFF;
            }
            .avatar-uploader-icon {
                font-size: 28px;
                color: #8c939d;
                width: 78px;
                height: 78px;
                line-height: 78px;
                text-align: center;
            }
            .avatar {
                width: 78px;
                height: 78px;
                display: block;
            }"
        );
    }

    //endregion
}
