<?php
//
//	TO DO:
//
//	create formula for naming subfolders based on first 4 characters of filename
//		e.g., J199v01n01_01.pdf can go into subfolder J199, GSTI_A_357616_O.pdf goes into GSTI
//	can use substr($handle,0,4) or regular expression with underscore as delimiter for folder name
//	create subfolder if it doesn't exist, otherwise add file (rename) to existing subfolder

//$dir: target folder that needs cleanup
$dir = "C:\\Users\\MoteC\\Desktop\\RESTAMP_FOLDER";

function folder_cleanup($dir){

	echo "\nBeginning folder cleanup...";
	$handle=@opendir($dir) or die("\nError: cannot open $dir");
	readdir($handle);	//.
	readdir($handle);	//..

	echo "\nFolder $dir opened...";

	//create log file to record history of files copied and deleted
	$file_log = basename($dir) . "_log_" . date('YmdGis') . ".txt";
	$fhandle = fopen($file_log,'a+');
	echo "\nWriting file log to $file_log ... ";

	$i = 0; $j = 0; //$i: count files, $j: count subfolders
	//loop through all files in the directory
	while(true){

		$entry = readdir($handle);
		if(false!==($entry)){
			//create subfolder (remove "./TEST/" for live run)
			$subfolder = "./TEST/".substr($entry,0,4);
			if(!is_dir($subfolder)){
				mkdir($subfolder);
				$j++;
				echo "\nNew subfolder created: $subfolder ";
				fwrite($fhandle,"\nSubfolder created: $subfolder ");
			}

			$source = $dir . "/" . $entry;
			$dest = $subfolder . "/" . $entry;

			//move to new folder and delete from current folder
			if(rename($source, $dest)){
				fwrite($fhandle,"\nFile $source moved to $dest ...");
				$i++;
			} else{
				fwrite($fhandle,"\nWarning: failed to move $source to $dest ...");
			}

		}else{
			fwrite($fhandle,"\nUpper file limit reached. $i files successfully moved into $j subfolders.");
			fclose($fhandle);
			exit("\nUpper file limit reached. $i files successfully moved into $j subfolders.");
		}
	}
}

folder_cleanup($dir);

?>
