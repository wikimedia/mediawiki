<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\SpecialPage;

/**
 * Implements Special:ApiSandbox
 *
 * @since 1.27
 * @ingroup SpecialPage
 */
class SpecialApiSandbox extends SpecialPage {

	public function __construct() {
		parent::__construct( 'ApiSandbox' );
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$out = $this->getOutput();
		$this->addHelpLink( 'Help:ApiSandbox' );

		$out->addJsConfigVars(
			'apihighlimits',
			$this->getAuthority()->isAllowed( 'apihighlimits' )
		);
		$out->addModuleStyles( [
			'mediawiki.special',
			'mediawiki.hlist',
		] );
		$out->addModules( [
			'mediawiki.special.apisandbox',
			'mediawiki.apipretty',
		] );
		$out->wrapWikiMsg(
			"<div id='mw-apisandbox'><div class='mw-apisandbox-nojs error'>\n$1\n</div></div>",
			'apisandbox-jsonly'
		);
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'wiki';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialApiSandbox::class, 'SpecialApiSandbox' );
