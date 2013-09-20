<?php
$files = array('dummy1.css','dummy2.css');
$css = new cssCheck($files);
echo "<pre>";
print_r($css->checkDouble());


/*
* @autor Markus Schuer
* @mail markus.schuer@googlemail.com
* @param filres array (CSS File list)
* TODO: 
* - errorhandling
* - cleanup code
* - more functions
*/

class cssCheck
{
	var $fileData = array();
	public function __construct($files)
	{
		$this->files = $files;
		$this->loadFiles();
		$this->cleanData();
		$this->seperateData();
	}
	/*
	* load all CSS filedata in an array
	*
	*
	*/
	private function loadFiles()
	{
		foreach ($this->files as $file)
		{
			try {
				$this->fileData[] = array(
					'data'=> file_get_contents($file),
					'filename'=> $file
					);
					
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}
	}
	/*
	* starts the cleanup process
	*
	*/
	
	private function cleanData()
	{
		foreach($this->fileData as $data)
		{
			$cleanData = $this->replaceUseless($data['data']);
			$clean[] = array(
						'data' => $cleanData,
						'filename' => $data['filename'],
						);			
		}
		$this->fileData = $clean;
	
	}
	/*
	* we dont need comments and definitions, so bye bye
	*
	*
	*/
	
	private function replaceUseless($string)
	{
		$string = preg_replace('/\{(.*)\}/isUe','',$string);
		$string = preg_replace('/\/\*(.*)\*\//isUe','',$string);		
		return trim($string);
	}
	
	/*
	* seperates the css declarations
	*
	*
	*/
	private function seperateData()
	{
		$cleanArray = array();
		
		// seperate declarations
		foreach($this->fileData as $data)
		{
			$clean = '';
			$check = explode(',', $data['data']);
			foreach ($check as $entry)
			{
				$clean.= trim($entry);
			}
			$cleanArray[] = array(
						'data' => $clean,
						'filename' => $data['filename'],
						);		
		}
		$this->fileData = $cleanArray;
		// cut at newline
		$cleanArray = array();
		foreach($this->fileData as $data) {
			$dataArrayClean = array();
			$dataArray = preg_split("/\\r\\n|\\r|\\n/", $data['data']);
			foreach($dataArray as $entry)
			{
				$entry = trim($entry);
				if (!empty($entry) && strlen($entry) > 2)
				{
					$dataArrayClean[] = $entry;
				}
			}
			$cleanArray[] = array(
						'data' => $dataArrayClean,
						'filename' => $data['filename'],
						);	
		}
		$this->fileData = $cleanArray;
	}
	/*
	* returns all double CSS declarations
	* @return array
	*
	*/
	public function checkDouble()
	{
		$doubleStyle = array();
		$doubleStyle_temp = array();
		foreach ($this->fileData as $entry)
		{
			foreach($entry['data'] as $subentry)
			{
				foreach($doubleStyle_temp as $_tempsearch)
				{
					if ($_tempsearch['style'] == $subentry )
					{
						$doubleStyle[] = array('filename1' => $entry['filename'],'filename2' => $_tempsearch['filename'], 'style' => $subentry);
					}
				}
				if (in_array($subentry,$doubleStyle_temp))
				{
					
				}				
				$doubleStyle_temp[] = array('filename' => $entry['filename'], 'style' => $subentry);
			}
		}
		return $doubleStyle;
	}
}