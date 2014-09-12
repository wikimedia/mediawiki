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

/**
 * Special page lists various statistics, including the contents of
 * `site_stats`, plus page view details if enabled
 *
 * @ingroup SpecialPage
 */
class SpecialStatistics extends SpecialPage {
	private $views, $edits, $good, $images, $total, $users,
		$activeUsers = 0;

	public function __construct() {
		parent::__construct( 'Statistics' );
	}

	public function execute( $par ) {
		global $wgMemc, $wgDisableCounters, $wgMiserMode;

		$this->setHeaders();
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

		$this->views = SiteStats::views();
		$this->edits = SiteStats::edits();
		$this->good = SiteStats::articles();
		$this->images = SiteStats::images();
		$this->total = SiteStats::pages();
		$this->users = SiteStats::users();
		$this->activeUsers = SiteStats::activeUsers();
		$this->hook = '';

		# Staticic - views
		$viewsStats = '';
		if ( !$wgDisableCounters ) {
			$viewsStats = $this->getViewsStats();
		}

		# Set active user count
		if ( !$wgMiserMode ) {
			$key = wfMemcKey( 'sitestats', 'activeusers-updated' );
			// Re-calculate the count if the last tally is old...
			if ( !$wgMemc->get( $key ) ) {
				$dbw = wfGetDB( DB_MASTER );
				SiteStatsUpdate::cacheUpdate( $dbw );
				$wgMemc->set( $key, '1', 24 * 3600 ); // don't update for 1 day
			}
		}

		$text = Xml::openElement( 'table', array( 'class' => 'wikitable mw-statistics-table' ) );

		# Statistic - pages
		$text .= $this->getPageStats();

		# Statistic - edits
		$text .= $this->getEditStats();

		# Statistic - users
		$text .= $this->getUserStats();

		# Statistic - usergroups
		$text .= $this->getGroupStats();
		$text .= $viewsStats;

		# Statistic - popular pages
		if ( !$wgDisableCounters && !$wgMiserMode ) {
			$text .= $this->getMostViewedPages();
		}

		# Statistic - other
		$extraStats = array();
		if ( wfRunHooks( 'SpecialStatsAddExtra', array( &$extraStats ) ) ) {
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
	 * @param float $number A statistical number
	 * @param array $trExtraParams Params to table row, see Html::elememt
	 * @param string $descMsg Message key
	 * @param array|string $descMsgParam Message parameters
	 * @return string Table row in HTML format
	 */
	private function formatRow( $text, $number, $trExtraParams = array(),
		$descMsg = '', $descMsgParam = ''
	) {
		if ( $descMsg ) {
			$msg = $this->msg( $descMsg, $descMsgParam );
			if ( $msg->exists() ) {
				$descriptionText = $this->msg( 'parentheses' )->rawParams( $msg->parse() )->escaped();
				$text .= "<br />" . Xml::element( 'small', array( 'class' => 'mw-statistic-desc' ),
					" $descriptionText" );
			}
		}

		return Html::rawElement( 'tr', $trExtraParams,
			Html::rawElement( 'td', array(), $text ) .
			Html::rawElement( 'td', array( 'class' => 'mw-statistics-numbers' ), $number )
		);
	}

	/**
	 * Each of these methods is pretty self-explanatory, get a particular
	 * row for the table of statistics
	 * @return string
	 */
	private function getPageStats() {
		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', array( 'colspan' => '2' ), $this->msg( 'statistics-header-pages' )->parse() ) .
			Xml::closeElement( 'tr' ) .
				$this->formatRow( Linker::linkKnown( SpecialPage::getTitleFor( 'Allpages' ),
					$this->msg( 'statistics-articles' )->parse() ),
					$this->getLanguage()->formatNum( $this->good ),
					array( 'class' => 'mw-statistics-articles' ) ) .
				$this->formatRow( $this->msg( 'statistics-pages' )->parse(),
					$this->getLanguage()->formatNum( $this->total ),
					array( 'class' => 'mw-statistics-pages' ),
					'statistics-pages-desc' ) .
				$this->formatRow( Linker::linkKnown( SpecialPage::getTitleFor( 'Listfiles' ),
					$this->msg( 'statistics-files' )->parse() ),
					$this->getLanguage()->formatNum( $this->images ),
					array( 'class' => 'mw-statistics-files' ) );
	}

	private function getEditStats() {
		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', array( 'colspan' => '2' ), $this->msg( 'statistics-header-edits' )->parse() ) .
			Xml::closeElement( 'tr' ) .
			$this->formatRow( $this->msg( 'statistics-edits' )->parse(),
				$this->getLanguage()->formatNum( $this->edits ),
				array( 'class' => 'mw-statistics-edits' )
			) .
			$this->formatRow( $this->msg( 'statistics-edits-average' )->parse(),
				$this->getLanguage()
					->formatNum( sprintf( '%.2f', $this->total ? $this->edits / $this->total : 0 ) ),
				array( 'class' => 'mw-statistics-edits-average' )
			);
	}

	private function getUserStats() {
		global $wgActiveUserDays;

		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', array( 'colspan' => '2' ), $this->msg( 'statistics-header-users' )->parse() ) .
			Xml::closeElement( 'tr' ) .
			$this->formatRow( $this->msg( 'statistics-users' )->parse(),
				$this->getLanguage()->formatNum( $this->users ),
				array( 'class' => 'mw-statistics-users' )
			) .
			$this->formatRow( $this->msg( 'statistics-users-active' )->parse() . ' ' .
				Linker::linkKnown(
					SpecialPage::getTitleFor( 'Activeusers' ),
					$this->msg( 'listgrouprights-members' )->escaped()
				),
				$this->getLanguage()->formatNum( $this->activeUsers ),
				array( 'class' => 'mw-statistics-users-active' ),
				'statistics-users-active-desc',
				$this->getLanguage()->formatNum( $wgActiveUserDays )
			);
	}

	private function getGroupStats() {
		global $wgGroupPermissions, $wgImplicitGroups;
		$text = '';
		foreach ( $wgGroupPermissions as $group => $permissions ) {
			# Skip generic * and implicit groups
			if ( in_array( $group, $wgImplicitGroups ) || $group == '*' ) {
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
				$grouppageLocalized = MWNamespace::getCanonicalName( NS_PROJECT ) . ':' . $groupname;
			} else {
				$grouppageLocalized = $msg->text();
			}
			$linkTarget = Title::newFromText( $grouppageLocalized );
			$grouppage = Linker::link(
				$linkTarget,
				htmlspecialchars( $groupnameLocalized )
			);
			$grouplink = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Listusers' ),
				$this->msg( 'listgrouprights-members' )->escaped(),
				array(),
				array( 'group' => $group )
			);
			# Add a class when a usergroup contains no members to allow hiding these rows
			$classZero = '';
			$countUsers = SiteStats::numberingroup( $groupname );
			if ( $countUsers == 0 ) {
				$classZero = ' statistics-group-zero';
			}
			$text .= $this->formatRow( $grouppage . ' ' . $grouplink,
				$this->getLanguage()->formatNum( $countUsers ),
				array( 'class' => 'statistics-group-' . Sanitizer::escapeClass( $group ) . $classZero ) );
		}

		return $text;
	}

	private function getViewsStats() {
		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', array( 'colspan' => '2' ), $this->msg( 'statistics-header-views' )->parse() ) .
			Xml::closeElement( 'tr' ) .
				$this->formatRow( $this->msg( 'statistics-views-total' )->parse(),
					$this->getLanguage()->formatNum( $this->views ),
						array( 'class' => 'mw-statistics-views-total' ), 'statistics-views-total-desc' ) .
				$this->formatRow( $this->msg( 'statistics-views-peredit' )->parse(),
					$this->getLanguage()->formatNum( sprintf( '%.2f', $this->edits ?
						$this->views / $this->edits : 0 ) ),
						array( 'class' => 'mw-statistics-views-peredit' ) );
	}

	private function getMostViewedPages() {
		$text = '';
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			'page',
			array(
				'page_namespace',
				'page_title',
				'page_counter',
			),
			array(
				'page_is_redirect' => 0,
				'page_counter > 0',
			),
			__METHOD__,
			array(
				'ORDER BY' => 'page_counter DESC',
				'LIMIT' => 10,
			)
		);

		if ( $res->numRows() > 0 ) {
			$text .= Xml::openElement( 'tr' );
			$text .= Xml::tags(
				'th',
				array( 'colspan' => '2' ),
				$this->msg( 'statistics-mostpopular' )->parse()
			);
			$text .= Xml::closeElement( 'tr' );

			foreach ( $res as $row ) {
				$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );

				if ( $title instanceof Title ) {
					$text .= $this->formatRow( Linker::link( $title ),
						$this->getLanguage()->formatNum( $row->page_counter ) );
				}
			}
			$res->free();
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
					$name = $this->msg( $key )->parse();
					$number = htmlspecialchars( $value );

					$return .= $this->formatRow(
						$name,
						$this->getLanguage()->formatNum( $number ),
						array( 'class' => 'mw-statistics-hook', 'id' => 'mw-' . $key )
					);
				}
			} else {
				// Create the legacy header only once
				if ( $return === '' ) {
					$return .= $this->formatRowHeader( 'statistics-header-hooks' );
				}

				// Recursively remap the legacy structure
				$return .= $this->getOtherStats( array( 'statistics-header-hooks' =>
					array( $header => $items ) ) );
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
			Xml::tags( 'th', array( 'colspan' => '2' ), $this->msg( $header )->parse() ) .
			Xml::closeElement( 'tr' );
	}

	protected function getGroupName() {
		return 'wiki';
	}
}
