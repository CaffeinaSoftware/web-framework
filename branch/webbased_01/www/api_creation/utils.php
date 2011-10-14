<?php


	function Zip($source, $destination)
	{
	    if (extension_loaded('zip') === false)
	    {
	    	throw new Exception ("zip extension not loaded");
	    }
	    

        if (file_exists($source) === false)
        {
        	throw new Exception ("source does not exist");
        }
	        
        $zip = new ZipArchive();

        if ($zip->open( $destination, ZIPARCHIVE::CREATE ) === true)
        {
                $source = realpath($source);

                if (is_dir($source) === true)
                {
                        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

                        foreach ($files as $file)
                        {
                                $file = realpath($file);

                                if (is_dir($file) === true)
                                {
                                        $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                                }

                                else if (is_file($file) === true)
                                {
                                        $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                                }
                        }
                }

                else if (is_file($source) === true)
                {
                        $zip->addFromString(basename($source), file_get_contents($source));
                }
        }

        return $zip->close();
	        
	    
	    return false;
	}



	function delete_directory($dirname) {
	   if (is_dir($dirname))
	      $dir_handle = opendir($dirname);
	   if (!$dir_handle)
	      return false;
	   while($file = readdir($dir_handle)) {
	      if ($file != "." && $file != "..") {
	         if (!is_dir($dirname."/".$file))
	            unlink($dirname."/".$file);
	         else
	            delete_directory($dirname.'/'.$file);    
	      }
	   }
	   closedir($dir_handle);
	   rmdir($dirname);
	   return true;
	}
 















 	function create_structure( $dir, $n = 1 ){

 		$p = explode( "/", $dir );
 		
 		if( $n == sizeof($p) ) return;

		$f = "";

		for ($i=0; $i < $n; $i++) $f .=  $p[$i] . "/";

 		if( !is_dir( $f ) ) mkdir ( $f );
		
		create_structure($dir, ++$n);
 	}









