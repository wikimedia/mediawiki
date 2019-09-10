<?php
/**
 * Implements Special:Fewestrevisions
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

use MediaWiki\MediaWikiServices;

/**
 * Special page for listing the articles with the fewest revisions.
 *
 * @ingroup SpecialPage
 * @author Martin Drashkov
 */
class SpecialFewestRevisions extends QueryPage {
	function __construct( $name = 'Fewestrevisions' ) {
		parent::__construct( $name );
	}

	public function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		return [
			'tables' => [ 'revision', 'page' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'COUNT(*)',
			],
			'conds' => [
				'page_namespace' => MediaWikiServices::getInstance()->getNamespaceInfo()->
					getContentNamespaces(),
				'page_id = rev_page',
				'page_is_redirect = 0',
			],
			'options' => [
				'GROUP BY' => [ 'page_namespace', 'page_title' ]
			]
		];
	}

	function sortDescending() {
		return false;
	}

	/**
	 * @param Skin $skin
	 * @param object $result Database row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		$nt = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$nt ) {
			return Html::element(
				'span',
				[ 'class' => 'mw-invalidtitle' ],
				Linker::getInvalidTitleDescription(
					$this->getContext(),
					$result->namespace,
					$result->title
				)
			);
		}
		$linkRenderer = $this->getLinkRenderer();
		$text = MediaWikiServices::getInstance()->getContentLanguage()->
			convert( htmlspecialchars( $nt->getPrefixedText() ) );
		$plink = $linkRenderer->makeLink( $nt, new HtmlArmor( $text ) );

		$nl = $this->msg( 'nrevisions' )->numParams( $result->value )->text();
		$redirect = isset( $result->redirect ) && $result->redirect ?
			' - ' . $this->msg( 'isredirect' )->escaped() : '';
		$nlink = $linkRenderer->makeKnownLink(
			$nt,
			$nl,
			[],
			[ 'action' => 'history' ]
		) . $redirect;

		return $this->getLanguage()->specialList( $plink, $nlink );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
