<?php

abstract class ApiFormatTestBase extends ApiTestCase {

	/**
	 * @param string $format
	 * @param array $params
	 * @param $data
	 *
	 * @return string
	 */
	protected function apiRequest( $format, $params, $data = null ) {
		$data = parent::doApiRequest( $params, $data, true );

		/** @var ApiMain $module */
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

}
