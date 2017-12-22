<?php
class GdWatermark
{
	protected $parentInstance;
	protected $workingImage;
	protected $newImage;
	protected $fileName;
	protected $position;

	public function watermark($watermarkPath,$watermarkImage,&$that)
	{
		
		$this->parentInstance 		= $that;
		$this->newImage				= $this->parentInstance->getOldImage();
		$this->fileName				= $this->parentInstance->getFileName();

		$validFormats = array('GIF', 'JPG', 'PNG');
		$format	= strtoupper(end(explode(".", $watermarkPath.'/'.$watermarkImage)));

		if (!in_array($format, $validFormats))
		{
			throw new InvalidArgumentException ('Invalid format type specified in save function: ' . $format);
		} else {
			switch($format)
			{
				case 'GIF':
					$watermark = @imagecreatefromgif($watermarkPath.'/'.$watermarkImage);
					break;
				case 'JPG':
					$watermark = @imagecreatefromjpg($watermarkPath.'/'.$watermarkImage);
					break;
				case 'PNG':
					$watermark = @imagecreatefrompng($watermarkPath.'/'.$watermarkImage);
					break;
			}
			$imagewidth = imagesx($this->newImage);
			$imageheight = imagesy($this->newImage);
			$watermarkwidth =  imagesx($watermark);
			$watermarkheight =  imagesy($watermark);
			list($startwidth,$startheight)=	$this->watermarkPosition($imagewidth,$imageheight,$watermarkwidth,$watermarkheight);
			imagecopy($this->newImage, $watermark,  $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);
			$mainImageFormate = strtoupper(	$this->parentInstance->getFormat());
			switch($mainImageFormate)
			{
				case 'GIF':
					imagegif($this->newImage,$this->fileName);
					break;
				case 'JPG':
					imagejpeg($this->newImage,$this->fileName);
					break;
				case 'PNG':
					imagepng($this->newImage,$this->fileName);
					break;
			}
		}

		return $this;
	}
	
	public function setPosition($p)
	{
		$this->position = $p;
	}
	
	private function watermarkPosition($target_width,$target_height,$watermark_width,$watermark_height)
	{
		switch($this->position)
		{
			case WATERMARK_TOP_LEFT :
				$watermark_x = 1;
				$watermark_y = 1;
				break;
			
			// top center, north
			//
			case WATERMARK_TOP_CENTER :
				$watermark_x = ($target_width - $watermark_width)/2;
				$watermark_y = 1;
				break;
			
			// top right, north east
			//
			case WATERMARK_TOP_RIGHT :
				$watermark_x = $target_width - $watermark_width ;
				$watermark_y = 1;
				break;
			
			// middle left, west
			//
			case WATERMARK_MIDDLE_LEFT :
				$watermark_x = 1;
				$watermark_y = ($target_height - $watermark_height)/2;
				break;
			
			// middle center, center
			//
			case WATERMARK_MIDDLE_CENTER :
				$watermark_x = ($target_width - $watermark_width)/2;
				$watermark_y = ($target_height - $watermark_height)/2;
				break;
			
			// middle right, east
			//
			case WATERMARK_MIDDLE_RIGHT :

				$watermark_x = $target_width - $watermark_width ;
				$watermark_y = ($target_height - $watermark_height)/2;
				break;
			
			// bottom left, south west
			//
			case WATERMARK_BOTTOM_LEFT :
				$watermark_x = 1;
				$watermark_y = $target_height - $watermark_height ;
				break;
			
			// bottom center, south
			//
			case WATERMARK_BOTTOM_CENTER :
				$watermark_x = ($target_width - $watermark_width)/2;
				$watermark_y = $target_height - $watermark_height ;
				break;
			
			
			// bottom right, south east
			//
			case WATERMARK_BOTTOM_RIGHT :
				$watermark_x = $target_width - $watermark_width ;
				$watermark_y = $target_height - $watermark_height ;
				break;
			default:	
				$watermark_x = $target_width - $watermark_width ;
				$watermark_y = $target_height - $watermark_height ;
				break;

		}
		return array($watermark_x,$watermark_y);
	}
}
$pt = PhpThumb::getInstance();
$pt->registerPlugin('GdWatermark', 'gd');
?>
