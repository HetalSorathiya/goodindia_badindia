<?php
class GTL_Paginator{
	//Items per page
	protected $itemsPerPage;
	//Total Items
	protected $itemsTotal;
	//Current Page
	protected $currentPage;
	//No of Pages
	protected $numPages;
	//Middle Range
	protected $midRange;
	//Lower Limit
	protected $low;
	//Higher Limit
	protected $high;
	//Limit
	protected $limit;
	//Generated Output
	protected $html;
	//Default Items Per Page
	protected $defaultItemsPerPage = 25;
	//do we need to show page nos ?
	protected $displayPages;

	public function __construct()
	{
	    $this->itemsTotal = 0;
		$this->currentPage = 1;
		$this->midRange = 5;
		$this->itemsPerPage = $this->defaultItemsPerPage;
		$this->html = "";
		$this->displayPages = true;
	}

	public function setItemsPerPage($itemsPerPage)
	{
	    $this->itemsPerPage = $itemsPerPage;
	}

	public function getItemsPerPage()
	{
	    return $this->itemsPerPage;
	}

	public function setItemsTotal($itemsTotal)
	{
	    $this->itemsTotal = $itemsTotal;
	}

	public function getItemsTotal()
	{
	    return $this->itemsTotal;
	}

	public function setMiddleRange($midRange)
	{
	    $this->midRange = $midRange;
	}

	public function getMiddleRange()
	{
	    return $this->midRange;
	}

	public function setDisplayPages($displayPages)
	{
	    $this->displayPages = $displayPages;
	}

	public function getDisplayPages()
	{
	    return $this->displayPages;
	}


	public function setCurrentPage($currentPage)
	{
	    // must be numeric > 0
	    $this->currentPage = $currentPage;
	}

	public function getCurrentPage()
	{
	    return $this->currentPage;
	}

	public function getTotalPages()
	{
	    return $this->numPages;
	}

	public function paginate($link)
	{
		if(isset($_GET['ipp']) && $_GET['ipp'] == 'All')
		{
			$this->numPages = ceil($this->itemsTotal/$this->defaultItemsPerPage);
			$this->itemsPerPage = $this->defaultItemsPerPage;
		}
		else
		{
			if(!is_numeric($this->itemsPerPage) OR $this->itemsPerPage <= 0)
				$this->itemsPerPage = $this->defaultItemsPerPage;
			$this->numPages = ceil($this->itemsTotal/$this->itemsPerPage);
		}

		if(!is_numeric($this->currentPage) OR $this->currentPage < 1)
			$this->currentPage = 1;


		if($this->numPages > 0 && $this->currentPage > $this->numPages)
			$this->currentPage = $this->numPages;

		$prevPage = $this->currentPage-1;
		$nextPage = $this->currentPage+1;
		/*if($_GET)
		{
			$args = explode("&",$_SERVER['QUERY_STRING']);
			foreach($args as $arg)
			{
				$keyval = explode("=",$arg);
				if($keyval[0] != "page" AND $keyval[0] != "ipp") $this->querystring .= "&" . $arg;
			}
		}

		if($_POST)
		{
			foreach($_POST as $key=>$val)
			{
				if($key != "page" AND $key != "ipp") $this->querystring .= "&$key=$val";
			}
		}*/

		if($this->displayPages)
		{
		    /*Previous link*/
                    
		    $linkTemp = str_replace("PAGENO", $prevPage, $link);
		    $this->html .='<ul class="pagination pagination-md pull-right">';
                    $this->html .= ($this->currentPage > 0 AND $this->currentPage != 1) ? 
                          ' <li><a href="' . $linkTemp . '">«</a></li>':' <li><a href="javascript:void(0)">«</a></li>';
                            //  '<a class="YellowBtnBack" href="' . $linkTemp . '">Back</a>':
                            // '<a class="YellowBtnBackDisable" href="#" onclick="return false;">Back</a>';
                            //$this->html .= "<span class=\"MiddlePagi\">";

			if($this->numPages > 10)
			{
				$this->startRange = $this->currentPage - floor($this->midRange/2);
				$this->endRange = $this->currentPage + floor($this->midRange/2);//echo "<br />";

				if($this->startRange <= 0)
				{
					$this->endRange += abs($this->startRange)+1;
					$this->startRange = 1;
				}
				if($this->endRange > $this->numPages)
				{
					$this->startRange -= $this->endRange-$this->numPages;
					$this->endRange = $this->numPages;
				}

				$this->range = range($this->startRange,$this->endRange);

				for($i=1;$i<=$this->numPages;$i++)
				{
					if($this->range[0] > 2 AND $i == $this->range[0]) $this->html .= "  ";
					// loop through all pages. if first, last, or in range, display
					if($i==1 Or $i==$this->numPages Or in_array($i,$this->range))
					{
					    $linkTemp = str_replace("PAGENO", $i, $link);
					    $this->html .= ($i == $this->currentPage) ? 
                                                    '<li class="active"><a href="javascript:void(0)">'.$i.'</a></li>':
                                                    '<li class=""><a href="' . $linkTemp . '">'.$i.'</a></li>';
                                                   // "<a class=\"current\" href=\"#\" onclick=\"return false;\">$i</a> ":
                                                   // "<a class=\"paginate\" href=\"" . $linkTemp . "\">$i</a> ";
					}
					if($this->range[$this->midRange-1] < $this->numPages-1 AND $i == $this->range[$this->midRange-1]) $this->html .= "  ";
				}
			}
			else
			{
				for($i=1;$i<=$this->numPages;$i++)
				{
				    $linkTemp = str_replace("PAGENO", $i, $link);
					$this->html .= ($i == $this->currentPage) ? 
                                                 '<li class="active"><a href="#">'.$i.'</a></li>':
                                                 '<li class=""><a href="' . $linkTemp . '">'.$i.'</a></li>';
                                               // "<a class=\"current\" href=\"#\" onclick=\"return false;\">$i</a> ":
                                               // "<a class=\"paginate\" href=\"" . $linkTemp . "\">$i</a> ";
				}
				//$this->html .= "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">All</a> \n";
			}
			/*Next link*/
			$linkTemp = str_replace("PAGENO", $nextPage, $link);
			//$this->html .= (($this->currentPage != $this->numPages AND $this->itemsTotal >= 10) AND ($_GET['page'] != 'All')) ? "<a class=\"paginate\" href=\"#page=$nextPage\">Next &raquo;</a>\n":"<span class=\"inactive\" href=\"#\">&raquo; Next</span>\n";
			
			$this->html .= ($this->currentPage != $this->numPages AND $this->itemsTotal >= $this->itemsPerPage) ? 
                                '<li><a href="' . $linkTemp . '">»</a></li>':'<li><a href="javascript:void(0)">»</a></li>';
                              //  '<a class="YellowBtnNext" href="' . $linkTemp . '">Next</a>':
                              //  '<a class="YellowBtnNextDisable" href="#" onclick="return false;">Next</a>';
			//$this->html .= ($_GET['page'] == 'All') ? "<a class=\"current\" style=\"margin-left:10px\" href=\"#\">All</a> \n":"<a class=\"paginate\" style=\"margin-left:10px\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">All</a> \n";
                        $this->html .= "</ul>";
                        
                                }
		$this->low = ($this->currentPage-1) * $this->itemsPerPage;
		$this->high = (isset($_GET['ipp']) && $_GET['ipp'] == 'All') ? $this->itemsTotal:($this->currentPage * $this->itemsPerPage)-1;
		if($this->high >= $this->itemsTotal) $this->high = ($this->itemsTotal-1);
		$this->limit = (isset($_GET['ipp']) && $_GET['ipp'] == 'All') ? "":" LIMIT $this->low,$this->itemsTotal";
	}

	public function displayItemsPerPage()
	{
		$items = '';
		$ipp_array = array(10,25,50,100,'All');
		foreach($ipp_array as $ipp_opt)
		{
		    $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";
		}
		return "<span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select>\n";
	}

	public function displayJumpMenu()
	{
		for($i=1;$i<=$this->numPages;$i++)
		{
			$option .= ($i==$this->currentPage) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";
		}
		return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page$this->querystring';return false\">$option</select>\n";
	}

	public function displayPages()
	{
		return $this->html;
	}

	public function getLow()
	{
	    return $this->low;
	}

	public function getHigh()
	{
	    return $this->high;
	}

	public function getLimit()
	{
	    return $this->limit;
	}

	public function isNextVisible()
	{
		return ($this->currentPage != $this->numPages);
	}

	public function getPaginationStatus()
    {
		if($this->currentPage == 1)
        {
            $previous = false;
        }
        else
        {
            $previous = true;
        }
        if($this->currentPage < $this->numPages)
        {
            $next = true;
        }
        else
        {
            $next = false;
        }

        $start = ($this->currentPage - 1) * $this->itemsPerPage;

        return array('start' => $start, 'previous' => $previous, 'next' => $next);
    }
}
