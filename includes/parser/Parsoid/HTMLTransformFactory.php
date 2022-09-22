<?php

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use Wikimedia\Parsoid\Parsoid;

/**
 * @since 1.40
 * @unstable should be marked stable before 1.40 release
 */
class HTMLTransformFactory {

	/** @var Parsoid */
	private $parsoid;

	/** @var array */
	private $parsoidSettings;

	/** @var PageConfigFactory */
	private $configFactory;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/**
	 * @param Parsoid $parsoid
	 * @param array $parsoidSettings
	 * @param PageConfigFactory $configFactory
	 * @param IContentHandlerFactory $contentHandlerFactory
	 */
	public function __construct(
		Parsoid $parsoid,
		array $parsoidSettings,
		PageConfigFactory $configFactory,
		IContentHandlerFactory $contentHandlerFactory
	) {
		$this->parsoid = $parsoid;
		$this->parsoidSettings = $parsoidSettings;
		$this->configFactory = $configFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
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
		return new HTMLTransform(
			$modifiedHTML,
			$page,
			$this->parsoid,
			$this->parsoidSettings,
			$this->configFactory,
			$this->contentHandlerFactory
		);
	}

}
