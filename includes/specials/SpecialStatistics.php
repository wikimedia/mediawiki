<?php

/**
 * Special page lists various statistics, including the contents of
 * `site_stats`, plus page view details if enabled
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * Show the special page
 *
 * @param mixed $par (not used)
 */
class SpecialStatistics extends SpecialPage {
	
	private $views, $edits, $good, $images, $total, $users,
			$activeUsers, $admins, $numJobs = 0;
	
	public function __construct() {
		parent::__construct( 'Statistics' );
	}
	
	public function execute( $par ) {
		global $wgOut, $wgRequest, $wgMessageCache;
		global $wgDisableCounters, $wgMiserMode;
		$wgMessageCache->loadAllMessages();
		
		$this->setHeaders();
	
		$this->views = SiteStats::views();
		$this->edits = SiteStats::edits();
		$this->good = SiteStats::articles();
		$this->images = SiteStats::images();
		$this->total = SiteStats::pages();
		$this->users = SiteStats::users();
		$this->activeUsers = SiteStats::activeUsers();
		$this->admins = SiteStats::numberingroup('sysop');
		$this->numJobs = SiteStats::jobs();
	
		# Staticic - views
		$viewsStats = '';
		if( !$wgDisableCounters ) {
			$viewsStats = $this->getViewsStats();
		}
		
		# Set active user count
		if( !$wgMiserMode ) {
			$dbw = wfGetDB( DB_MASTER );
			SiteStatsUpdate::cacheUpdate( $dbw );
		}
	
		# Do raw output
		if( $wgRequest->getVal( 'action' ) == 'raw' ) {
			$this->doRawOutput();
		}

		$text = Xml::openElement( 'table', array( 'class' => 'mw-statistics-table' ) );

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

		$text .= Xml::closeElement( 'table' );

		# Customizable footer
		$footer = wfMsgExt( 'statistics-footer', array('parseinline') );
		if( !wfEmptyMsg( 'statistics-footer', $footer ) && $footer != '' ) {
			$text .= "\n" . $footer;
		}

		$wgOut->addHTML( $text );
	}

	/**
	 * Format a row
	 * @param string $text description of the row
	 * @param float $number a number
	 * @return string table row in HTML format
	 */
	private function formatRow( $text, $number, $trExtraParams = array(), $descMsg = '' ) {
		global $wgStylePath;
	
		if( $descMsg ) {
			$descriptionText = wfMsg( $descMsg );
			if ( !wfEmptyMsg( $descMsg, $descriptionText ) ) {
				$descriptionText = " ($descriptionText)";
				$text = $text . "<br />" . Xml::element( 'small', array( 'class' => 'mw-statistic-desc'), $descriptionText );
			}
		}
		
		return Xml::openElement( 'tr', $trExtraParams ) .
				Xml::openElement( 'td' ) . $text . Xml::closeElement( 'td' ) .
				Xml::openElement( 'td' ) . $number . Xml::closeElement( 'td' ) .
				Xml::closeElement( 'tr' );
	}
	
	/**
	 * Each of these methods is pretty self-explanatory, get a particular
	 * row for the table of statistics
	 * @return string
	 */
	private function getPageStats() {
		global $wgLang;
		return Xml::tags( 'th', array( 'colspan' => '2' ), wfMsg( 'statistics-header-pages' ) ) .
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
		return Xml::tags( 'th', array( 'colspan' => '2' ), wfMsg( 'statistics-header-edits' ) ) .
				$this->formatRow( wfMsgExt( 'statistics-edits', array( 'parseinline' ) ),
						$wgLang->formatNum( $this->edits ),
						array( 'class' => 'mw-statistics-edits' ) ) .
				$this->formatRow( wfMsgExt( 'statistics-edits-average', array( 'parseinline' ) ),
						$wgLang->formatNum( sprintf( '%.2f', $this->total ? $this->edits / $this->total : 0 ) ),
						array( 'class' => 'mw-statistics-edits-average' ) ) .
				$this->formatRow( wfMsgExt( 'statistics-jobqueue', array( 'parseinline' ) ),
						$wgLang->formatNum( $this->numJobs ),
						array( 'class' => 'mw-statistics-jobqueue' ) );
	}
	private function getUserStats() {
		global $wgLang;
		return Xml::tags( 'th', array( 'colspan' => '2' ), wfMsg( 'statistics-header-users' ) ) .
				$this->formatRow( wfMsgExt( 'statistics-users', array( 'parseinline' ) ),
						$wgLang->formatNum( $this->users ),
						array( 'class' => 'mw-statistics-users' ) ) .
				$this->formatRow( wfMsgExt( 'statistics-users-active', array( 'parseinline' ) ),
						$wgLang->formatNum( $this->activeUsers ),
						array( 'class' => 'mw-statistics-users-active' ),
						'statistics-users-active-desc' );
	}
	private function getGroupStats() {
		global $wgGroupPermissions, $wgImplicitGroups, $wgLang, $wgUser;
		$sk = $wgUser->getSkin();
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
			$grouppage = $sk->makeLink( $grouppageLocalized, $groupnameLocalized );
			$grouplink = $sk->link( SpecialPage::getTitleFor( 'Listusers' ),
				wfMsgHtml( 'listgrouprights-members' ),
				array(),
				array( 'group' => $group ),
				'known' );
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
		return Xml::tags( 'th', array( 'colspan' => '2' ), wfMsg( 'statistics-header-views' ) ) .
					$this->formatRow( wfMsgExt( 'statistics-views-total', array( 'parseinline' ) ),
							$wgLang->formatNum( $this->views ),
							array ( 'class' => 'mw-statistics-views-total' ) ) .
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
				$text .= Xml::tags( 'th', array( 'colspan' => '2' ), wfMsg( 'statistics-mostpopular' ) );
				while( $row = $res->fetchObject() ) {
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
	
	/**
	 * Do the action=raw output for this page. Legacy, but we support
	 * it for backwards compatibility
	 * http://lists.wikimedia.org/pipermail/wikitech-l/2008-August/039202.html
	 */
	private function doRawOutput() {
		global $wgOut;
		$wgOut->disable();
		header( 'Pragma: nocache' );
		echo "total=" . $this->total . ";good=" . $this->good . ";views=" . 
				$this->views . ";edits=" . $this->edits . ";users=" . $this->users . ";";
		echo "activeusers=" . $this->activeUsers . ";admins=" . $this->admins . 
				";images=" . $this->images . ";jobs=" . $this->numJobs . "\n";
		return;
	}
}