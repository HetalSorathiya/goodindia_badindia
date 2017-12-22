<?php

/*
 * This class is used to add css and JS files in front panel.
 */

class GTL_Helper_DefaultHead extends Zend_View_Helper_Abstract {

    protected $layoutn;

    public function DefaultHead($layout, $front = false) {
        $this->layoutn = $layout;
        if ($front) {
            $this->frontPageHead();
        } else {
            $this->innerPageHead();
        }
    }

    protected function frontPageHead() {
        $registry = Zend_Registry::getInstance();
        if (!isset($this->registry->language)){
            $this->registry->language = 'en';
        }
        $layout = $this->layoutn;
        $layout->headLink()
                ->appendStylesheet($registry->domainName . 'public/css/reset.css')
                ->appendStylesheet($registry->domainName . 'public/css/jquery.validate.css')
                ->appendStylesheet($registry->domainName . 'public/css/jquery.rte.css')
                ->appendStylesheet($registry->domainName . 'public/css/silver2date-icons.css')
                ->appendStylesheet($registry->domainName . 'public/css/silver2date-forms.css')
                ->appendStylesheet($registry->domainName . 'public/css/silver2date-base.css')
                ->appendStylesheet($registry->domainName . 'public/css/silver2date-administrator.css')
                ->appendStylesheet($registry->domainName . 'public/css/jquery-ui-1.7.2.custom.css')
                ->appendStylesheet($registry->domainName . 'public/css/jpassword.css')
                ->appendStylesheet($registry->domainName . 'public/css/silver2date-homepage.css');
        echo $layout->headLink();

        $layout->headScript()
                ->appendFile($registry->domainName . 'public/js/jquery-1.3.2.min.js')
                ->appendFile($registry->domainName . 'public/js/jquery.innerfade.js')
                ->appendFile($registry->domainName . 'public/js/silver2date-homepage.js')
                ->appendFile($registry->domainName . 'public/js/jquery-1.3.2.min.js')
                ->appendFile($registry->domainName . 'public/js/jquery-1.3.2.min.js');

        echo $layout->headScript();
    }

    protected function innerPageHead() {
        $registry = Zend_Registry::getInstance();
        if (!isset($this->registry->language))
            $this->registry->language = 'en';
        $layout = $this->layoutn;
        $layout->headLink()
                ->appendStylesheet($registry->domainName . 'public/css/reset.css')
                ->appendStylesheet($registry->domainName . 'public/css/jquery.validate.css')
                ->appendStylesheet($registry->domainName . 'public/css/jquery.rte.css')
                ->appendStylesheet($registry->domainName . 'public/css/silver2date-icons.css')
                ->appendStylesheet($registry->domainName . 'public/css/silver2date-forms.css')
                ->appendStylesheet($registry->domainName . 'public/css/silver2date-base.css')
                ->appendStylesheet($registry->domainName . 'public/css/silver2date-administrator.css')
                ->appendStylesheet($registry->domainName . 'public/css/cupertino/jquery-ui-1.7.2.custom.css')
                ->appendStylesheet($registry->domainName . 'public/css/jpassword/jpassword.css');
        echo $layout->headLink();

        $layout->headScript()
                ->appendFile($registry->domainName . 'public/js/' . $registry->language . '.js')
                ->appendFile($registry->domainName . 'public/js/jquery-1.3.2.min.js')
                ->appendFile($registry->domainName . 'public/js/jquery-ui.min.js')
                ->appendFile($registry->domainName . 'public/js/jquery.selectboxes.js')
                ->appendFile($registry->domainName . 'public/js/swfobject.js')
                ->appendFile($registry->domainName . 'public/js/swfupload.js')
                ->appendFile($registry->domainName . 'public/js/jquery.bgiframe.pack.js')
                ->appendFile($registry->domainName . 'public/js/jquery.validate.js')
                ->appendFile($registry->domainName . 'public/js/jquery.innerfade.js')
                ->appendFile($registry->domainName . 'public/js/jquery-ui-rte/jquery.rte.js')
                ->appendFile($registry->domainName . 'public/js/jquery-ui-rte/jquery.rte.tb.js')
                ->appendFile($registry->domainName . 'public/js/silver2date-media.js')
                ->appendFile($registry->domainName . 'public/js/flowplayer-3.1.4.min.js')
                ->appendFile($registry->domainName . 'public/js/silver2date-forms.js')
                ->appendFile($registry->domainName . 'public/js/jquery.jpassword.js')
                ->appendFile($registry->domainName . 'public/js/application.js');


        echo $layout->headScript();
    }

}

