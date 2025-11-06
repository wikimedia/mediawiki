<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of all uncategorised pages in the Template namespace.
 *
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */
class SpecialUncategorizedTemplates extends SpecialUncategorizedPages {

	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct(
			$namespaceInfo,
			$dbProvider,
			$linkBatchFactory,
			$languageConverterFactory
		);
		$this->mName = 'Uncategorizedtemplates';
		$this->requestedNamespace = NS_TEMPLATE;
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUncategorizedTemplates::class, 'SpecialUncategorizedTemplates' );
