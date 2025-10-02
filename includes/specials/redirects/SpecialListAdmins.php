<?php
/**
 * Copyright © 2004 Brooke Vibber, lcrocker, Tim Starling,
 * Domas Mituzas, Antoine Musso, Jens Frank, Zhengzhu,
 * 2006 Rob Church <robchur@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials\Redirects;

use MediaWiki\SpecialPage\SpecialRedirectToSpecial;

/**
 * Redirect to Special:ListUsers/sysop.
 *
 * @ingroup SpecialPage
 */
class SpecialListAdmins extends SpecialRedirectToSpecial {
	public function __construct() {
		parent::__construct( 'Listadmins', 'Listusers', 'sysop' );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialListAdmins::class, 'SpecialListAdmins' );
