<?php
if ($wgUsePHPTal) {
require_once('includes/SkinPHPTal.php');

class SkinMonoBook extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'monobook';
	}
}

}
?>
