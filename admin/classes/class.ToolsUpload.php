<?php
/* wscms/classes/class.ToolsUpload.php v.3.5.4. 09/05/2019 */

class ToolsUpload extends Core {	
	static $filename;
	static $filenameWithId;
	static $filenameWithTime ;
	static $filenameMd5;
	static $orgFilename;
	static $tempFilename;
	static $fileExtension;
	static $fileExtension1;
	static $fileSize;
	static $imageSize;
	static $fileType ;
	static $fieldPostImage;
	static $filenameFormat;
	
	public function __construct(){
		parent::__construct();
		self::$filename = '';
		self::$filenameWithId = '';
		self::$filenameWithTime = '';
		self::$filenameMd5 = '';
		self::$orgFilename = '';
		self::$tempFilename = '';
		self::$fileExtension = '';
		self::$fileExtension1 = '';
		self::$fileSize = '';
		self::$fileType = '';
		self::$fieldPostImage = 'filename';
		self::$filenameFormat = array();
		}
	
	public static function getFilenameFromForm($id=0) {
		if (isset($_FILES[self::$fieldPostImage])) {		
			$FILES = $_FILES[self::$fieldPostImage];
			if ($FILES['error'] == 0) {
				self::$tempFilename = SanitizeStrings::stripMagic($FILES['tmp_name']);
				self::$filename = (isset($FILES['name']) && $FILES['name'] != '') ? SanitizeStrings::stripMagic($FILES['name']) : '';
				self::$orgFilename = self::$filename;  		
				self::$filename = str_replace(" ", "",strip_tags(trim(self::$filename)));
				self::$fileExtension = strtolower(substr(strrchr(self::$filename ,"."),1));	
				
				if (strnatcmp(phpversion(),'5.3.6') >= 0) {
					# equal or newer
					$info = new SplFileInfo(self::$filename);
					self::$fileExtension1 = $info->getExtension();
					} else {
	        			self::$fileExtension1 = strtolower(substr(strrchr(self::$filename ,"."),1));	
	    				} 
				
				/* filename options */
				self::$filenameWithId = $id.'-'.self::$filename;
				self::$filenameWithTime = time().self::$filename;
				self::$filenameMd5 = md5(self::$filenameWithTime).".".self::$fileExtension;			
				self::$fileType = $FILES['type'];	
				self::$fileSize = $FILES['size'];
				
				/* trova dimensione immagine */
				if (getimagesize(self::$tempFilename)) {
					$imagesize = getimagesize(self::$tempFilename);
					list($width, $height, $type, $attr) = $imagesize;
					self::$imageSize = $width.'x'.$height;
					} else {
						self::$imageSize = '';
						}
		
				/* controlli */
				/* tipo file */				
				if (count(self::$filenameFormat) > 0) {
					//echo self::$fileExtension1;
					//print_r(self::$filenameFormat);
					
					if (!in_array(strtoupper(self::$fileExtension1),self::$filenameFormat)) {
						self::clearAll();
						self::$resultOp->error =  1;
						self::$resultOp->message =  'Errore formato file! Formati ammessi: '.implode(', ',self::$filenameFormat);
						}	
					} 				
	
				} else {
					self::clearAll();
					//Core::$resultOp->errors->type =  1;
					//Core::$resultOp->errors->message = 'Errore lettura file!';
					//Core::$resultOp->error = 1;
					//Core::$resultOp->message = 'Errore lettura file!';
					}
			} else {
					self::clearAll();
					}		
		}

	public static function readFile($file,$orgfile,$ctype) {
		if (file_exists($file)) {
		   header('Pragma: public');
		   header('Expires: 0');
		   header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		   header('Cache-Control: private',false);
		   header('Content-Type: '.$ctype);
		   header('Content-Disposition: attachment; filename="'.$orgfile.'";');
		   header('Content-Transfer-Encoding: binary');
		   header('Content-Length: '.@filesize($file));
		   if (!ini_get('safe_mode')) set_time_limit(0);
		   readfile($file);
	   	} else {
	   		Core::$resultOp->error = 1;
				Core::$resultOp->message = 'Errore lettura file!';
	   		}
		}
		
	public static function create_zip($files = array(),$destination = '',$overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[] = $file;
					}
				}
			}
		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
				}
			//add the files
			foreach($valid_files as $file) {
				$zip->addFile($file,$file);
				}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
			//close the zip -- done!
			$zip->close();		
			//check to make sure the file exists
			return file_exists($destination);
			} else {
				return false;
				}
		}
		
	/**
	* Delete a file or recursively delete a directory
	*
	* @param string $str Path to file or directory
	*/
	public static function recursiveDelete($str) {
		if (is_file($str)) {
        return @unlink($str);
   		} elseif (is_dir($str)) {
				$scan = glob(rtrim($str,'/').'/*');
        		foreach($scan as $index=>$path) {
           		self::recursiveDelete($path);
        			}
				return @rmdir($str);
    			}
		}

 	/* imposta i parametri */
 	
 	public static function setFilenameFormat($value){
 		self::$filenameFormat = $value;
		}	

 	
 	/* */
 	
 	public static function checkFilenameFormat($value = array()){
		return self::$filenameFormat = $value;
		}	
	
	public static function clearAll($value = array()){	
		self::$tempFilename ='';
		self::$filename = '';
		self::$orgFilename = '';  		
		self::$filename = '';
		self::$fileExtension = '';
		self::$filenameWithId = '';
		self::$filenameWithTime = '';
		self::$filenameMd5 = '';			
		self::$fileType = '';	
		self::$fileSize = '';
		self::$imageSize = '';
		}	

	public static function getFilename(){
		return self::$filename;
		}	
		
	public static function getFilenameWithId(){
		return self::$filenameWithId;
		}
	
	public static function getFilenameWithTime(){
		return self::$filenameWithTime;
		}
		
	public static function getFilenameMd5(){
		return self::$filenameMd5;
		}

	public static function getTempFilename(){
		return self::$tempFilename;
		}	
	
	public static function getOrgFilename(){
		return self::$orgFilename;
		}	
		
	public static function getFileExtension(){
		return self::$fileExtension;
		}	
		
	public static function getFileSize(){
		return self::$fileSize;
		}
		
	public static function getImageSize(){
		return self::$imageSize;
		}
	
	public static function getFileType(){
		return self::$fileType;
		}
		
	public static function setFieldPostImage($value) {
		self::$fieldPostImage = $value;
		}	
	}
?>