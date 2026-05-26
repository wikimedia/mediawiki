<?php

namespace MediaWiki\ShadowPage;

/**
 * @stable to override
 * @since 1.47
 */
abstract class BaseShadowPageProvider implements ShadowPageProvider {
	private ParseHelper $parseHelper;

	/**
	 * @internal
	 */
	public function initBaseDeps( ParseHelper $parseHelper ): void {
		$this->parseHelper = $parseHelper;
	}

	protected function getParseHelper(): ParseHelper {
		return $this->parseHelper;
	}
}
