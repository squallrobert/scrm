<?php

/**
 * @author Squall Robert
 * @copyright 2015
 */

    function __autoload($class)
    {
        if(file_exists('classes/'.ucfirst($class). '.php'))
            include_once('classes/'.ucfirst($class). '.php');
        else
             include_once('modulos/'.underscore($class).'/classe.'.underscore($class).".php");
    }
    function underscore($name)
    {
		$name	= preg_replace('/([a-z])([A-Z])/',"$1_$2",$name);	
		$name	= strtr($name," ","_");
		return strtolower($name);
	}


?>