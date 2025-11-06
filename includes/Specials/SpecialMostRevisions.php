<?php
/**
 * Copyright © 2005 Ævar Arnfjörð Bjarmason
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implements Special:Mostrevisions
 *
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */
class SpecialMostRevisions extends SpecialFewestRevisions {

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
		$this->mName = 'Mostrevisions';
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return true;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'highuse';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialMostRevisions::class, 'SpecialMostRevisions' );
