<?php
/**
 * Copyright © 2008 Hojjat (aka Huji)
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Redirect to a random page that isn't a subpage.
 *
 * @ingroup SpecialPage
 */
class SpecialRandomRootPage extends SpecialRandomPage {

	public function __construct(
		IConnectionProvider $dbProvider,
		NamespaceInfo $nsInfo
	) {
		parent::__construct( $dbProvider, $nsInfo );
		$this->mName = 'Randomrootpage';
		$dbr = $dbProvider->getReplicaDatabase();
		$this->extra[] = $dbr->expr(
			'page_title',
			IExpression::NOT_LIKE,
			new LikeValue( $dbr->anyString(), '/', $dbr->anyString() )
		);
	}

	/** @inheritDoc */
	public function isRedirect() {
		// Don't select redirects
		return false;
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialRandomRootPage::class, 'SpecialRandomRootPage' );
