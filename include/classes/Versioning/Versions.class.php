<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Versions
 *
 * @author ayush
 */
class Versions {
    //put your code here
    function Versions(){}
    function Create_Meta(){
    }
    function Create_Versions($cid, $data){
    	print_r($data);
    	echo "haha $cid";
    	// echo TR_CONTENT_DIR;
    	$versionsDir = TR_CONTENT_DIR."versions/";
    	$atticDir = $versionsDir."attic/";
    	$metaDir = $versionsDir."meta/";
    	// echo $versionsDir;
    	echo $atticDir."test.txt";
    	// echo $metaDir;
    	$fp = fopen($atticDir."test23.txt", 'w') or die("cannot open file");
    	$dataToWrite = $data['title']."\n".$data['body_text'];
    	echo "<br>".$dataToWrite."<br>";
    	fwrite($fp, $dataToWrite);
    	exit(0);
    }
}
