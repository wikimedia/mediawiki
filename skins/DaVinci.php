<?php
if ($wgUsePHPTal) {
require_once('includes/SkinPHPTal.php');

class SkinDaVinci extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'davinci';
		$this->template = 'MonoBook';
	}
}

}
?>
