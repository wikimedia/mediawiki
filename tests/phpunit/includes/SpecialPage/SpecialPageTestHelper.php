<?php

namespace MediaWiki\Tests\SpecialPage;

use MediaWiki\Specials\SpecialAllPages;

/**
 * @license GPL-2.0-or-later
 */
class SpecialPageTestHelper {

	public static function newSpecialAllPages(): SpecialAllPages {
		return new SpecialAllPages();
	}

}
