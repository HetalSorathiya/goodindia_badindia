<?php 
class Form_Custom_General extends Zend_Form {

    public function createElement($type, $name, $options = null)
    {
        if (!is_string($type)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Element type must be a string indicating type');
        }

        if (!is_string($name)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Element name must be a string');
        }

        $prefixPaths              = array();
        $prefixPaths['decorator'] = $this->getPluginLoader('decorator')->getPaths();
        if (!empty($this->_elementPrefixPaths)) {
            $prefixPaths = array_merge($prefixPaths, $this->_elementPrefixPaths);
        }

        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if ((null === $options) || !is_array($options)) {
            $options = array('prefixPath' => $prefixPaths);
        } elseif (is_array($options)) {
            if (array_key_exists('prefixPath', $options)) {
                $options['prefixPath'] = array_merge($prefixPaths, $options['prefixPath']);
            } else {
                $options['prefixPath'] = $prefixPaths;
            }
        }

        $class = $this->getPluginLoader(self::ELEMENT)->load($type);
        $element = new $class($name, $options);

		$element->removeDecorator('DtDdWrapper')
			->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->removeDecorator('Errors');
        return $element;
    }

}
?>