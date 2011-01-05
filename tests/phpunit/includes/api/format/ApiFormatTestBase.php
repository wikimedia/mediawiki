<?php

require_once dirname( dirname( __FILE__ ) ) . '/ApiSetup.php';

abstract class ApiFormatTestBase extends ApiTestSetup {

	protected function apiRequest( $format, $params, $data = null ) {
		
		$data = parent::doApiRequest( $params, $data, true );
		
		$module = $data[3];
		
		$printer = $module->createPrinterByName( $format );
		$printer->setUnescapeAmps( false );

		$printer->initPrinter( false );

		ob_start();
		$printer->execute();
		$out = ob_get_clean();
		
		$printer->closePrinter();

		return $out;
	}

	function setupUser() {
		/* Do not setup a user here */
	}
}

