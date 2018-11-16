<?php
/**
 * Special page for replacing manually disabled special pages
 *
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
 * @ingroup SpecialPage
 */

/**
 * This class is a drop-in replacement for other special pages that need to be manually
 * disabled. To use it, just put something like
 *
 *     $wgSpecialPages['Name'] = DisabledSpecialPage::getCallback( 'Name', 'message' );
 *
 * in the local configuration (where 'Name' is the canonical name of the special page
 * to be disabled, and 'message' is a message key for explaining the reason for disabling).
 *
 * @since 1.33
 */
class DisabledSpecialPage extends UnlistedSpecialPage {

	/** @var Message */
	protected $errorMessage;

	/**
	 * Create a callback suitable for use in $wgSpecialPages.
	 * @param string $name Canonical name of the special page that's being replaced.
	 * @param Message|string|null $errorMessage Error message to show when users try to use the page.
	 * @return Closure
	 */
	public static function getCallback( $name, $errorMessage = null ) {
		return function () use ( $name, $errorMessage ) {
			return new DisabledSpecialPage( $name, $errorMessage );
		};
	}

	/**
	 * @param string $name Canonical name of the special page that's being replaced.
	 * @param Message|string|null $errorMessage Error message to show when users try to use the page.
	 */
	public function __construct( $name, $errorMessage = null ) {
		parent::__construct( $name );
		$this->errorMessage = $errorMessage ?: 'disabledspecialpage-disabled';
	}

	public function execute( $subPage ) {
		$this->setHeaders();
		$this->outputHeader();

		$error = Html::rawElement( 'div', [
			'class' => 'error',
		], $this->msg( $this->errorMessage )->parseAsBlock() );
		$this->getOutput()->addHTML( $error );
	}

}
