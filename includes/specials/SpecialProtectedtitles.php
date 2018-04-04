<?php
/**
 * Implements Special:Protectedtitles
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
 * A special page that list protected titles from creation
 *
 * @ingroup SpecialPage
 */
class SpecialProtectedtitles extends SpecialPage {
	protected $IdLevel = 'level';
	protected $IdType = 'type';

	public function __construct() {
		parent::__construct( 'Protectedtitles' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$request = $this->getRequest();
		$type = $request->getVal( $this->IdType );
		$level = $request->getVal( $this->IdLevel );
		$sizetype = $request->getVal( 'sizetype' );
		$size = $request->getIntOrNull( 'size' );
		$NS = $request->getIntOrNull( 'namespace' );

		$pager = new ProtectedTitlesPager( $this, [], $type, $level, $NS, $sizetype, $size );

		$this->getOutput()->addHTML( $this->showOptions( $NS, $type, $level ) );

		if ( $pager->getNumRows() ) {
			$this->getOutput()->addHTML(
				$pager->getNavigationBar() .
					'<ul>' . $pager->getBody() . '</ul>' .
					$pager->getNavigationBar()
			);
		} else {
			$this->getOutput()->addWikiMsg( 'protectedtitlesempty' );
		}
	}

	/**
	 * Callback function to output a restriction
	 *
	 * @param object $row Database row
	 * @return string
	 */
	function formatRow( $row ) {
		$title = Title::makeTitleSafe( $row->pt_namespace, $row->pt_title );
		if ( !$title ) {
			return Html::rawElement(
				'li',
				[],
				Html::element(
					'span',
					[ 'class' => 'mw-invalidtitle' ],
					Linker::getInvalidTitleDescription(
						$this->getContext(),
						$row->pt_namespace,
						$row->pt_title
					)
				)
			) . "\n";
		}

		$link = $this->getLinkRenderer()->makeLink( $title );
		// Messages: restriction-level-sysop, restriction-level-autoconfirmed
		$description = $this->msg( 'restriction-level-' . $row->pt_create_perm )->escaped();
		$lang = $this->getLanguage();
		$expiry = strlen( $row->pt_expiry ) ?
			$lang->formatExpiry( $row->pt_expiry, TS_MW ) :
			'infinity';

		if ( $expiry !== 'infinity' ) {
			$user = $this->getUser();
			$description .= $this->msg( 'comma-separator' )->escaped() . $this->msg(
				'protect-expiring-local',
				$lang->userTimeAndDate( $expiry, $user ),
				$lang->userDate( $expiry, $user ),
				$lang->userTime( $expiry, $user )
			)->escaped();
		}

		return '<li>' . $lang->specialList( $link, $description ) . "</li>\n";
	}

	/**
	 * @param int $namespace
	 * @param string $type
	 * @param string $level
	 * @return string
	 * @private
	 */
	function showOptions( $namespace, $type = 'edit', $level ) {
		$formDescriptor = [
			'namespace' => [
				'class' => 'HTMLSelectNamespace',
				'name' => 'namespace',
				'id' => 'namespace',
				'cssclass' => 'namespaceselector',
				'all' => '',
				'label' => $this->msg( 'namespace' )->text()
			],
			'levelmenu' => $this->getLevelMenu( $level )
		];

		$htmlForm = new HTMLForm( $formDescriptor, $this->getContext() );
		$htmlForm
			->setMethod( 'get' )
			->setWrapperLegendMsg( 'protectedtitles' )
			->setSubmitText( $this->msg( 'protectedtitles-submit' )->text() );

		return $htmlForm->prepareForm()->getHTML( false );
	}

	/**
	 * @param string $pr_level Determines which option is selected as default
	 * @return string Formatted HTML
	 * @private
	 */
	function getLevelMenu( $pr_level ) {
		// Temporary array
		$m = [ $this->msg( 'restriction-level-all' )->text() => 0 ];
		$options = [];

		// First pass to load the log names
		foreach ( $this->getConfig()->get( 'RestrictionLevels' ) as $type ) {
			if ( $type != '' && $type != '*' ) {
				// Messages: restriction-level-sysop, restriction-level-autoconfirmed
				$text = $this->msg( "restriction-level-$type" )->text();
				$m[$text] = $type;
			}
		}

		// Is there only one level (aside from "all")?
		if ( count( $m ) <= 2 ) {
			return '';
		}
		// Third pass generates sorted XHTML content
		foreach ( $m as $text => $type ) {
			$options[ $text ] = $type;
		}

		return [
			'type' => 'select',
			'options' => $options,
			'label' => $this->msg( 'restriction-level' )->text(),
			'name' => $this->IdLevel,
			'id' => $this->IdLevel
		];
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
