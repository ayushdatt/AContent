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
    var $varsionsDir,$atticDir,$metadir,$timeofversion;
    
    function Versions(){
        $this->versionsDir = TR_CONTENT_DIR."versions/";
    	$this->atticDir = $this->versionsDir."attic/";
    	$this->metaDir = $this->versionsDir."meta/";
        $this->timeofversion=time();
    }
    
    function Create_Meta($cid,$flag){
         	
        $myFile = $this->metaDir.$cid.".txt";
        $fp = fopen($myFile,'a') or die("cannot open file");
        $dataToWrite = $this->timeofversion."\t".$_SESSION['user_id']."\t".$flag."\n";
        fwrite($fp, $dataToWrite);
        fclose($myFile);
    }
    
    
    function Create_Versions($cid, $data){
    	//print_r($data);
    	//echo "haha $cid";
        $myFile = $this->atticDir.$cid."_".$this->timeofversion.".txt";
    	$fp = fopen($myFile,'w') or die("cannot open file");
    	$dataToWrite = $data['title']."\n".$data['body_text'];
    	fwrite($fp, $dataToWrite);
        fclose($myFile);
    	//exit(0);
    }
}
