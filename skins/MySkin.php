<?php
if ($wgUsePHPTal) {

require_once($IP.'/includes/SkinPHPTal.php');

class SkinMySkin extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'myskin';
	}
}

}
?>
