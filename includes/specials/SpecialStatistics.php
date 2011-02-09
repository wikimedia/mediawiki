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
		global $wgOut, $wgMemc;
		global $wgDisableCounters, $wgMiserMode;
		
		$this->setHeaders();
	
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
		if( !$wgDisableCounters ) {
			$viewsStats = $this->getViewsStats();
		}
		
		# Set active user count
		if( !$wgMiserMode ) {
			$key = wfMemcKey( 'sitestats', 'activeusers-updated' );
			// Re-calculate the count if the last tally is old...
			if( !$wgMemc->get($key) ) {
				$dbw = wfGetDB( DB_MASTER );
				SiteStatsUpdate::cacheUpdate( $dbw );
				$wgMemc->set( $key, '1', 24*3600 ); // don't update for 1 day
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
		if( !$wgDisableCounters && !$wgMiserMode ) {
			$text .= $this->getMostViewedPages();
		}
		
		# Statistic - other
		$extraStats = array();
		if( wfRunHooks( 'SpecialStatsAddExtra', array( &$extraStats ) ) ) {
			$text .= $this->getOtherStats( $extraStats );
		}

		$text .= Xml::closeElement( 'table' );

		# Customizable footer
		$footer = wfMessage( 'statistics-footer' );
		if ( !$footer->isBlank() ) {
			$text .= "\n" . $footer->parse();
		}

		$wgOut->addHTML( $text );
	}

	/**
	 * Format a row
	 * @param $text  String: description of the row
	 * @param $number  Float: a statistical number
	 * @param $trExtraParams  Array: params to table row, see Html::elememt
	 * @param $descMsg  String: message key
	 * @param $descMsgParam  Array: message params
	 * @return string table row in HTML format
	 */
	private function formatRow( $text, $number, $trExtraParams = array(), $descMsg = '', $descMsgParam = '' ) {
		if( $descMsg ) {
			$msg = wfMessage( $descMsg, $descMsgParam );
			if ( $msg->exists() ) {
				$descriptionText = $msg->parse();
				$text .= "<br />" . Xml::element( 'small', array( 'class' => 'mw-statistic-desc'), 
					" ($descriptionText)" );
			}
		}
		return
		Html::rawElement( 'tr', $trExtraParams,
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
		global $wgLang;
		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', array( 'colspan' => '2' ), wfMsgExt( 'statistics-header-pages', array( 'parseinline' ) ) ) .
			Xml::closeElement( 'tr' ) .
				$this->formatRow( wfMsgExt( 'statistics-articles', array( 'parseinline' ) ),
						$wgLang->formatNum( $this->good ),
						array( 'class' => 'mw-statistics-articles' ) ) .
				$this->formatRow( wfMsgExt( 'statistics-pages', array( 'parseinline' ) ),
						$wgLang->formatNum( $this->total ),
						array( 'class' => 'mw-statistics-pages' ),
						'statistics-pages-desc' ) .
				$this->formatRow( wfMsgExt( 'statistics-files', array( 'parseinline' ) ),
						$wgLang->formatNum( $this->images ),
						array( 'class' => 'mw-statistics-files' ) );
	}
	private function getEditStats() {
		global $wgLang;
		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', array( 'colspan' => '2' ), wfMsgExt( 'statistics-header-edits', array( 'parseinline' ) ) ) .
			Xml::closeElement( 'tr' ) .
				$this->formatRow( wfMsgExt( 'statistics-edits', array( 'parseinline' ) ),
						$wgLang->formatNum( $this->edits ),
						array( 'class' => 'mw-statistics-edits' ) ) .
				$this->formatRow( wfMsgExt( 'statistics-edits-average', array( 'parseinline' ) ),
						$wgLang->formatNum( sprintf( '%.2f', $this->total ? $this->edits / $this->total : 0 ) ),
						array( 'class' => 'mw-statistics-edits-average' ) );
	}

	private function getUserStats() {
		global $wgLang, $wgUser, $wgActiveUserDays;
		$sk = $wgUser->getSkin();
		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', array( 'colspan' => '2' ), wfMsgExt( 'statistics-header-users', array( 'parseinline' ) ) ) .
			Xml::closeElement( 'tr' ) .
				$this->formatRow( wfMsgExt( 'statistics-users', array( 'parseinline' ) ),
						$wgLang->formatNum( $this->users ),
						array( 'class' => 'mw-statistics-users' ) ) .
				$this->formatRow( wfMsgExt( 'statistics-users-active', array( 'parseinline' ) ) . ' ' .
							$sk->link(
								SpecialPage::getTitleFor( 'Activeusers' ),
								wfMsgHtml( 'listgrouprights-members' ),
								array(),
								array(),
								'known'
							),
						$wgLang->formatNum( $this->activeUsers ),
						array( 'class' => 'mw-statistics-users-active' ),
						'statistics-users-active-desc',
						$wgLang->formatNum( $wgActiveUserDays ) );
	}

	private function getGroupStats() {
		global $wgGroupPermissions, $wgImplicitGroups, $wgLang, $wgUser;
		$sk = $wgUser->getSkin();
		$text = '';
		foreach( $wgGroupPermissions as $group => $permissions ) {
			# Skip generic * and implicit groups
			if ( in_array( $group, $wgImplicitGroups ) || $group == '*' ) {
				continue;
			}
			$groupname = htmlspecialchars( $group );
			$msg = wfMsg( 'group-' . $groupname );
			if ( wfEmptyMsg( 'group-' . $groupname, $msg ) || $msg == '' ) {
				$groupnameLocalized = $groupname;
			} else {
				$groupnameLocalized = $msg;
			}
			$msg = wfMsgForContent( 'grouppage-' . $groupname );
			if ( wfEmptyMsg( 'grouppage-' . $groupname, $msg ) || $msg == '' ) {
				$grouppageLocalized = MWNamespace::getCanonicalName( NS_PROJECT ) . ':' . $groupname;
			} else {
				$grouppageLocalized = $msg;
			}
			$linkTarget = Title::newFromText( $grouppageLocalized );
			$grouppage = $sk->link(
				$linkTarget,
				htmlspecialchars( $groupnameLocalized )
			);
			$grouplink = $sk->link(
				SpecialPage::getTitleFor( 'Listusers' ),
				wfMsgHtml( 'listgrouprights-members' ),
				array(),
				array( 'group' => $group ),
				'known'
			);
			# Add a class when a usergroup contains no members to allow hiding these rows
			$classZero = '';
			$countUsers = SiteStats::numberingroup( $groupname );
			if( $countUsers == 0 ) {
				$classZero = ' statistics-group-zero';
			}
			$text .= $this->formatRow( $grouppage . ' ' . $grouplink,
				$wgLang->formatNum( $countUsers ),
				array( 'class' => 'statistics-group-' . Sanitizer::escapeClass( $group ) . $classZero )  );
		}
		return $text;
	}

	private function getViewsStats() {
		global $wgLang;
		return Xml::openElement( 'tr' ) .
			Xml::tags( 'th', array( 'colspan' => '2' ), wfMsgExt( 'statistics-header-views', array( 'parseinline' ) ) ) .
			Xml::closeElement( 'tr' ) .
				$this->formatRow( wfMsgExt( 'statistics-views-total', array( 'parseinline' ) ),
					$wgLang->formatNum( $this->views ),
						array ( 'class' => 'mw-statistics-views-total' ), 'statistics-views-total-desc' ) .
				$this->formatRow( wfMsgExt( 'statistics-views-peredit', array( 'parseinline' ) ),
					$wgLang->formatNum( sprintf( '%.2f', $this->edits ? 
						$this->views / $this->edits : 0 ) ),
						array ( 'class' => 'mw-statistics-views-peredit' ) );
	}

	private function getMostViewedPages() {
		global $wgLang, $wgUser;
		$text = '';
		$dbr = wfGetDB( DB_SLAVE );
		$sk = $wgUser->getSkin();
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
			if( $res->numRows() > 0 ) {
				$text .= Xml::openElement( 'tr' );
				$text .= Xml::tags( 'th', array( 'colspan' => '2' ), wfMsgExt( 'statistics-mostpopular', array( 'parseinline' ) ) );
				$text .= Xml::closeElement( 'tr' );
				foreach ( $res as $row ) {
					$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
					if( $title instanceof Title ) {
						$text .= $this->formatRow( $sk->link( $title ),
								$wgLang->formatNum( $row->page_counter ) );
	
					}
				}
				$res->free();
			}
		return $text;
	}

	private function getOtherStats( $stats ) {
		global $wgLang;

		if ( !count( $stats ) )
			return '';

		$return = Xml::openElement( 'tr' ) .
			Xml::tags( 'th', array( 'colspan' => '2' ), wfMsgExt( 'statistics-header-hooks', array( 'parseinline' ) ) ) .
			Xml::closeElement( 'tr' );
			
		foreach( $stats as $name => $number ) {
			$name = htmlspecialchars( $name );
			$number = htmlspecialchars( $number );
			
			$return .= $this->formatRow( $name, $wgLang->formatNum( $number ), array( 'class' => 'mw-statistics-hook' ) );
		}
		
		return $return;
	}
}
