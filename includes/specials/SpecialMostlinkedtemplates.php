<?php
/**
 * Implements Special:Mostlinkedtemplates
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
 * @author Rob Church <robchur@gmail.com>
 */

/**
 * Special page lists templates with a large number of
 * transclusion links, i.e. "most used" templates
 *
 * @ingroup SpecialPage
 */
class MostlinkedTemplatesPage extends QueryPage {
	function __construct( $name = 'Mostlinkedtemplates' ) {
		parent::__construct( $name );
	}

	/**
	 * Is this report expensive, i.e should it be cached?
	 *
	 * @return bool
	 */
	public function isExpensive() {
		return true;
	}

	/**
	 * Is there a feed available?
	 *
	 * @return bool
	 */
	public function isSyndicated() {
		return false;
	}

	/**
	 * Sort the results in descending order?
	 *
	 * @return bool
	 */
	public function sortDescending() {
		return true;
	}

	public function getQueryInfo() {
		return array(
			'tables' => array( 'templatelinks' ),
			'fields' => array(
				'namespace' => 'tl_namespace',
				'title' => 'tl_title',
				'value' => 'COUNT(*)'
			),
			'options' => array( 'GROUP BY' => array( 'tl_namespace', 'tl_title' ) )
		);
	}

	/**
	 * Pre-cache page existence to speed up link generation
	 *
	 * @param IDatabase $db
	 * @param ResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = new LinkBatch();
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		$res->seek( 0 );
	}

	/**
	 * Format a result row
	 *
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return Html::element(
				'span',
				array( 'class' => 'mw-invalidtitle' ),
				Linker::getInvalidTitleDescription(
					$this->getContext(),
					$result->namespace,
					$result->title
				)
			);
		}

		return $this->getLanguage()->specialList(
			Linker::link( $title ),
			$this->makeWlhLink( $title, $result )
		);
	}

	/**
	 * Make a "what links here" link for a given title
	 *
	 * @param Title $title Title to make the link for
	 * @param object $result Result row
	 * @return string
	 */
	private function makeWlhLink( $title, $result ) {
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedText() );
		$label = $this->msg( 'ntransclusions' )->numParams( $result->value )->escaped();

		return Linker::link( $wlh, $label );
	}

	protected function getGroupName() {
		return 'highuse';
	}
}
