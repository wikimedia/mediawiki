<?php
if ($wgUsePHPTal) {
require_once($IP.'/includes/SkinPHPTal.php');

class SkinMono extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'mono';
		$this->template = 'MonoBook';
	}
}

}
?>
