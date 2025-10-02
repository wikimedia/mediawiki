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
 * Redirect to Special:ListUsers/bot.
 *
 * @ingroup SpecialPage
 */
class SpecialListBots extends SpecialRedirectToSpecial {
	public function __construct() {
		parent::__construct( 'Listbots', 'Listusers', 'bot' );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialListBots::class, 'SpecialListBots' );
