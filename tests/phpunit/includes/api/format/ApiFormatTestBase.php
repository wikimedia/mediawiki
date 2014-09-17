<?php

abstract class ApiFormatTestBase extends ApiTestCase {

	/**
	 * @param string $format
	 * @param array $params
	 * @param array $data
	 *
	 * @return string
	 */
	protected function apiRequest( $format, $params, $data = null ) {
		$data = parent::doApiRequest( $params, $data, true );

		/** @var ApiMain $module */
		$module = $data[3];

		$printer = $module->createPrinterByName( $format );

		ob_start();
		$printer->initPrinter( false );
		$printer->execute();
		$printer->closePrinter();
		$out = ob_get_clean();

		return $out;
	}

}
