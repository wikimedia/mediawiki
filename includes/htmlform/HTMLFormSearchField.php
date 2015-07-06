<?php
/**
 * Provides an OOUI style title search field provided by TitleInputWidget.
 * @since 1.26
 */
class HTMLFormSearchField extends HTMLTextField {
	protected $mOOUIClass = 'MediaWiki\Widget\TitleInputWidget';

	/*
	 * Override the type and force it to be 'search'.
	 * @param array $attribs Attribuites returned by self::getAttributes
	 * @return string
	 */
	protected function getType( &$attribs ) {
		return 'search';
	}

	/**
	 * Force to view a search icon and set a namespace (default: all namespaces).
	 * @param array $list List of attributes to get
	 * @param array $mappings Optional - Key/value map of attribute names to use instead of the ones passed in
	 * @return array Attributes
	 */
	public function getAttributes( array $list, array $mappings = NULL ) {
		$params = parent::getAttributes( $list, $mappings );
		$params['icon'] = 'search';
		$params['namespace'] = ( isset( $this->mParams['namespace'] ) ?
			$this->mParams['namespace'] : null );
		return $params;
	}
}
