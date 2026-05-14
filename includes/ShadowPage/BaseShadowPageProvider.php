<?php

namespace MediaWiki\ShadowPage;

/**
 * @stable to override
 * @since 1.47
 */
abstract class BaseShadowPageProvider implements ShadowPageProvider {
	/** @var ParseHelper */
	private $parseHelper;

	/**
	 * @internal
	 * @param ParseHelper $parseHelper
	 */
	public function initBaseDeps( ParseHelper $parseHelper ) {
		$this->parseHelper = $parseHelper;
	}

	protected function getParseHelper(): ParseHelper {
		return $this->parseHelper;
	}
}
