<?php
if ($wgUsePHPTal) {
require_once($IP.'/includes/SkinPHPTal.php');

class SkinChick extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'chick';
		$this->template = 'Chick';
	}
	function suppressUrlExpansion() { return true; }
	function printSource() { return ''; }
}

}
?>
