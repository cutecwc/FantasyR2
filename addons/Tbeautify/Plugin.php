<?php
/**
 * 网站美化：包含春节灯笼，网站置灰，鼠标点击效果，语言国际化，悬浮音乐播放器，看板娘等诸多特效！
 * 
 * @package Tbeautify
 * @author 流情
 * @version 1.0.2
 * @update: 2011.06.07
 * @link https://liuqingwushui.top/
 */
class Tbeautify_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'footer');
        return '插件安装成功，请进入设置配置需要的网站特效';
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
        return '插件卸载成功';
    }
    
    /**
     * 获取插件配置面板
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        echo <<<HTML
            <div style="font-family:arial; background:#E8EFD1; padding:8px">
                提示：本插件主要是<b style="color:#CF7000">针对全网站做静态资源引入起到美化的效果</b>，请针对性开启所需要的美化特效.<br>
                多个美化特效开启可能会与网站已有的一些内容产生冲突造成未知影响。建议如网站已存在的功能特效不要重复开启.<br>
                作者：流情(<a href="https://liuqingwushui.top" target="_blank">https://liuqingwushui.top</a>)
            </div>
HTML;
        //春节灯笼特效
        $lantern = new Typecho_Widget_Helper_Form_Element_Text("lantern", null, '', '春节灯笼：', '留空不显示，字符长度1-4，代表4个灯笼');
        $form->addInput($lantern);
        
        //置灰模式
        $grey = new Typecho_Widget_Helper_Form_Element_Radio("grey", ['关闭', '开启'], 0, '置灰模式：', '全站置灰，适合全国哀悼日使用，不忘历史，牢记使命');
        $form->addInput($grey);
        
        //樱花飘落特效
        $cherry = new Typecho_Widget_Helper_Form_Element_Radio("cherry", ['关闭', '开启'], 0, '樱花飘落特效：', '提示：会影响到阅读体验，视情况开启');
        $form->addInput($cherry);
        
         //国际化
        $languageTitle = new Typecho_Widget_Helper_Layout();
        $languageTitle->html('<h3>语言国际化:</h3><p style="font-family:arial; background:#E8EFD1; padding:8px">本功能由translate.js实现翻译，支持数十种语言.全程由js控制,动态实时请求翻译,无需改动页面、无语言配置文件、无API Key.因动态请求翻译,可能会影响到加载速度.视情况选择是否开启</p>');
        $form->addItem($languageTitle);
        $languageOn = new Typecho_Widget_Helper_Form_Element_Radio('languageOn', ['关闭', '开启'], 0, '是否开启语言国际化','开启后,会自动进行国际化语言转化,使用说明：https://gitee.com/mail_osc/translate/');
        $form->addInput($languageOn);
        $languageshow = new Typecho_Widget_Helper_Form_Element_Checkbox('languageshow', array('languageshow' => '隐藏'), array('languageshow'), '是否隐藏语言选择下拉','前台是否隐藏语言下拉选择,默认隐藏.如启动，则由前台手动控制转化语种,下方默认选择语言选项失效');
        $form->addInput($languageshow);
        require_once __DIR__ . '/static/language.php';
        $languageUse = new Typecho_Widget_Helper_Form_Element_Select('languageUse',$language,'chinese_simplified','选择语言','选择你想要转化的国际化语言,将应用到整站');
        $form->addInput($languageUse);
        //设置鼠标点击效果
        $mouseTitle = new Typecho_Widget_Helper_Layout();
        $mouseTitle->html('<h3>鼠标点击特效配置:</h3>');
        $form->addItem($mouseTitle);
        $showMouse = new Typecho_Widget_Helper_Form_Element_Checkbox('showMouse', array('showMouse' => '开启鼠标点击效果'), array(), '是否开启鼠标点击效果');
        $mousejs = array_map('basename', glob(dirname(__FILE__) . '/static/mousejs/*.js'));
        $mousejs = array_combine($mousejs, $mousejs);
        $mouse = new Typecho_Widget_Helper_Form_Element_Select('mousename', $mousejs, 'mousefire', '选择鼠标点击效果',"可选鼠标点击特效：mouselove 爱心特效;mouseword 文字特效;mousefire 烟花特效;mousestar 星星拖尾特效");
        $form->addInput($showMouse);
        $form->addInput($mouse);
        
        //悬浮音乐播放器
        $musicTitle = new Typecho_Widget_Helper_Layout();
        $musicTitle->html('<h3>悬浮音乐播放器配置:</h3>');
        $form->addItem($musicTitle);
        $musicshow = new Typecho_Widget_Helper_Form_Element_Checkbox('musicshow', array('musicshow' => '开启悬浮音乐'), array(), '是否开启悬浮音乐','配置项说明：https://musicplayer.xfyun.club/');
        $form->addInput($musicshow);
        $mstyle = array(
            'xf-original' => '默认主题',
            'xf-sky' => '天空',
            'xf-orange' => '橙子',
            'xf-darkGreen' => '墨绿',
            'xf-orange' => '橙子',
            'xf-wineRed' => '酒红',
            'xf-girlPink' => '少女粉'
        );
        $musictheme = new Typecho_Widget_Helper_Form_Element_Select('musictheme', $mstyle, 'xf-original', '选择主题效果',"提供了多种不同主题色样式，请根据网站主题色选择契合的播放器主题色");
        $form->addInput($musictheme);
        //看板娘
        $live2dTitle = new Typecho_Widget_Helper_Layout();
        $live2dTitle->html('<h3>看板娘模型配置:</h3>');
        $form->addItem($live2dTitle);
        $model = array(
            'no'  =>'不要',
            '/Tbeautify/live2d/model/tororo/assets/tororo.model.json' => '小白猫',
            '/Tbeautify/live2d/model/hijiki/assets/hijiki.model.json' => '小黑猫',
            '/Tbeautify/live2d/model/platelet/model.json' => '血小板',
        );
        $live2d = new Typecho_Widget_Helper_Form_Element_Select('live2d',$model,'no','看板娘模型','选择你想要的看板娘模型，这样你就可以在前台看到它啦！');
        $form->addInput($live2d);
        $ModelHeight = new Typecho_Widget_Helper_Form_Element_Text('ModelHeight', NULL, '320', '自定义模型高度', '设置/自定义模型的高度');
        $form->addInput($ModelHeight);
	    $ModelWidth = new Typecho_Widget_Helper_Form_Element_Text('ModelWidth', NULL, '300', '自定义模型宽度', '设置/自定义模型的宽度');
        $form->addInput($ModelWidth);
	    $customModel = new Typecho_Widget_Helper_Form_Element_Text('customModel', NULL, NULL, '自定义 Live2d 模型', '填入 Live2d 模型的 json 地址，会代替预设模型显示在首页，填入后上一个设置项失效。<hr>');
        $form->addInput($customModel);
        
    }
    
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render(){
        
    }
    
    /**
     * 个人用户的配置面板
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     *为header添加静态文件
     *@return void
     */
    public static function header() {
        $grey = Helper::options()->plugin('Tbeautify')->grey;
        if($grey==1){
            echo <<<HTML
<style>
        body{
            filter: grayscale(100%);
            -webkit-filter: grayscale(100%); /* 兼容 Safari 和旧版浏览器 */
            -moz-filter: grayscale(100%); /* 兼容 Firefox */
            -ms-filter: grayscale(100%); /* 兼容 IE */
            -o-filter: grayscale(100%); /* 兼容 Opera */
        }
</style>
HTML;
        }
        //悬浮音乐播放器
        $musictheme = Helper::options()->plugin('Tbeautify')->musictheme;
        $musicshow = Helper::options()->plugin('Tbeautify')->musicshow;
        if($musicshow){
             echo <<<HTML
    <div id="xf-MusicPlayer" data-random="true" data-cdnName="https://player.xfyun.club/js" data-themeColor="{$musictheme}"  data-memory="1"></div>
    <script src="https://player.xfyun.club/js/xf-MusicPlayer/js/xf-MusicPlayer.min.js"></script>
HTML;
        }
        //语言国际化
        $languageOn = Helper::options()->plugin('Tbeautify')->languageOn;
        $languageshow = Helper::options()->plugin('Tbeautify')->languageshow;
        $languageUse = Helper::options()->plugin('Tbeautify')->languageUse;
        if($languageOn==1){
            $languageshowBoolean = $languageshow ? 'false' : 'true';
            echo <<<HTML
    <script src="https://cdn.staticfile.net/translate.js/3.3.0/translate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            translate.service.use('client.edge'); 
            try{ 
                translate.listener.start();
                translate.selectLanguageTag.show = {$languageshowBoolean}; // 隐藏语言选择按钮
                translate.language.setLocal('chinese_simplified');
                if(!translate.selectLanguageTag.show){translate.changeLanguage('{$languageUse}')};  // 设置为英文
                translate.language.clearCacheLanguage();   //清除历史翻译语种的缓存
                translate.setAutoDiscriminateLocalLanguage();
                translate.service.use('client.edge');
                translate.language.setUrlParamControl(); 
            }catch(e){ console.log(e); }
            translate.execute();
        })
    </script>
    <style>
        #translate>.translateSelectLanguage {
            position: absolute;
            right: 2rem;
            top: 2rem;
            font-size: 1rem;
            padding: 0.3rem;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            border: 1px solid #C9C9C9;
            background-color: #fff;
            color: #555;
        }
    </style>
HTML;
        }
    }
    
     /**
     *为footer添加静态文件
     *@return void
     */
    public static function footer() {
        //灯笼特效
        $lantern = Helper::options()->plugin('Tbeautify')->lantern;
        if(!empty($lantern)){   
            $jsUrl = Helper::options()->pluginUrl . '/Tbeautify/static/Lantern.js?text='.$lantern;
            echo <<<HTML
<script type="text/javascript" src="{$jsUrl}"></script>
HTML;
        }
        //樱花飘落特效
        $cherry = Helper::options()->plugin('Tbeautify')->cherry;
        if($cherry==1){   
            $jsUrl = Helper::options()->pluginUrl . '/Tbeautify/static/Blossom.js';
            echo <<<HTML
<script type="text/javascript" src="{$jsUrl}"></script>
HTML;
        }
        //鼠标点击特效
        $mouse = Helper::options()->plugin('Tbeautify')->mousename;
        $showMouse = Helper::options()->plugin('Tbeautify')->showMouse;
        if($showMouse){
            $mouseurl = Helper::options()->pluginUrl . '/Tbeautify/static/mousejs/' . $mouse;
             echo <<<HTML
<script type="text/javascript" src="{$mouseurl}"></script>
HTML;
        }
        //看板娘模型
        $live2d = Helper::options()->plugin('Tbeautify')->live2d;
        $ModelHeight = Helper::options()->plugin('Tbeautify')->ModelHeight;
        $ModelWidth = Helper::options()->plugin('Tbeautify')->ModelWidth;
        $customModel = Helper::options()->plugin('Tbeautify')->customModel;
        if(empty($customModel)&&$live2d!='no'){
            $live2dUrl = Helper::options()->pluginUrl . $live2d;
        }
        if(!empty($customModel)){
            $live2dUrl = $customModel;
        }
        $L2Dwidget = Helper::options()->pluginUrl . '/Tbeautify/live2d/L2Dwidget.min.js';
        if(!empty($live2dUrl)){
        echo <<<HTML
 <script type="text/javascript" charset="utf-8" src="{$L2Dwidget}"></script>
    <script>
        L2Dwidget.init({
            "model": {
            　　　　jsonPath: "{$live2dUrl}",
                    "scale": 1
            },
            "display": {
                "position": "right", //模型的表现位置
                "width": {$ModelWidth}, //模型的宽度
                "height": {$ModelHeight}, //模型的高度
                "hOffset": 0,   //模型的横向偏移
                "vOffset": -20  //模型的纵向偏移 
            },
            "mobile": {
                "show": false,   //是否在移动端显示模型
                "scale": 0.5    //模型在移动端显示时的缩放比例
            },
            "react": {
                "opacityDefault": 0.7, //模型默认透明度
                "opacityOnHover": 0.2   //模型鼠标悬停时的透明度
            },
        });
    </script>
HTML;
        }
    }
}
