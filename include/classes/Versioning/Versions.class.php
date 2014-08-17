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
    var $varsionsDir,$atticDir,$metadir,$timeofversion,$meta;
    
    function Versions(){
        $this->versionsDir = TR_CONTENT_DIR."versions/";
    	$this->atticDir = $this->versionsDir."attic/";
    	$this->metaDir = $this->versionsDir."meta/";
        $this->timeofversion=time();
    }
    
    function Create_Meta($cid, $flag, $version_id=null){
        $myFile = $this->metaDir.$cid.".txt";
        $fp = fopen($myFile,'a') or die("cannot open file");
        if($version_id!=null){
            $flag='R';
            $dataToWrite = $this->timeofversion."\t".$_SESSION['user_id']."\t".$flag."\t".$version_id."\n";
        }
        else{
            $dataToWrite = $this->timeofversion."\t".$_SESSION['user_id']."\t".$flag."\n";
        }
        
        fwrite($fp, $dataToWrite);
        fclose($myFile);
        //exit(0);
    }
    
    
    function Create_Versions($cid, $data){
        $myFile = $this->atticDir.$cid."_".$this->timeofversion.".txt.gz";
    	$fp = gzopen($myFile,'w') or die("cannot open file");
    	$dataToWrite = $data['title']."\n".$data['body_text'];
    	gzwrite($fp, $dataToWrite);
        fclose($myFile);
    	//exit(0);
    }
    
    function get_Versions($cid)
    {
        $myFile = $this->metaDir.$cid.".txt";
        $fp = fopen($myFile,'r') or die("cannot open file");
        $this->meta=array();
        while (!feof($fp)){
            $line = trim(fgets($fp));
            if ($line == "") {
                break;
            }
            $parts = explode("\t",$line);
            array_push($this->meta, $parts);
        }
        $this->meta=  array_reverse($this->meta);
        fclose($fp);   
    }
    
    function get_Version_text($cid,$version_id)
    {
        $myFile = $this->atticDir.$cid."_".$version_id.".txt.gz";
        $lines = gzfile($myFile);
        $version_text="";
        $counter=0;
        foreach ($lines as $line) {
            if($counter===0){
                $counter++;
            }
            else{
                $version_text = $version_text.$line;
            }
        }
        return $version_text;
    }
    
    function get_Version_title($cid,$version_id)
    {
        $myFile = $this->atticDir.$cid."_".$version_id.".txt.gz";
    	$fp = gzopen($myFile,'r') or die("cannot open file");
    	$title = trim(fgets($fp));
        fclose($fp);   
        return $title;
    }
    
}
