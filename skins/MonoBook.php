<?php
require_once($IP.'/includes/SkinPHPTal.php');

class SkinMonoBook extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'monobook';
	}
}

?>
