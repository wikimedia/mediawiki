<?php

require_once($IP.'/includes/SkinPHPTal.php');

class SkinDaVinci extends SkinPHPTal {
	function initPage( &$out ) {
		SkinPHPTal::initPage( $out );
		$this->skinname = 'davinci';
	}
}
?>
