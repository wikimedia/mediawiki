<?php

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use Wikimedia\Parsoid\Parsoid;

/**
 * @unstable
 */
class HTMLTransformFactory {

	/** @var Parsoid */
	private $parsoid;

	/** @var array */
	private $parsoidSettings;

	/** @var PageConfigFactory */
	private $configFactory;

	/**
	 * @param Parsoid $parsoid
	 * @param array $parsoidSettings
	 * @param PageConfigFactory $configFactory
	 */
	public function __construct(
		Parsoid $parsoid,
		array $parsoidSettings,
		PageConfigFactory $configFactory
	) {
		$this->parsoid = $parsoid;
		$this->parsoidSettings = $parsoidSettings;
		$this->configFactory = $configFactory;
	}

	/**
	 * Get the HTML transform object for a given page and specified
	 * modified HTML.
	 *
	 * @param string $modifiedHTML
	 * @param PageIdentity $page
	 *
	 * @return HTMLTransform
	 */
	public function getHTMLTransform( string $modifiedHTML, PageIdentity $page ) {
		// XXX: do we need to be able to override anything else in the PageConfig?
		$pageConfig = $this->configFactory->create(
			$page,
			null,
			null,
			null,
			null,
			$this->parsoidSettings
		);

		return new HTMLTransform(
			$modifiedHTML,
			$pageConfig,
			$this->parsoid,
			$this->parsoidSettings
		);
	}

}
