<?php
/*
 * This class is used to display datagrid. 
 */
class Gtl_View_Helper_Grid extends Zend_View_Helper_Abstract {

    function grid($grid, $text = '', $image = '', $alt = '') {
        $image = '/public/images/' . $image;
        $fgrid = '<div class="DataGrid"><div class="DataHd">';

        if (!empty($image)) {
            $fgrid .= '<img src="' . $image . '" alt="' . $alt . '" />';
        }
        $fgrid .= $text . '</div>' . $grid;
        $fgrid .= '</div></div>';

        echo $fgrid;
    }

}
