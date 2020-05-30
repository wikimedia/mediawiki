<?php
/**
 * Implements Special:Statistics
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
 * Special page lists various statistics, including the contents of
 * `site_stats`, plus page view details if enabled
 *
 * @ingroup SpecialPage
 */
class SpecialStatistics extends SpecialPage {
	private $edits, $good, $images, $total, $users,
		$activeUsers = 0;

	public function __construct() {
		parent::__construct( 'Statistics' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

		$this->edits = SiteStats::edits();
		$this->good = SiteStats::articles();
		$this->images = SiteStats::images();
		$this->total = SiteStats::pages();
		$this->users = SiteStats::users();
		$this->activeUsers = SiteStats::activeUsers();

		$text = Xml::openElement( 'table', [ 'class' => 'wikitable mw-statistics-table' ] );

		# Statistic - pages
		$text .= $this->getPageStats();

		# Statistic - edits
		$text .= $this->getEditStats();

		# Statistic - users
		$text .= $this->getUserStats();

		# Statistic - usergroups
		$text .= $this->getGroupStats();

		# Statistic - other
		$extraStats = [];
		if ( $this->getHookRunner()->onSpecialStatsAddExtra(
			$extraStats, $this->getContext() )
		) {
			$text .= $this->getOtherStats( $extraStats );
		}

		$text .= Xml::closeElement( 'table' );

		# Customizable footer
		$footer = $this->msg( 'statistics-footer' );
		if ( !$footer->isBlank() ) {
			$text .= "\n" . $footer->parse();
		}

		$this->getOutput()->addHTML( $text );
	}

	/**
	 * Format a row
	 * @param string $text Description of the row
	 * @param float|string $number A statistical number
	 * @param array $trExtraParams Params to table row, see Html::elememt
	 * @param string $descMsg Message key
	 * @param array|string $descMsgParam Message parameters
	 * @return string Table row in HTML format
	 */
	private function formatRow( $text, $number, $trExtraParams = [],
		$descMsg = '', $descMsgParam = ''
	) {
		if ( $descMsg ) {
			$msg = $this->msg( $descMsg, $descMsgParam );
			if ( !$msg->isDisabled() ) {
				$descriptionHtml = $this->msg( 'parentheses' )->rawParams( $msg->parse() )
					->escaped();
				$text .= "<br />" . Html::rawElement(
					'small',
					[ 'class' => 'mw-statistic-desc' ],
					" $descriptionHtml"
				);
			}
		}

		return Html::rawElement( 'tr', $trExtraParams,
			Html::rawElement( 'td', [], $text ) .
			Html::rawElement( 'td', [ 'class' => 'mw-statistics-numbers' ], $number )
		);
	}

	/**
	 * Each of these methods is pretty self-explanatory, get a particular
	 * row for the table of statistics
	 * @return string
	 */
	private function getPageStats() {
		$linkRenderer = $this->getLinkRenderer();

		$specialAllPagesTitle = SpecialPage::getTitleFor( 'Allpages' );
		$pageStatsHtml = Xml::openElement( 'tr' ) .
			Xml::tags( 'th', [ 'colspan' => '2' ], $this->msg( 'statistics-header-pages' )
				->parse() ) .
			Xml::closeElement( 'tr' ) .
				$this->formatRow( $linkRenderer->makeKnownLink(
					$specialAllPagesTitle,
					$this->msg( 'statistics-articles' )->text(),
					[], [ 'hideredirects' => 1 ] ),
					$this->getLanguage()->formatNum( $this->good ),
					[ 'class' => 'mw-statistics-articles' ],
					'statistics-articles-desc' ) .
				$this->formatRow( $linkRenderer->makeKnownLink( $specialAllPagesTitle,
					$this->msg( 'statistics-pages' )->text() ),
					$this->getLanguage()->formatNum( $this->total ),
					[ 'class' => 'mw-statistics-pages' ],
					'statistics-pages-desc' );

		// Show the image row only, when there are files or upload is possible
		if ( $this->images !== 0 || $this->getConfig()->get( 'EnableUploads' ) ) {
			$pageStatsHtml .= $this->formatRow(
				$linkRenderer->makeKnownLink( SpecialPage::getTitleFor( 'MediaStatistics' ),
				$this->msg( 'statistics-files' )->text() ),
				$this->getLanguage()->formatNum( $this->images ),
				[ 'class' => 'mw-statistics-files' ] );
		}

		return $pageStatsHtml;
	}

	private function getEditStats() {
		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', [ 'colspan' => '2' ],
				$this->msg( 'statistics-header-edits' )->parse() ) .
			Xml::closeElement( 'tr' ) .
			$this->formatRow( $this->msg( 'statistics-edits' )->parse(),
				$this->getLanguage()->formatNum( $this->edits ),
				[ 'class' => 'mw-statistics-edits' ]
			) .
			$this->formatRow( $this->msg( 'statistics-edits-average' )->parse(),
				$this->getLanguage()->formatNum(
					sprintf( '%.2f', $this->total ? $this->edits / $this->total : 0 )
				), [ 'class' => 'mw-statistics-edits-average' ]
			);
	}

	private function getUserStats() {
		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', [ 'colspan' => '2' ],
				$this->msg( 'statistics-header-users' )->parse() ) .
			Xml::closeElement( 'tr' ) .
			$this->formatRow( $this->msg( 'statistics-users' )->parse() . ' ' .
				$this->getLinkRenderer()->makeKnownLink(
					SpecialPage::getTitleFor( 'Listusers' ),
					$this->msg( 'listgrouprights-members' )->text()
				),
				$this->getLanguage()->formatNum( $this->users ),
				[ 'class' => 'mw-statistics-users' ]
			) .
			$this->formatRow( $this->msg( 'statistics-users-active' )->parse() . ' ' .
				$this->getLinkRenderer()->makeKnownLink(
					SpecialPage::getTitleFor( 'Activeusers' ),
					$this->msg( 'listgrouprights-members' )->text()
				),
				$this->getLanguage()->formatNum( $this->activeUsers ),
				[ 'class' => 'mw-statistics-users-active' ],
				'statistics-users-active-desc',
				$this->getLanguage()->formatNum(
					$this->getConfig()->get( 'ActiveUserDays' ) )
			);
	}

	private function getGroupStats() {
		$linkRenderer = $this->getLinkRenderer();
		$text = '';
		foreach ( $this->getConfig()->get( 'GroupPermissions' ) as $group => $permissions ) {
			# Skip generic * and implicit groups
			if ( in_array( $group, $this->getConfig()->get( 'ImplicitGroups' ) )
				|| $group == '*' ) {
				continue;
			}
			$groupname = htmlspecialchars( $group );
			$msg = $this->msg( 'group-' . $groupname );
			if ( $msg->isBlank() ) {
				$groupnameLocalized = $groupname;
			} else {
				$groupnameLocalized = $msg->text();
			}
			$msg = $this->msg( 'grouppage-' . $groupname )->inContentLanguage();
			if ( $msg->isBlank() ) {
				$grouppageLocalized = MediaWikiServices::getInstance()->getNamespaceInfo()->
					getCanonicalName( NS_PROJECT ) . ':' . $groupname;
			} else {
				$grouppageLocalized = $msg->text();
			}
			$linkTarget = Title::newFromText( $grouppageLocalized );

			if ( $linkTarget ) {
				$grouppage = $linkRenderer->makeLink(
					$linkTarget,
					$groupnameLocalized
				);
			} else {
				$grouppage = htmlspecialchars( $groupnameLocalized );
			}

			$grouplink = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Listusers' ),
				$this->msg( 'listgrouprights-members' )->text(),
				[],
				[ 'group' => $group ]
			);
			# Add a class when a usergroup contains no members to allow hiding these rows
			$classZero = '';
			$countUsers = SiteStats::numberingroup( $groupname );
			if ( $countUsers == 0 ) {
				$classZero = ' statistics-group-zero';
			}
			$text .= $this->formatRow( $grouppage . ' ' . $grouplink,
				$this->getLanguage()->formatNum( $countUsers ),
				[ 'class' => 'statistics-group-' . Sanitizer::escapeClass( $group ) .
					$classZero ] );
		}

		return $text;
	}

	/**
	 * Conversion of external statistics into an internal representation
	 * Following a ([<header-message>][<item-message>] = number) pattern
	 *
	 * @param array $stats
	 * @return string
	 */
	private function getOtherStats( array $stats ) {
		$return = '';

		foreach ( $stats as $header => $items ) {
			// Identify the structure used
			if ( is_array( $items ) ) {
				// Ignore headers that are recursively set as legacy header
				if ( $header !== 'statistics-header-hooks' ) {
					$return .= $this->formatRowHeader( $header );
				}

				// Collect all items that belong to the same header
				foreach ( $items as $key => $value ) {
					if ( is_array( $value ) ) {
						$name = $value['name'];
						$number = $value['number'];
					} else {
						$name = $this->msg( $key )->parse();
						$number = $value;
					}

					$return .= $this->formatRow(
						$name,
						$this->getLanguage()->formatNum( htmlspecialchars( $number ) ),
						[ 'class' => 'mw-statistics-hook', 'id' => 'mw-' . $key ]
					);
				}
			} else {
				// Create the legacy header only once
				if ( $return === '' ) {
					$return .= $this->formatRowHeader( 'statistics-header-hooks' );
				}

				// Recursively remap the legacy structure
				$return .= $this->getOtherStats( [ 'statistics-header-hooks' =>
					[ $header => $items ] ] );
			}
		}

		return $return;
	}

	/**
	 * Format row header
	 *
	 * @param string $header
	 * @return string
	 */
	private function formatRowHeader( $header ) {
		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', [ 'colspan' => '2' ], $this->msg( $header )->parse() ) .
			Xml::closeElement( 'tr' );
	}

	protected function getGroupName() {
		return 'wiki';
	}
}
