<?php

class image {
	
	var $im;
	var $width;
	var $height;
	
	function image($fileName = "") {
		if ($fileName != "") {
			return $this->open($fileName);
		}
	}
	
	function open($fileName) {
		unset($this->im);
		$detail = $this->detail($fileName);
		if ($detail) {
			$this->width  = $detail["width"];
			$this->height = $detail["height"];
			
			$imageType = $detail["type"];
			
			if ($imageType == "JPG" || $imageType == "JPEG") {
				$this->im = imagecreatefromjpeg($fileName);
			} elseif ($imageType == "GIF") {
				$this->im = imagecreatefromgif($fileName);
			} elseif ($imageType == "PNG") {
				$this->im = imagecreatefrompng($fileName);
			}
		}
		return $this->im === false ? false : true;
	}
	
	function resize($width, $height) {
		$image_p = imagecreatetruecolor($width, $height);
		$ret = @imagecopyresampled($image_p, $this->im, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
		if ($ret) {
			$this->close();
			$this->im = $image_p;
		}
		return $ret;
	}
	
	function close() {
		@imagedestroy($this->im);
	}
	
	function save($fileName = "", $type = "JPG", $quality = 100) {
		$ret = false;
		if ($fileName == "") {
			ob_start();
		}
		switch($type) {
			default:
				$ret = @imagejpeg($this->im, $fileName, $quality);
			break;
		}
		if ($fileName == "") {
			$ret = ob_get_contents();
			ob_end_clean();
		}
		return $ret;
	}
	
	function detail($fileName) {
		$imageData = @getimagesize($fileName);
		$imageType = $this->_translateImageType($imageData[2]);
		if ($imageType !== null) {
			$tmpData["width"] = $imageData[0];
			$tmpData["height"] = $imageData[1];
			$tmpData["type"] = $imageType;
			return $tmpData;
		}
		return false;
	}

	function _translateImageType($imageCode) {
		$codes = array(
			"1"  => "GIF", "2"  => "JPG", "3"  => "PNG" , "4"  => "SWF",
			"5"  => "PSD", "6"  => "BMP", "7"  => "TIFF", "8"  => "TIFF",
			"9"  => "JPC", "10" => "JP2", "11" => "JPX" , "12" => "JB2",
			"13" => "SWC", "14" => "IFF", "15" => "WBMP", "16" => "XBM"
		);
		return $codes[$imageCode] ? $codes[$imageCode] : false;
	}

}

?>