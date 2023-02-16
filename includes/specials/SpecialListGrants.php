<?php
/**
 * Implements Special:Listgrants
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

use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\GrantsLocalization;

/**
 * This special page lists all defined rights grants and the associated rights.
 * See also @ref $wgGrantPermissions and @ref $wgGrantPermissionGroups.
 *
 * @ingroup SpecialPage
 */
class SpecialListGrants extends SpecialPage {
	/** @var GrantsLocalization */
	private $grantsLocalization;

	public function __construct( GrantsLocalization $grantsLocalization ) {
		parent::__construct( 'Listgrants' );
		$this->grantsLocalization = $grantsLocalization;
	}

	/**
	 * Show the special page
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.special' );

		$out->addHTML(
			\MediaWiki\Html\Html::openElement( 'table',
											   [ 'class' => 'wikitable mw-listgrouprights-table' ] ) .
				'<tr>' .
				\MediaWiki\Html\Html::element( 'th', [], $this->msg( 'listgrants-grant' )->text() ) .
				\MediaWiki\Html\Html::element( 'th', [], $this->msg( 'listgrants-rights' )->text() ) .
				'</tr>'
		);

		$lang = $this->getLanguage();

		foreach (
			$this->getConfig()->get( MainConfigNames::GrantPermissions ) as $grant => $rights
		) {
			$descs = [];
			$rights = array_filter( $rights ); // remove ones with 'false'
			foreach ( $rights as $permission => $granted ) {
				$descs[] = $this->msg(
					'listgrouprights-right-display',
					\User::getRightDescription( $permission ),
					'<span class="mw-listgrants-right-name">' . $permission . '</span>'
				)->parse();
			}
			if ( $descs === [] ) {
				$grantCellHtml = '';
			} else {
				sort( $descs );
				$grantCellHtml = '<ul><li>' . implode( "</li>\n<li>", $descs ) . '</li></ul>';
			}

			$id = Sanitizer::escapeIdForAttribute( $grant );
			$out->addHTML( \MediaWiki\Html\Html::rawElement( 'tr', [ 'id' => $id ],
															 "<td>" .
				$this->msg(
					"listgrants-grant-display",
					$this->grantsLocalization->getGrantDescription( $grant, $lang ),
					"<span class='mw-listgrants-grant-name'>" . $id . "</span>"
				)->parse() .
				"</td>" .
				"<td>" . $grantCellHtml . "</td>"
			) );
		}

		$out->addHTML( \MediaWiki\Html\Html::closeElement( 'table' ) );
	}

	protected function getGroupName() {
		return 'users';
	}
}
