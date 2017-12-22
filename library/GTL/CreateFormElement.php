<?php

/**
 * Textarea form element
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Textarea.php 8064 2008-02-16 10:58:39Z thomas $
 */
class GTL_CreateFormElement extends Zend_Form {

    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function generateForm($config_array = null) {
        foreach ($config_array as $key => $value) {
            $form_element[$key] = $this->CreateElement($value['type'], $key)
                    ->setLabel(($value['label']));

            if (isset($value['parameter'])) {
                foreach ($value['parameter'] as $v_key => $v_value)
                    $form_element[$key]->setAttrib($v_key, $v_value);
            }

            if (isset($value['setRequired'])) {
                $form_element[$key]->setRequired($value['setRequired']);
            }

            $form_element[$key]->setDecorators(
                    array('ViewHelper',
                        array('Description', 'options' => array('tag' => '', 'escape' => false)),
                        'Errors')
            );

            if (isset($value['setMultiOptions'])) {
                $form_element[$key]->setmultiOptions($value['setMultiOptions']);
            }
            if (isset($value['addMultiOption'])) {
                $form_element[$key]->setmultiOptions($value['addMultiOption']);
            }
            if (isset($value['value'])) {
                $form_element[$key]->setValue($value['value']);
            }
            if (isset($value['setSeparator'])) {
                $form_element[$key]->setSeparator($value['setSeparator']);
            }

            /**
             * Used for file type
             */
            if (isset($value['setDestination'])) {
                $form_element[$key]->setDestination($value['setDestination']);
            }
            if (isset($value['setDestination'])) {
                $form_element[$key]->setDestination($value['setDestination']);
            }

            $form_element[$key]->removeDecorator('Errors');

            if (isset($value['setRegisterInArrayValidator'])) {
                $form_element[$key]->setRegisterInArrayValidator($value['setRegisterInArrayValidator']);
            }
        }

        $this->addElements($form_element);
    }

}
