<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Redirect to a random redirect page (minus the second redirect)
 *
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>, Ilmari Karonen
 */
class SpecialRandomRedirect extends SpecialRandomPage {

	public function __construct(
		IConnectionProvider $dbProvider,
		NamespaceInfo $nsInfo
	) {
		parent::__construct( $dbProvider, $nsInfo );
		$this->mName = 'Randomredirect';
		$this->isRedir = true;
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialRandomRedirect::class, 'SpecialRandomRedirect' );
