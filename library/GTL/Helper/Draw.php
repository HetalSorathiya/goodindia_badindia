<?php

/**
 * @license Free to use - no strings. 
 */
class GTL_Helper_Draw extends Zend_View_Helper_Abstract {

    public function Draw($postion = NULL, $full = 0) {
        if ($postion != NULL) {
            if ($full == 0)
                return '<div class="' . $postion . '"><div class="' . $postion . '"><div class="' . $postion . '"></div></div></div>';
            else
                return '<div class="' . $postion . '"></div>';
        }
    }

}