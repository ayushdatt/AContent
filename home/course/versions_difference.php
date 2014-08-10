<link href="./_diff.css" rel="stylesheet"></link>

<?php 
define('TR_INCLUDE_PATH', '../../include/');
require(TR_INCLUDE_PATH.'vitals.inc.php');
require_once(TR_INCLUDE_PATH.'classes/Versioning/Versions.class.php');
include './DifferenceEngine.php';
include './common.php';

function html_diff($cid, $l_vid, $r_vid, $type=null, $type1=null){
    $versions=new Versions();
    $l_text=$versions->get_Version_text($cid,$l_vid);
    $r_text=$versions->get_Version_text($cid,$r_vid);
    //$type = 'inline';
    if($type1=="text"){
    $l_text = trim(strip_tags($l_text));
    $r_text = trim(strip_tags($r_text));
    }
    
    $df = new Diff(explode("\n",$l_text),explode("\n",$r_text));
    if($type == 'inline'){
        $tdf = new InlineDiffFormatter();
    } else {
        $tdf = new TableDiffFormatter();
    }
    $url=TR_BASE_HREF."home/editor/edit_content.php?_cid=".$_GET['_cid'];
    $l_head=date('d-m-Y', $l_vid)."\t".date('H:i:s', $l_vid);
    $r_head=date('d-m-Y', $r_vid[0])."\t".date('H:i:s', $r_vid);            
    ?>
    <div class="dokuwiki">
	<div class="table">
	    <table class="diff diff_<?php echo $type?>">
             <?php
        //navigation and header
            if($type == 'inline') {
            ?>
                <tr>
                    <th class="diff-lineheader">-</th>
                    <th>
                        <?php echo $l_head ?>
                    </th>
                </tr>
           
                <tr>
                    <th class="diff-lineheader">+</th>
                    <th>
                        <?php echo $r_head ?>
                    </th>
                </tr>
            <?php } else { ?>
                <tr>
                    <th colspan="2">
                        <?php echo $l_head ?>
                    </th>
                    <th colspan="2">
                        <?php echo $r_head ?>
                    </th>
                </tr>
        <?php }

	   
	    echo html_insert_softbreaks($tdf->format($df));
	    ?>

	    </table>
	    </div>
    </div>
    <?php
}

function html_insert_softbreaks($diffhtml) {
    // search the diff html string for both:
    // - html tags, so these can be ignored
    // - long strings of characters without breaking characters
    return preg_replace_callback('/<[^>]*>|[^<> ]{12,}/','html_softbreak_callback',$diffhtml);
}
require(TR_INCLUDE_PATH.'header.inc.php'); 
$savant->display('home/course/versions_difference.tmpl.php');
require(TR_INCLUDE_PATH.'footer.inc.php');
?>
