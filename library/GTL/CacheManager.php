<?php

/*
 * AD
 */
class GTL_CacheManager
{
	protected $physicalCachePath;
	protected $fileName;
	
	public function __construct()
	{
	    $this->physicalCachePath = APP_ROOT . "cache" . DIRECTORY_SEPARATOR;
	}
	
	public function getCacheData($fileName,$param='')
	{
		$physicalFileName = $this->physicalCachePath . $fileName;
	    if(!file_exists($physicalFileName))
	    {
			$this->generateFile($fileName,$param);
	    }
	    return file_get_contents($physicalFileName);
	}
	
	public function generateFile($fileName,$param='')
	{
		$physicalFileName = $this->physicalCachePath . $fileName;
	    $strHtml = "";
	    $registry = Zend_Registry::getInstance();
		switch($fileName)
	    {
			
            case "siteOptions" :
			    //write logic here to write html in file
			    $model = new Model_Siteoptions();
			    $options = $model->fetchAll();
			    $siteOptions = array();
				foreach ($options as $option)
				{
					$siteOptions[$option['opt_key']] = $option['opt_value'];
				}
			    $strHtml = serialize($siteOptions);
			    break;
			
	        default :
	            throw new Exception("Invalid file name :".$fileName);
				break;
	    }
	    file_put_contents($physicalFileName, $strHtml);
	}
	
	public function getFields($type)
	{
	    if(!in_array($type, array('interior', 'exterior', 'safety')))
	    {
	        throw new Exception("Fields for name $type is not available.");
	    }
	    /*NOTE: keys are column names in the car_other_options table and values are mapped to the option attributes in car xml*/
		if('interior' == $type)
		{
		    return array(
						'car_has_electric_windows' => 'elektrische_ramen',
						'car_has_audio_system' => 'radio_cd_speler',
						'car_has_bluetooth' => 'bluetooth',
						'car_has_rear_folding' => 'achterbank_in_delen_neerklapbaar',
						'car_has_driver_seat_height_adjustment' => 'bestuurdersstoel_in_hoogte_verstelbaar',
						'car_has_adjustable_height_of_steering_wheel' => 'in_hoogte_verstelbaar_stuur',
						'car_has_leather_coating' => 'lederen_bekleding',
						'car_has_sports_seats' => 'sportstoelen',
						'car_has_air_conditioning' => 'airconditioning',
						'car_has_electrically_adjustable_side_mirrors' => 'elektrisch_verstelbare_buitenspiegels',
						'car_has_trip_computer' => 'boordcomputer',
						'car_has_armrest' => 'middenarmsteun',
						'car_has_navigation_system' => 'navigatiesysteem',
						'car_has_seat_heating' => 'stoelverwarming'
						);
		}
		if('exterior' == $type)
		{
		    return array(
						'car_has_coupling' => 'trekhaak',
						'car_has_panorama' => 'panoramadak',
						'car_has_sunroof' => 'schuifdak',
						'car_has_alloy_wheels' => 'lichtmetalen_velgen',
						'car_has_heated_mirrors' => 'verwarmde_buitenspiegels',
						'car_has_rails' => 'dakrails',
						'car_has_xenon_lighting' => 'xenon_verlichting'
						);
		}
		if('safety' == $type)
		{
			return array(
						'car_has_power_steering' => 'stuurbekrachtiging',
						'car_has_abs' => 'abs',
						'car_has_airbags' => 'airbags',
						'car_has_cruise_control' => 'cruise_control',
						'car_has_parking_sensor' => 'parkeersensor',
						'car_has_rain_sensor' => 'regensensor',
						'car_has_alarm_system' => 'alarmsysteem',
						'car_has_central_locking' => 'centrale_deurvergrendeling',
						'car_has_tinted_glass' => 'getint_glas',
						'car_has_fog_lights' => 'mistlampen',
						'car_has_traction_control' => 'traction_control'
						);
		}
	}
	
	public function getCarThumbSizes()
	{
		return array(
						"s1" => array('width' => '130', 'height' => '98'),
						"s2" => array('width' => '201', 'height' => '143'),
						"s3" => array('width' => '118', 'height' => '88'),
						"s4" => array('width' => '297', 'height' => '212'),
						"s5" => array('width' => '94', 'height' => '70'),
						"s6" => array('width' => '388', 'height' => '290'),
						"s7" => array('width' => '104', 'height' => '73'),
						"s8" => array('width' => '671', 'height' => '372'),
						);
	}
}
