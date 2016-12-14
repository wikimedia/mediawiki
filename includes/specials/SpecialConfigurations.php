<?php
class SpecialConfigurations extends SpecialPage {
	function __construct() {
		parent::__construct( 'Configurations' );
	}

	public function execute( $subPage ) {
		$configRepo = \MediaWiki\MediaWikiServices::getInstance()->getConfigRepository();

		$rows = [];
		foreach ( $configRepo as $config ) {
			/** @var \MediaWiki\Config\ConfigItem $value */
			try {
				$value = $config->getValue();
			} catch ( ConfigException $e ) {
				$value = 'nothing';
			}
			$rows[] = [ $config->getName(), $value, $config->getDefaultValue() ];
		}
		$this->getOutput()->addHTML( Xml::buildTable( $rows, [
			'class' => 'wikitable',
		], [
			'Configuration',
			'Value',
			'default value'
		] ) );
	}
}