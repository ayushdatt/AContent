<link href="./_diff.css" rel="stylesheet"></link>

<?php 
include './DifferenceEngine.php';
include './common.php';

html_diff();

function html_diff($l_text, $r_text, $type=null){

	$l_text="adfasdfadsfasfasd";
	$r_text="adfasdfadsfasfasd adfdasfasfas";
    $type = 'inline';

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

?>
