<?php
if ($wgUsePHPTal) {
require_once('includes/SkinPHPTal.php');

class SkinMySkin extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'myskin';
	}
}

}
?>
