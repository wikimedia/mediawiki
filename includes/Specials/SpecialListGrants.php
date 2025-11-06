<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\User;

/**
 * List all defined rights grants and the associated rights.
 *
 * See also @ref $wgGrantPermissions and @ref $wgGrantPermissionGroups.
 *
 * @see SpecialListGroupRights
 * @ingroup SpecialPage
 */
class SpecialListGrants extends SpecialPage {
	private GrantsLocalization $grantsLocalization;

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
			Html::openElement( 'table', [ 'class' => [ 'wikitable', 'mw-listgrouprights-table' ] ] ) .
				'<tr>' .
				Html::element( 'th', [], $this->msg( 'listgrants-grant' )->text() ) .
				Html::element( 'th', [], $this->msg( 'listgrants-rights' )->text() ) .
				'</tr>'
		);

		$lang = $this->getLanguage();

		foreach (
			$this->getConfig()->get( MainConfigNames::GrantPermissions ) as $grant => $rights
		) {
			$descs = [];
			$rights = array_filter( $rights ); // remove ones with 'false'
			foreach ( $rights as $permission => $granted ) {
				$descs[] = $this->msg( 'listgrouprights-right-display' )
					->params( User::getRightDescription( $permission ) )
					->rawParams( Html::element(
						'span',
						[ 'class' => 'mw-listgrants-right-name' ],
						$permission
					) )
					->parse();
			}
			if ( $descs === [] ) {
				$grantCellHtml = '';
			} else {
				sort( $descs );
				$grantCellHtml = '<ul><li>' . implode( "</li>\n<li>", $descs ) . '</li></ul>';
			}

			$out->addHTML( Html::rawElement( 'tr', [ 'id' => $grant ],
				"<td>" .
				$this->msg( 'listgrants-grant-display' )
					->params( $this->grantsLocalization->getGrantDescription( $grant, $lang ) )
					->rawParams( Html::element(
						'span',
						[ 'class' => 'mw-listgrants-grant-name' ],
						$grant
					) )
					->parse() .
				"</td>" .
				"<td>" . $grantCellHtml . "</td>"
			) );
		}

		$out->addHTML( Html::closeElement( 'table' ) );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'users';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialListGrants::class, 'SpecialListGrants' );
