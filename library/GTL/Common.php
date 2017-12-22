<?php

/*
 * RY
 */

class GTL_Common {
	
	   function sendPushNotification($fields) {
   # echo "<pre>";print_r($fields);exit;
       // require_once __DIR__ . '/config.php';
 
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
		$FIREBASE_KEY = 'c5YfoZAYEpk:APA91bFXaSbZKKjF0wiW14uE1cNjvwSQBqTDg1aCuggAqDjawktpGxkOK6X189VhfnqNXCpN8SFmrRXgrWfbzCKcZkpu-lsByiVSwsp3eI6SzTmAKBFI13JySttG7GnSg91bYOE7iKy1';		

 
        $headers = array(
			'Authorization: key=' . $FIREBASE_KEY,
            'Content-Type: application/json'
        );
		//Initializing curl to open a connection
		$ch = curl_init();

		//Setting the curl url
		curl_setopt($ch, CURLOPT_URL, $url);
		
		//setting the method as post
		curl_setopt($ch, CURLOPT_POST, true);

		//adding headers 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//disabling ssl support
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		//adding the fields in json format 
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		//finally executing the curl request 
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}

		//Now close the connection
		curl_close($ch);
//echo $result;exit;
		//and return the result 
		return $result;
    }

    public function IsEmpty($value = '') {
        if (trim($value) == '') {
            return true;
        }
        return false;
    }

    public function IsValidDate($str) {
        $stamp = strtotime($str);

        if (!is_numeric($stamp)) {
            return FALSE;
        }
        $month = date('m', $stamp);
        $day = date('d', $stamp);
        $year = date('Y', $stamp);

        if (checkdate($month, $day, $year)) {
            return TRUE;
        }

        return FALSE;
    }

    public static function getDateDifference($date1, $date2) {
        return round(abs(strtotime($date1) - strtotime($date2)) / 86400);
    }

    /**
     * Collapse the given string to given length
     *
     * This function returns the collapse string with given length.
     *
     * @param string $_text String to collapse
     * @param int $_length maximum length
     * @return string
     */
    public static function getCollapseText($_text, $_length = 150) {
        if (strlen($_text) > $_length) {
            return substr($_text, 0, $_length) . "...";
        }
        return $_text;
    }

    /*
     * This function is used to generate password.
     */

    public static function generatePassword($length = 6, $level = 2) {
        list($usec, $sec) = explode(' ', microtime());
        srand((float) $sec + ((float) $usec * 100000));

        $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
        $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $validchars[3] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";
        $validchars[4] = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $password = "";
        $counter = 0;

        while ($counter < $length) {
            $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);

            // All character must be different
            if (!strstr($password, $actChar)) {
                $password .= $actChar;
                $counter++;
            }
        }

        return $password;
    }

    /**
     * function to get Gender array
     *
     * @return unknown
     */
    public function getGender() {
        $registry = Zend_Registry::getInstance();
        $translate = $registry->translate;

        $gender = array(
            '' => $translate->_('select_c'),
            'M' => $translate->_('man'),
            'F' => $translate->_('woman')
        );
        return $gender;
    }

    /**
     * Function for getting Language array
     *
     * @return unknown
     */
    public function getLanguage() {
        $registry = Zend_Registry::getInstance();
        $translate = $registry->translate;

        $language = array('en' => $translate->_('languages_en'), 'nl' => $translate->_('languages_nl'));

        return $language;
    }

    /*
     * This function is used to check whether user is loggedIn or not.
     */

    public static function isUserLoggedIn() {
        return Zend_Auth::getInstance()->hasIdentity();
    }

    /*
     * This function is used to getLoggedIn user data.
     */

    public static function getLoggedInUserData() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            /* NOTE: type casting works only for single dimension arrays and objects. */
            return (array) Zend_Auth::getInstance()->getIdentity();
        }
        return false;
    }

    /*
     * This function is used to get credential which user has entered for 
     * loggin.
     */

    public static function getAuthAdapter(array $params, $module = 'default') {
        //$registry = Zend_Registry::getInstance();
        // set earlier in Bootstrap
        $adapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $adapter->setTableName('tbl_admin_user');
        $adapter->setIdentityColumn('admin_email');
        $adapter->setCredentialColumn('admin_password');
        $adapter->setIdentity($params['email']);
        $adapter->setCredential(md5($params['password']));
        $select = $adapter->getDbSelect()
                ->where('admin_status = 1');
        return $adapter;
    }

    public static function initializeBreadCrumbs($brdCrmbArray = Array()) {
        $brdCrmb = '';
        if (count($brdCrmbArray) > 0) {
            $brdCrmb .= "<p class='breadcrumbs'>";
            foreach ($brdCrmbArray as $_key => $_val) {
                if ($_key == '') {
                    $brdCrmb .= "$_val";
                } else {
                    $brdCrmb .= "<a title='" . $_val . "' href='" . $_key . "'>" . $_val . "</a> &#187; ";
                }
            }
            $brdCrmb .= "</p>";
        }

        return $brdCrmb;
    }

    /**
     * Function for displaying success and error messages
     *
     * @param string (i.e error,success( $status
     * @param array $message
     */
    public function dispMessages($status, $messages) {
        $output = '';
        $dispMessage = '';
        $status = $status;

        if (!is_array($messages)) {
            $dispMessage .= $messages;
        } elseif (count($messages) == 1) {
            $dispMessage .= $messages[0];
        } else {
            $dispMessage .= '<ul style="margin-bottom:5px">';
            foreach ($messages as $message) {
                $dispMessage .= '<li style="margin-left:20px">' . $message . '</li>';
            }
            $dispMessage .= '</ul>';
        }

        if ($status == 'error') {
            /* $template = '<div class="ui-state-error ui-corner-all" style="padding: 0pt 0.7em;">
              <p style="margin-top:5px;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span>
              %s</p>
              </div>'; */
            $template = '<dl id="system-message"><dt class="error">Error</dt>
        				 <dd class="error message fade"><ul>
        				 	<li>%s</li>
						 </ul></dd></dl>';

            /* $template = '<div class="ui-state-error ui-corner-all" style="padding: 3pt 0.7em;">
              <span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span>
              %s
              </div>'; */
        } else {
            /* $template = '<div class="ui-state-highlight ui-corner-all" style="padding: 0pt 0.7em;">
              <p style="margin-top:5px"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
              %s</p>
              </div>'; */
            $template = '<div class="ui-state-highlight ui-corner-all" style="padding: 3pt 0.7em;">
                       <span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span> 
                        %s
			           </div>';
        }

        $output .= sprintf($template, $dispMessage);

        return $output;
    }

    /*
     * This function is used get configuration.
     */

    public static function getConfigOption($optionName) {
        $registry = Zend_Registry::getInstance();
        $siteOptions = array();
        if ($registry->isRegistered('siteOptions')) {
            $siteOptions = $registry->siteOptions;
        } else {
            throw new Exception('Key siteOptions is not Registred.');
        }
        return $siteOptions[$optionName];
    }

    /*
     * This function is used set number format
     */

    public static function setNumberFormat($number, $prescision = 2) {
        return number_format($number, $prescision, '.', '');
    }

    /*
     * This function is used set configuration.
     */

    public static function setConfigOptions() {
        $registry = Zend_Registry::getInstance();
        $objCacheManager = new GTL_CacheManager();
        $siteOptions = $objCacheManager->getCacheData('siteOptions');
        /* By Dharmand Andhariya
          if (!$result = $registry->cacheDbObj->load('setOptionsResult') )
          {
          $model = new Model_Siteoptions();
          $options = $model->getTable()->fetchAll();
          $siteOptions = array();
          foreach ($options as $option)
          {
          $siteOptions[$option['opt_key']] = $option['opt_value'];
          }
          $registry->cacheDbObj->save($siteOptions, 'setOptionsResult');
          }
          else
          {
          $siteOptions = $registry->cacheDbObj->load('setOptionsResult');
          }
         */
        $registry->siteOptions = unserialize($siteOptions);
    }

    /*
     * This function is used get category type.
     */

    public static function getCategoryType() {
        return array("0" => "megavendor",
            "1" => "product_suites",
            "2" => "product",
            "3" => "version");
    }

    public static function friendlyURL($string) {

        $string = preg_replace("`\[.*\]`U", "", $string);
        $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i", "\\1", $string);
        $string = preg_replace(array("`[^a-z0-9]`i", "`[-]+`"), "-", $string);

        include_once("specialCharslFunction.php");
        if (mb_detect_encoding($string, array("UTF-8", "ISO-8859-1", "ISO-8859-15") == "UTF-8"))
            $string = utf8_decode($string);

        $string = ReplaceLangSplChar($string);

        return strtolower(trim($string, '-'));
    }

    /**
     * creates folder if not exists recursively by given path
     * @return
     * @param $folder String
     */
    public function createFolder($path) {
        $DS = DIRECTORY_SEPARATOR;
        $folder = $path;
        $folder = explode($DS, $folder);
        $mkfolder = '';
        if (stristr(PHP_OS, 'Win')) {
            //windows plateform
            $mkfolder = '';
        } else {
            $mkfolder = $DS;
        }
        for ($i = 0; isset($folder[$i]); $i++) {
            $mkfolder .= $folder[$i];
            if ($mkfolder != '') {
                if (!is_dir($mkfolder))
                    mkdir("$mkfolder", 0777);
                $mkfolder .= $DS;
            }
        }
    }

    /*
     * This function is used to upload file.
     */

    public static function UploadFile($name, $tmpname, $filename, $path) {
        GTL_Common::createFolder($path);
        if ($name == "") {
            $result = "";
        } else {

            $filename = $name;

            if (move_uploaded_file($tmpname, $path . $filename)) {
                $result = $filename;
            } else {
                $result = "";
            }
        }
        return $result;
    }

    /*
     * This function is used to write log in file.
     */

    public function log($message = "") {
        $fileName = TMP_PATH . "log.txt";
        $fp = fopen($fileName, "a");
        $paramsString = "\n" . date("Y-m-d H:i:s") . ":\n" . $message;
        fwrite($fp, $paramsString);
    }

    /*
     * This function is used to get user's IP address.
     */

    public function getRealIpAddress() {
        $ip = 0;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) { //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
                $tmp = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip = $tmp[0];
            } else {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = 0;
        }
        return $ip;
    }

    /*
     * This function is used to check whether entered url is valid or not.
     */

    function isValidURL($url) {
        return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }

    /*
     * This function is used to check whether entered price is valid or not.
     */

    public function isValidPrice($number) {
        return preg_match('/^[0-9]+(?:\.[0-9]+)?$/im', $number);
    }

    /*
     * This function is used to check whether entered phone number is 
     * valid or not.
     */

    public function isValidPhone($number) {
        return preg_match('/^([0-9\(\)\/\+ \-]*)$/', $number);
    }

    public function isValidemail($email) {
        return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email);
    }

    /**
     * Function to download file
     *
     * This function will download the given file after setting relative header info.
     *
     * @param File $file File name with the actual path.
     * @return viod
     */
    public static function dl_file($file) {

        //First, see if the file exists
        if (!is_file($file)) {
            die(
                    "<div style='text-align:center;'><h1> Sorry, file temporary not available!</h1></div>");
        }

        //Gather relevent info about file
        $len = filesize($file);
        $filename = basename($file);
        $file_extension = strtolower(substr(strrchr($filename, "."), 1));

        //This will set the Content-Type to the appropriate setting for the file
        switch ($file_extension) {
            case "pdf":
                $ctype = "application/pdf";
                break;
            case "exe":
                $ctype = "application/octet-stream";
                break;
            case "zip":
                $ctype = "application/zip";
                break;
            case "doc":
                $ctype = "application/msword";
                break;
            case "xls":
                $ctype = "application/vnd.ms-excel";
                break;
            case "ppt":
                $ctype = "application/vnd.ms-powerpoint";
                break;
            case "gif":
                $ctype = "image/gif";
                break;
            case "png":
                $ctype = "image/png";
                break;
            case "jpeg":
            case "jpg":
                $ctype = "image/jpg";
                break;
            case "mp3":
                $ctype = "audio/mpeg";
                break;
            case "wav":
                $ctype = "audio/x-wav";
                break;
            case "mpeg":
            case "mpg":
            case "mpe":
                $ctype = "video/mpeg";
                break;
            case "mov":
                $ctype = "video/quicktime";
                break;
            case "avi":
                $ctype = "video/x-msvideo";
                break;

            //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
            case "php":
            case "htm":
            case "html":
            case "txt":
                die("<b>Cannot be used for " . $file_extension . " files!</b>");
                break;

            default:
                $ctype = "application/force-download";
        }

        //Begin writing headers
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");

        //Use the switch-generated Content-Type
        header("Content-Type: $ctype");

        //Force the download
        //$header="Content-Disposition: attachment; filename=".$filename.";"; //for force fully download
        $header = "Content-Disposition: inline; filename=" . $filename . ";";
        header($header);
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $len);
        @readfile($file);
        exit;
    }

    public function xmlize($data, $WHITE = 1, $encoding = 'UTF-8') {

        $data = trim($data);
        $vals = $index = $array = array();
        $parser = xml_parser_create($encoding);
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, $WHITE);
        xml_parse_into_struct($parser, $data, $vals, $index);
        xml_parser_free($parser);

        $i = 0;

        $tagname = $vals[$i]['tag'];
        if (isset($vals[$i]['attributes'])) {
            $array[$tagname]['@'] = $vals[$i]['attributes'];
        } else {
            $array[$tagname]['@'] = array();
        }

        $array[$tagname]["#"] = $this->xml_depth($vals, $i);


        return $array;
    }

    /*
     *
     * You don't need to do anything with this function, it's called by
     * xmlize.  It's a recursive function, calling itself as it goes deeper
     * into the xml levels.  If you make any improvements, please let me know.
     *
     *
     */

    public function xml_depth($vals, &$i) {
        $children = array();

        if (isset($vals[$i]['value'])) {
            array_push($children, $vals[$i]['value']);
        }

        while (++$i < count($vals)) {

            switch ($vals[$i]['type']) {

                case 'open':

                    if (isset($vals[$i]['tag'])) {
                        $tagname = $vals[$i]['tag'];
                    } else {
                        $tagname = '';
                    }

                    if (isset($children[$tagname])) {
                        $size = sizeof($children[$tagname]);
                    } else {
                        $size = 0;
                    }

                    if (isset($vals[$i]['attributes'])) {
                        $children[$tagname][$size]['@'] = $vals[$i]["attributes"];
                    }

                    $children[$tagname][$size]['#'] = $this->xml_depth($vals, $i);

                    break;


                case 'cdata':
                    array_push($children, $vals[$i]['value']);
                    break;

                case 'complete':
                    $tagname = $vals[$i]['tag'];

                    if (isset($children[$tagname])) {
                        $size = sizeof($children[$tagname]);
                    } else {
                        $size = 0;
                    }

                    if (isset($vals[$i]['value'])) {
                        $children[$tagname][$size]["#"] = $vals[$i]['value'];
                    } else {
                        $children[$tagname][$size]["#"] = '';
                    }

                    if (isset($vals[$i]['attributes'])) {
                        $children[$tagname][$size]['@']
                                = $vals[$i]['attributes'];
                    }

                    break;

                case 'close':
                    return $children;
                    break;
            }
        }

        return $children;
    }

    /*
     *
     * this helps you understand the structure of the array xmlize() outputs
     *
     * usage:
     * traverse_xmlize($xml, 'xml_');
     * print '<pre>' . implode("", $traverse_array . '</pre>';
     *
     *
     */

    public function traverse_xmlize($array, $arrName = "array", $level = 0) {

        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $this->traverse_xmlize($val, $arrName . "[" . $key . "]", $level + 1);
            } else {
                $GLOBALS['traverse_array'][] = '$' . $arrName . '[' . $key . '] = "' . $val . "\"\n";
            }
        }

        return 1;
    }

    /* takes the input, scrubs bad characters */

    public function generate_seo_link($input, $replace = '-', $remove_words = true, $words_array = array('a', 'and', 'the', 'an', 'it', 'is', 'with', 'can', 'of', 'why', 'not')) {
        //make it lowercase, remove punctuation, remove multiple/leading/ending spaces
        $string = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($input));
        $return = preg_replace('/ +/', ' ', $string);
        $return = trim($return);

        //remove words, if not helpful to seo
        //i like my defaults list in remove_words(), so I wont pass that array
        if ($remove_words) {
            $return = $this->remove_words($return, $replace, $words_array);
        }

        //convert the spaces to whatever the user wants
        //usually a dash or underscore..
        //...then return the value.
        return str_replace(' ', $replace, $return);
    }

    /* takes an input, scrubs unnecessary words */

    public function remove_words($input, $replace, $words_array = array(), $unique_words = true) {
        //separate all words based on spaces
        $input_array = explode(' ', $input);

        //create the return array
        $return = array();

        //loops through words, remove bad words, keep good ones
        foreach ($input_array as $word) {
            //if it's a word we should add...
            if (!in_array($word, $words_array) && ($unique_words ? !in_array($word, $return) : true)) {
                $return[] = $word;
            }
        }

        //return good words separated by dashes
        return implode($replace, $return);
    }

    public function allowedImageFormats() {
        $imageAllowedFormat = Array(
            "image/gif",
            "image/pjpeg",
            "image/jpeg",
            "image/jpg",
            "image/png",
            "image/x-png",
            "image/gif"
        );
        return $imageAllowedFormat;
    }

    public function image_validate($imageAllowedFormat, $image = 'image') {
        if (!$_FILES) {
            return false;
        }

        if (count($_FILES[$image]['name']) == 1) {
            if (!in_array($_FILES[$image]['type'], $imageAllowedFormat)) {
                return 'IMPROPER_FORMAT';
            }
        } else {
            foreach ($_FILES[$image]['name'] as $key => $value) {
                if ($_FILES[$image]['tmp_name'][$key] == '') {
                    return 'UN_UPLOADED';
                }
                if (!in_array($_FILES[$image]['type'][$key], $imageAllowedFormat)) {
                    return 'IMPROPER_FORMAT';
                }
            }
        }
        return 'OK';
    }

    function one_way_send_sms($user, $pass, $sms_from, $sms_to, $sms_msg) {
        $query_string = "api.aspx?apiusername=" . $user . "&apipassword=" . $pass;
        $query_string .= "&senderid=" . rawurlencode($sms_from) . "&mobileno=" . rawurlencode($sms_to);
        $query_string .= "&message=" . rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";
        $url = "http://gateway.onewaysms.com.au:10001/" . $query_string;
        $fd = @implode('', file($url));
        if ($fd) {
            if ($fd > 0) {
                $MT_ID = $fd;
                $RESULT = "SUCCESS";
            } else {
                $MT_ID = "API ON ERROR : " . $fd;
                $RESULT = "FAIL";
            }
        } else {
            // no contact with gateway                      
            $MT_ID = "NO CONTACT WITH GATEWAY ";
            $RESULT = "FAIL";
        }
        $returnArray = Array('MT_ID' => $MT_ID, 'RESULT' => $RESULT);
        return $returnArray;
    }
	
	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
}

}