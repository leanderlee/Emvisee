<?php
#################################################################################
#       Class GD2                                                               #
#       Development By Mustafa Yontar											#
#       Modified By Leander Lee                                                 #
#		GPL Licanse                                                             #
#       http://www.e4z.net - ra@e4z.net                                         #
#################################################################################

class gd2 {
	
	var $bgcolor_red = 255;
	var $bgcolor_green = 255;
	var $bgcolor_blue = 255;
	var $quality=100;
	
	function is_image($file) {
        $format = string::after(".", $file, false);
        switch ($format) {
            case "gif":
                $image_type = true;
                break;
            case "jpeg":
                $image_type = true;
                break;
            case "png":
                $image_type = true;
                break;
            default:
                $image_type = false;
                break;
        }
        return $image_type;
	}
	
	function create_image($file) {
		$extension = file_tGetFormat($file);
		switch($extension) {
            case "gif":
                $image = imagecreatefromgif($file);
                break;
            case "jpg":
            case "jpeg":
                $image = imagecreatefromjpeg($file);
                break;
            case "png":
                $image = imagecreatefrompng($file);
                break;
		}
		
		return $image;
	}
	
    function save_as($file,$im,$saveas='',$savefile='') {
		if ($saveas == '') {	   
			$type = file_tGetFormat($file);
		}
        else {
			$type = $saveas;
		}
		switch ($type) {
            case "gif":
                if ($savefile == '') {
                    header('Content-type: image/gif');
                    imagegif($im);
                }
                else {
                    imagegif($im,$savefile);
                }
                break;
            case "jpg":
            case "jpeg":
                if ($savefile == '') {
                    header('Content-type: image/jpeg');
                    imagejpeg($im);
                }
                else {
                    imagejpeg($im,self::quality,$savefile);
                }
                break;
            
            case "png":
                if ($savefile == '') {
                    header('Content-type: image/png');
                }
                imagepng($im,$savefile);
            break;
		}
		return true;
	}
    
    function size($filename) {
        return getimagesize($filename);
    }
	
	function resize($filename,$new_width,$new_height,$x,$y,$size=0) {
        list($width, $height) = getimagesize($filename);
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = file_tCreateImageX($filename);
        imagecopyresampled($image_p, $image, 0, 0,0,0, $new_width, $new_height, $width, $height);
        if($size == 0) {
            return $image_p;
        }
        else {
            $image2 = imagecreatetruecolor($size, $size);
            $bg = imagecolorallocate($image2, self::bgcolor_red, self::bgcolor_green, self::bgcolor_blue);
            imagefill($image2, 0, 0, $bg);
            imagecopymerge($image2,$image_p,$x,$y,0,0,$size,$size,100);
            imagefill($image2, $x, $y, $bg);
            return $image2;
        }
	}
	
    function crop($filename,$width,$height,$x,$y,$savefile='') {
        if (self::is_image($file)) {
            $image = file_tCreateImageX($filename);
            $image2 = imagecreatetruecolor($width, $height);
            imagecopymerge($image2,$image,0,0,$x,$y,$width,$height,100);
            if($savefile != '') {
                self::save_as($file, $image2,'',$savefile);
            }
            else {
                self::save_as($file, $image2);
            }
            return $image2;
        }
        else {
            return array('error' => 'Unsupported Image File');
        }
	}
	
	function effect_negate($file,$savefile='') {
        if(self::is_image($file)) {
            $img=self::create_image($file);
            imagefilter($img, IMG_FILTER_NEGATE);
            if($savefile != '') {
                self::save_as($file,$img,'',$savefile);
            } else {
                self::save_as($file,$img,'',$savefile);
            }
        }
	}
	
	function effect_grayscale($file,$savefile='') {
        if(self::is_image($file)) {
            $img=self::create_image($file);
            imagefilter($img, IMG_FILTER_GRAYSCALE);
            if($savefile != '') {
                self::save_as($file,$img,'',$savefile);
            } else {
                self::save_as($file,$img,'',$savefile);
            }
        }	
	}
	
	function effect_edge($file,$savefile='') {
        if(self::is_image($file)) {
            $img=self::create_image($file);
            imagefilter($img, IMG_FILTER_EDGEDETECT);
            if($savefile != '') {
                self::save_as($file,$img,'',$savefile);
            } else {
                self::save_as($file,$img,'',$savefile);
            }
        }
	}
	
	function effect_selective_blur($file,$savefile='') {
        if(self::is_image($file)) {
            $img=self::create_image($file);
            imagefilter($img, IMG_FILTER_SELECTIVE_BLUR);
            if($savefile != '') {
                self::save_as($file,$img,'',$savefile);
            } else {
                self::save_as($file,$img,'',$savefile);
            }
        }
	}
	
    function effect_contrast($file,$val,$savefile='') {
        if(self::is_image($file)) {
            $img=self::create_image($file);
            imagefilter($img, IMG_FILTER_CONTRAST,$val);
            if($savefile != '') {
                self::save_as($file,$img,'',$savefile);
            } else {
                self::save_as($file,$img,'',$savefile);
            }
        }
	}
	
    function effect_brightness($file,$val,$savefile='') {
        if(self::is_image($file)) {
            $img=self::create_image($file);
            imagefilter($img, IMG_FILTER_BRIGHTNESS,$val);
            if($savefile != '') {
                self::save_as($file,$img,'',$savefile);
            } else {
                self::save_as($file,$img,'',$savefile);
            }
        }
	}
	
	function effect_blur($file,$val,$savefile='') {
        if(self::is_image($file)) {
            $img=self::create_image($file);
            imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR,$val);
            if($savefile != '') {
                self::save_as($file,$img,'',$savefile);
            } else {
                self::save_as($file,$img,'',$savefile);
            }
        }
	}
	
	function effect_smooth($file, $val, $savefile='') {
        if(self::is_image($file)) {
            $img=self::create_image($file);
            imagefilter($img, IMG_FILTER_SMOOTH,$val);
            if($savefile != '') {
                self::save_as($file,$img,'',$savefile);
            } else {
                self::save_as($file,$img,'',$savefile);
            }
        }
	}
	
	function effect_emboss($file,$savefile='') {
        if(self::is_image($file)) {
            $img=self::create_image($file);
            imagefilter($img, IMG_FILTER_EMBOSS);
            if($savefile != '') {
                self::save_as($file,$img,'',$savefile);
            } else {
                self::save_as($file,$img,'',$savefile);
            }
        }
	}
	
	function effect_mean_remove($file,$savefile='') {
        if(self::is_image($file)) {
            $img=self::create_image($file);
            imagefilter($img, IMG_FILTER_MEAN_REMOVAL);
            if($savefile != '') {
                self::save_as($file,$img,'',$savefile);
            } else {
                self::save_as($file,$img,'',$savefile);
            }
        }
	}
	
	function rotate($file,$degrees,$savefile='') {
		if(self::is_image($file)) {
			$img=self::create_image($file);
			$rotate = imagerotate($img, $degrees, 0);
			if($savefile != '') {
				self::save_as($file,$rotate,'',$savefile);
			} else {
				self::save_as($file,$rotate,'',$savefile);
			}
		}
	
	}
		
	function opacity($file,$hexcolor,$savefile='') {
			if(self::is_image($file)) {
				$rgb = self::Hex2Rgb($hexcolor);
				$img=self::create_image($file);
				$trans = imagecolorallocate($img,$rgb[0],$rgb[1],$rgb[2]);
   				imagecolortransparent($img,$trans);
				if($savefile != '') {
					self::save_as($file,$img,"gif",$savefile);
				} else {
					self::save_as($file,$img,"gif",$savefile);
				}
			}
		
		}
		
	function Hex2Rgb($hex) {
        if (0 === strpos($hex, '#')) {
            $hex = substr($hex, 1);
        }
        else if (0 === strpos($hex, '&H')) {
            $hex = substr($hex, 2);
        }
        else if (0 === strpos($hex, 'x')) {
            $hex = substr($hex, 2);
        }
        
        $cutpoint = ceil(strlen($hex)/2)-1;
        $rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);
        $rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
        $rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
        $rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);
        return $rgb;
    }
	
}

//$gd = new GD2;
//$gd->ImageRotate("DSC_0096.jpg",-90);
//$gd->effect_mean_remove("DSC_0096.jpg");
//$gd->effect_blur("DSC_0096.jpg",20);
//$gd->effect_emboss("DSC_0096.jpg");
//$gd->effect_smooth("DSC_0096.jpg",20);
//$gd->effect_brightness("DSC_0096.jpg",20);
//$gd->effect_contrast("DSC_0096.jpg",20);
//$gd->effect_selective_blur("DSC_0096.jpg");
//$gd->effect_edge("DSC_0096.jpg");
//$gd->effect_grayscale("DSC_0096.jpg");
//$gd->effect_negate("DSC_0096.jpg");
//$gd->MaxSizeThumbnail("DSC_0096.jpg",250);
//$gd->OneSizeThumbnail($_GET['Img'],$_GET['Size']);
//$gd->CropImage("DSC_0096.jpg",250,250,100,70);

//$gd->opacity("DSC_0096.jpg","#000000");



?>