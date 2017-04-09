<?php
class Helpers {
	
	public static function getThumbnailSrc($sImgPath, $iDesiredWidth) {
		
		/* Variables */
		$sCacheDir = "images/cache/";
		$sFileExtension = pathinfo ( $sImgPath, PATHINFO_EXTENSION );
		$sCacheName = hash ( "md5", $sImgPath . $iDesiredWidth );
		$sImgPath = iconv ( "utf-8", "tis-620", $sImgPath );
		
		/* Check if cache has already been created */
		if (file_exists ( $sCacheDir . $sCacheName . "." . $sFileExtension ))
			return $sCacheDir . $sCacheName . "." . $sFileExtension;
			
		/* Check if sImgPath is not existed */
		if (! file_exists ( $sImgPath ))
			return null;
			
		/* Check if it is a GIF (return the original because this function cannot be used with GIF) */
		if (strtolower ( $sFileExtension ) == "gif")
			return $sImgPath;
			
		/* Check if it is not a picture */
		if (strtolower ( $sFileExtension ) != "jpg" && strtolower ( $sFileExtension ) != "png")
			return null;
			
		/* Create dir if cache folder is not existed */
		if (! file_exists ( $sCacheDir ))
			mkdir ( $sCacheDir );
			
		/* Create cache */
		if (strtolower ( $sFileExtension ) == "jpg")
			$source_image = imagecreatefromjpeg ( $sImgPath );
		else
			$source_image = imagecreatefrompng ( $sImgPath );
		
		$iImgwidth = imagesx ( $source_image );
		$iImgheight = imagesy ( $source_image );
		$iDesiredHeight = floor ( $iImgheight * ($iDesiredWidth / $iImgwidth) );
		$virtual_image = imagecreatetruecolor ( $iDesiredWidth, $iDesiredHeight );
		imagealphablending ( $virtual_image, false );
		imagesavealpha ( $virtual_image, true );
		$trans_layer_overlay = imagecolorallocatealpha ( $virtual_image, 220, 220, 220, 127 );
		imagefill ( $virtual_image, 0, 0, $trans_layer_overlay );
		imagecopyresampled ( $virtual_image, $source_image, 0, 0, 0, 0, $iDesiredWidth, $iDesiredHeight, $iImgwidth, $iImgheight );
		
		if (strtolower ( $sFileExtension ) == "jpg")
			imagejpeg ( $virtual_image, $sCacheDir . $sCacheName . "." . $sFileExtension );
		else
			imagepng ( $virtual_image, $sCacheDir . $sCacheName . "." . $sFileExtension );
		
		return $sCacheDir . $sCacheName . "." . $sFileExtension;
	}
	
}