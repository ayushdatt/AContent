<link href="./_diff.css" rel="stylesheet"></link>

<?php 
define('TR_INCLUDE_PATH', '../../include/');
require(TR_INCLUDE_PATH.'vitals.inc.php');
require_once(TR_INCLUDE_PATH.'classes/Versioning/Versions.class.php');
include './DifferenceEngine.php';
include './common.php';

function html_diff($cid, $l_vid, $r_vid, $type=null){
    $versions=new Versions();
    $l_text=$versions->get_Version_text($cid,$l_vid);
    $r_text=$versions->get_Version_text($cid,$r_vid);
    //$type = 'inline';

    $df = new Diff(explode("\n",$l_text),explode("\n",$r_text));
    if($type == 'inline'){
        $tdf = new InlineDiffFormatter();
    } else {
        $tdf = new TableDiffFormatter();
    }

    ?>
    <div class="dokuwiki">
	    <div class="table">
	    <table class="diff diff_<?php echo $type?>">

	    <?php
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
