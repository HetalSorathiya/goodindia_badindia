<?php
class Gtl_Grid
{
    public static function getGridObject($options,$limit = '')
    {
        $registry = Zend_Registry::getInstance();
        $view = $registry->view;

        $config = new Zend_Config_Ini('./application/configs/grid.ini', 'production');
        $grid = Bvb_Grid::factory('Table', $config, '');
        $grid->setSource(new Bvb_Grid_Source_Zend_Select($options['select']));
        $grid->setNumberRecordsPerPage($limit);
        //$grid->setPaginationInterval(array(10 => 10, 20 => 20, 50 => 50, 100 => 100));
        $grid->setPaginationInterval(array());
        
        $grid->setExport(array());
        if (isset($options['Columns']) && is_array($options['Columns'])) {
            foreach ($options['Columns'] as $columnsKey => $columnsValue) {
                $grid->updateColumn($columnsKey, $columnsValue);
            }
        }
        $filters = new Bvb_Grid_Filters();
        if (is_array($options['Filters'])) {
            foreach ($options['Filters'] as $key => $value) {
                $filters->addFilter($key, $value);
            }
        }
        if (is_array($options['FiltersText'])) {
            foreach ($options['FiltersText'] as $filter) {
                $filters->addFilter($filter);
            }
        }

        if ($filters->_filters != null)
            $grid->addFilters($filters);
        else
            $grid->setNoFilters(1);

        if (isset($options['Order'])) {
            $grid->order($options['Order']);
        }
		
	   	$grid->ajax='grid';
	   		
        if (isset($options['Actions']) && is_array($options['Actions'])) {
            $decorator = "";
            $seperator = " ";

            foreach ($options['Actions'] as $key => $value) {
                if ('status' == $key) {
                    $link = str_replace(urlencode("#"), "{{" . $value['field'] ."}}", $value['link']);
                    $link = str_replace(urlencode("?"), "{{" . $value['status_field'] ."}}", $link);
                    $statusText =  ($value['title'] == 'Active')?'Inactive':'Active';

                    if (isset($value['image'])) {
                        $decorator .= "<a title='" . $value['title'] . "' href='" . $link . "'><img src='" . $view->imagePath(strtolower($value['image']), $view->moduleName) . "' title='" . $value['title'] . "' /></a>";
                    } else {
                        $decorator .= "<a title='" . $value['title'] . "' href='" . $link . "'>" . $value['title']. "</a>";
                    }
                } else if (isset($value['image'])) {
                    $decorator .= "<a title='" . $value['title'] . "' href='" . str_replace(urlencode("#"), "{{" . $value['field'] ."}}", $value['link']) . "'><img src='" . $view->imagePath(strtolower($value['image']), $view->moduleName) . "' title='" . $value['title'] . "' /></a>";
        		} else {
                    $decorator .= "<a href='" . str_replace(urlencode("#"), "{{" . $value['field'] ."}}", $value['link']) . "'>" . $value['text'] . "</a>";
                }
                $decorator .= $seperator;
            }
            $grid->updateColumn('date', array('format' => array('date', array('date_format' => "d-MM-Y"))));
            $right = new Bvb_Grid_Extra_Column();
            $right->position('right')->name('Action')->decorator($decorator);
            $grid->addExtraColumns($right);
			
            if(isset($options['GridId']))
            	$grid->ajax=$options['GridId']['id'];
            else
            	$grid->ajax='grid';
        }
        elseif (isset($options['Checkbox']) && is_array($options['Checkbox'])) {
            $decorator = "";
            $seperator = " ";

            foreach ($options['Checkbox'] as $key => $value) {
                $decorator .= "<input type='checkbox' name= '" . $value['name'] . "' id= '" . "chk{{" . $value['id'] ."}}" . "'value= '" . "{{" . $value['fldvalue'] ."}}" . "'". "'" . "{{" . $value['chkid'] ."}}" . "'"."/>";
                $decorator .= $seperator;
            }

            $right = new Bvb_Grid_Extra_Column();
            $right->position('right')->name('Checkbox')->decorator($decorator);
            $grid->addExtraColumns($right);
        }
        return $grid->deploy();
    }
}