<?php
class CImage {

	/**
	 * Memebers
	 */
	private $verboseMessage = null;
	private $maxWidth = 2000;
	private $maxHeight = 2000; 
	private $mime = null;
	private $imgInfo = null;
	
	private $src = null;
	private $verbose    = null;
	private $saveAs     = null;
	private $quality    = null;
	private $ignoreCache = null;
	private $newWidth   = null;
	private $newHeight  = null;
	private $cropToFit  = null;
	private $sharpen    = null;
	private $pathToImage = null;
	private $cacheFileName = null;
	private $fileExtension = null;
	private $width = null;
	private $height = null;
	private $type = null;
	private $attr = null;
	private $filesize = null;
	
	/**
	 * Constructor 
	 *
	 * @param 
	 *
	 */
	public function __construct() {
		
		//define('IMG_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR);
		define('IMG_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR);
		define('CACHE_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);
		echo "I konstruktorn";
		
		//
		// Get the incoming arguments
		//
		$this->src        = isset($_GET['src'])     ? $_GET['src']      : null;
		$this->verbose    = isset($_GET['verbose']) ? true              : null;
		$this->saveAs     = isset($_GET['save-as']) ? $_GET['save-as']  : null;
		$this->quality    = isset($_GET['quality']) ? $_GET['quality']  : 60;
		$this->ignoreCache = isset($_GET['no-cache']) ? true           : null;
		$this->newWidth   = isset($_GET['width'])   ? $_GET['width']    : null;
		$this->newHeight  = isset($_GET['height'])  ? $_GET['height']   : null;
		$this->cropToFit  = isset($_GET['crop-to-fit']) ? true : null;
		$this->sharpen    = isset($_GET['sharpen']) ? true : null;

		//$this->pathToImage = realpath(IMG_PATH . $this->src);
		$this->pathToImage = realpath(IMG_PATH . $this->src);
		
		is_dir(IMG_PATH) or $this->errorMessage('The image dir is not a valid directory.');
		is_writable(CACHE_PATH) or $this->errorMessage('The cache dir is not a writable directory.');
		isset($this->src) or $this->errorMessage('Must set src-attribute.');
		preg_match('#^[a-z0-9A-Z-_\.\/]+$#', $this->src) or $this->errorMessage('Filename contains invalid characters.');
		substr_compare(realpath(IMG_PATH), $this->pathToImage, 0, strlen(realpath(IMG_PATH))) == 0 or $this->errorMessage('Security constraint: Source image is not directly below the directory IMG_PATH.');
		is_null($this->saveAs) or in_array($this->saveAs, array('png', 'jpg', 'jpeg')) or $this->errorMessage('Not a valid extension to save image as');
		is_null($this->quality) or (is_numeric($this->quality) and $this->quality > 0 and $this->quality <= 100) or $this->errorMessage('Quality out of range');
		is_null($this->newWidth) or (is_numeric($this->newWidth) and $this->newWidth > 0 and $this->newWidth <= $this->maxWidth) or $this->errorMessage('Width out of range');
		is_null($this->newHeight) or (is_numeric($this->newHeight) and $this->newHeight > 0 and $this->newHeight <= $this->maxHeight) or $this->errorMessage('Height out of range');
		is_null($this->cropToFit) or ($this->cropToFit and $this->newWidth and $this->newHeight) or $this->errorMessage('Crop to fit needs both width and height to work');

		//
		// Start displaying log if verbose mode & create url to current image
		//
		if($this->verbose) {
		  $query = array();
		  parse_str($_SERVER['QUERY_STRING'], $query);
		  unset($query['verbose']);
		  $url = '?' . http_build_query($query);


		echo <<<EOD
		<html lang='en'>
		<meta charset='UTF-8'/>
		<title>img.php verbose mode</title>
		<h1>Verbose mode</h1>
		<p><a href=$url><code>$url</code></a><br>
		<img src='{$url}' /></p>
EOD;
		}
		
		$this->GetImageInfo();
		$this->CalculateWidthHeight();
		$this->cacheFileName = $this->GetCacheFilename();
		if($this->cacheFileName != null) {
			//If valid cache file exist
			$this->outputImage($this->cacheFileName, $this->verbose);
		} else {
			//No valid cache file, creates cache file
			$this->cacheFileName = $this->CreateCacheFile();
			$this->outputImage($this->cacheFileName, $this->verbose);
		}
		
		
		//$file = __DIR__.'/img/dragon.jpg';
		//header('Content-type: image/jpeg');
		//$this->outputImage($file, false);
	}
	
	
	//
	// Check if cachefile exist
	//
	function GetCacheFilename() {
		$parts          = pathinfo($this->pathToImage);
		$this->fileExtension  = $parts['extension'];
		$this->saveAs         = is_null($this->saveAs) ? $this->fileExtension : $this->saveAs;
		$quality_       = is_null($this->quality) ? null : "_q{$this->quality}";
		$cropToFit_     = is_null($this->cropToFit) ? null : "_cf";
		$sharpen_       = is_null($this->sharpen) ? null : "_s";
		$dirName        = preg_replace('/\//', '-', dirname($this->src));
		$cacheFileName = CACHE_PATH . "-{$dirName}-{$parts['filename']}_{$this->newWidth}_{$this->newHeight}{$quality_}{$cropToFit_}{$sharpen_}.{$this->saveAs}";
		$cacheFileName = preg_replace('/^a-zA-Z0-9\.-_/', '', $cacheFileName);

		if($this->verbose) { $this->verbose("Cache file is: {$cacheFileName}"); }


		//
		// Is there already a valid image in the cache directory, then use it and exit
		//
		$imageModifiedTime = filemtime($this->pathToImage);
		$cacheModifiedTime = is_file($cacheFileName) ? filemtime($cacheFileName) : null;

		// If cached image is valid, output it.
		if(!$this->ignoreCache && is_file($cacheFileName) && $imageModifiedTime < $cacheModifiedTime) {
		  if($this->verbose) { $this->verbose("Cache file is valid, output it."); }
		  $this->outputImage($cacheFileName, $this->verbose);
		  return $cacheFileName;
		  exit;
		//If no vaild cache file return
		} else {
			if($this->verbose) { $this->verbose("Cache is not valid, process image and create a cached version of it."); }
			$cacheFileName = null;
			return $cacheFileName;
			exit;
		}
	}

		
	function CreateCacheFile() {
		//
		// Open up the original image from file
		//
		if($this->verbose) { $this->verboseMessage .= $this->verbose("File extension is: {$this->fileExtension}"); }

		switch($this->fileExtension) {  
		  case 'jpg':
		  case 'jpeg': 
			$image = imagecreatefromjpeg($this->pathToImage);
			if($this->verbose) { $this->verboseMessage .= $this->verbose("Opened the image as a JPEG image."); }
			break;  
		  
		  case 'png':  
			$image = imagecreatefrompng($pathToImage); 
			if($this->verbose) { $this->verboseMessage .= $this->verbose("Opened the image as a PNG image."); }
			break;  

		  default: errorPage('No support for this file extension.');
		}

		//
		// Resize the image if needed
		//
		if($this->cropToFit) {
		  if($this->verbose) { $this->verboseMessage .= $this->verbose("Resizing, crop to fit."); }
		  $cropX = round(($this->width - $cropWidth) / 2);  
		  $cropY = round(($this->height - $cropHeight) / 2);    
		  $imageResized = imagecreatetruecolor($this->newWidth, $this->newHeight);
		  imagecopyresampled($imageResized, $image, 0, 0, $cropX, $cropY, $this->newWidth, $this->newHeight, $cropWidth, $cropHeight);
		  $image = $imageResized;
		  $this->width = $this->newWidth;
		  $this->height = $this->newHeight;
		}
		else if(!($this->newWidth == $this->width && $this->newHeight == $this->height)) {
		  if($this->verbose) { $this->verboseMessage .= $this->verbose("Resizing, new height and/or width."); }
		  $imageResized = imagecreatetruecolor($this->newWidth, $this->newHeight);
		  imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $this->width, $this->height);
		  $image  = $imageResized;
		  $this->width  = $this->newWidth;
		  $this->height = $this->newHeight;
		}

		//
		// Apply filters and postprocessing of image
		//
		if($this->sharpen) {
		  $image = sharpenImage($image);
		}

		//
		// Save the image
		//
		$parts          = pathinfo($this->pathToImage);
		$this->fileExtension  = $parts['extension'];
		$this->saveAs         = is_null($this->saveAs) ? $this->fileExtension : $this->saveAs;
		$quality_       = is_null($this->quality) ? null : "_q{$this->quality}";
		$cropToFit_     = is_null($this->cropToFit) ? null : "_cf";
		$sharpen_       = is_null($this->sharpen) ? null : "_s";
		$dirName        = preg_replace('/\//', '-', dirname($this->src));
		$cacheFileName = CACHE_PATH . "-{$dirName}-{$parts['filename']}_{$this->newWidth}_{$this->newHeight}{$quality_}{$cropToFit_}{$sharpen_}.{$this->saveAs}";
		$cacheFileName = preg_replace('/^a-zA-Z0-9\.-_/', '', $cacheFileName);
		
		switch($this->saveAs) {
		  case 'jpeg':
		  case 'jpg':
			if($this->verbose) { $this->verboseMessage .= $this->verbose("Saving image as JPEG to cache using quality = {$this->quality}."); }
			imagejpeg($image, $cacheFileName, $this->quality);
		  break;  

		  case 'png':  
			if($this->verbose) { $this->verboseMessage .= $this->verbose("Saving image as PNG to cache."); }
			imagepng($image, $cacheFileName);  
		  break;  

		  default:
			$this->errorMessage('No support to save as this file extension.');
		  break;
		}

		if($this->verbose) { 
		  clearstatcache();
		  $cacheFilesize = filesize($cacheFileName);
		  $this->verboseMessage .= $this->verbose("File size of cached file: {$cacheFilesize} bytes."); 
		  $this->verboseMessage .= $this->verbose("Cache file has a file size of " . round($cacheFilesize/$this->filesize*100) . "% of the original size.");
		}
		return $cacheFileName;	
	}

	
	//
	// Get information on the image
	//
	function GetImageInfo() {
		$this->imgInfo = list($this->width, $this->height, $this->type, $this->attr) = getimagesize($this->pathToImage);
		!empty($this->imgInfo) or $this->errorMessage("The file doesn't seem to be an image.");
		$this->mime = $this->imgInfo['mime'];

		if($this->verbose) {
		  $this->filesize = filesize($this->pathToImage);
		  $this->verboseMessage .= $this->verbose("Image file: {$this->pathToImage}");
		  $this->verboseMessage .= $this->verbose("Image information: " . print_r($this->imgInfo, true));
		  $this->verboseMessage .= $this->verbose("Image width x height (type): {$this->width} x {$this->height} ({$this->type}).");
		  $this->verboseMessage .= $this->verbose("Image file size: {$this->filesize} bytes.");
		  $this->verboseMessage .= $this->verbose("Image mime type: {$this->mime}.");
		}
		return $this->verboseMessage;
	}


	/**
	 * Display error message.
	 *
	 * @param string $message the error message to display.
	 */
	function errorMessage($message) {
	  header("Status: 404 Not Found");
	  die('img.php says 404 - ' . htmlentities($message));
	}
	
	
	/**
	 * Display log message.
	 *
	 * @param string $message the log message to display.
	 */
	function verbose($message) {
	  echo "<p>" . htmlentities($message) . "</p>";
	}
	
	
	/**
	 * Output an image together with last modified header.
	 *
	 * @param string $file as path to the image.
	 * @param boolean $verbose if verbose mode is on or off.
	 */
	function outputImage($file, $verbose) {
	  $info = getimagesize($file);
	  !empty($info) or $this->errorMessage("The file doesn't seem to be an image.");
	  $mime   = $info['mime'];

	  $lastModified = filemtime($file);  
	  $gmdate = gmdate("D, d M Y H:i:s", $lastModified);
	  if($verbose) {
		$this->verbose("Memory peak: " . round(memory_get_peak_usage() /1024/1024) . "M");
		$this->verbose("Memory limit: " . ini_get('memory_limit'));
		$this->verbose("Time is {$gmdate} GMT.");
	  }
	  if(!$verbose) header('Last-Modified: ' . $gmdate . ' GMT');
	  if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified){
		if($verbose) { $this->verbose("Would send header 304 Not Modified, but its verbose mode."); exit; }
		header('HTTP/1.0 304 Not Modified');
	  } else {  
		if($verbose) { 
			$this->verbose("Would send header to deliver image with modified time: {$gmdate} GMT, but its verbose mode."); 			
			exit; 
		}
		header('Content-type: ' . $mime);  
		readfile($file);
	  }
	  exit;
	}
	
	/**
	 * Sharpen image as http://php.net/manual/en/ref.image.php#56144
	 * http://loriweb.pair.com/8udf-sharpen.html
	 *
	 * @param resource $image the image to apply this filter on.
	 * @return resource $image as the processed image.
	 */
	function sharpenImage($image) {
	  $matrix = array(
		array(-1,-1,-1,),
		array(-1,16,-1,),
		array(-1,-1,-1,)
	  );
	  $divisor = 8;
	  $offset = 0;
	  imageconvolution($image, $matrix, $divisor, $offset);
	  return $image;
	}
	
	
	//
	// Calculate new width and height for the image
	//
	function CalculateWidthHeight() {
		$aspectRatio = $this->width / $this->height;

		if($this->cropToFit && $this->newWidth && $this->newHeight) {
		  $targetRatio = $this->newWidth / $this->newHeight;
		  $cropWidth   = $targetRatio > $aspectRatio ? $this->width : round($this->height * $targetRatio);
		  $cropHeight  = $targetRatio > $aspectRatio ? round($this->width  / $targetRatio) : $this->height;
		  if($this->verbose) { $this->verbose("Crop to fit into box of {$this->newWidth}x{$this->newHeight}. Cropping dimensions: {$cropWidth}x{$cropHeight}."); }
		}
		else if($this->newWidth && !$this->newHeight) {
		  $this->newHeight = round($this->newWidth / $aspectRatio);
		  if($this->verbose) { $this->verbose("New width is known {$this->newWidth}, height is calculated to {$this->newHeight}."); }
		}
		else if(!$this->newWidth && $this->newHeight) {
		  $newWidth = round($this->newHeight * $aspectRatio);
		  if($this->verbose) { $this->verbose("New height is known {$this->newHeight}, width is calculated to {$this->newWidth}."); }
		}
		else if($this->newWidth && $this->newHeight) {
		  $ratioWidth  = $this->width  / $this->newWidth;
		  $ratioHeight = $this->height / $this->newHeight;
		  $ratio = ($ratioWidth > $ratioHeight) ? $ratioWidth : $ratioHeight;
		  $this->newWidth  = round($this->width  / $ratio);
		  $this->newHeight = round($this->height / $ratio);
		  if($this->verbose) { $this->verbose("New width & height is requested, keeping aspect ratio results in {$this->newWidth}x{$this->newHeight}."); }
		}
		else {
		  $this->newWidth = $this->width;
		  $this->newHeight = $this->height;
		  if($this->verbose) { $this->verbose("Keeping original width & heigth."); }
		}
	}

}