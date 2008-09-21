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
function wfSpecialStatistics( $par = '' ) {
	global $wgOut, $wgLang, $wgRequest, $wgUser, $wgContLang;
	global $wgDisableCounters, $wgMiserMode, $wgImplicitGroups, $wgGroupPermissions;
	$sk = $wgUser->getSkin();
	$dbr = wfGetDB( DB_SLAVE );

	$views = SiteStats::views();
	$edits = SiteStats::edits();
	$good = SiteStats::articles();
	$images = SiteStats::images();
	$total = SiteStats::pages();
	$users = SiteStats::users();
	$activeUsers = SiteStats::activeUsers();
	$admins = SiteStats::numberingroup('sysop');
	$numJobs = SiteStats::jobs();

	# Staticic - views
	$viewsStats = '';
	if( !$wgDisableCounters ) {
		$viewsStats = Xml::tags( 'th', array( 'colspan' => '2' ), wfMsg( 'statistics-header-views' ) ) .
				formatRow( wfMsgExt( 'statistics-views-total', array( 'parseinline' ) ),
						$wgLang->formatNum( $views ) ) .
				formatRow( wfMsgExt( 'statistics-views-peredit', array( 'parseinline' ) ),
						$wgLang->formatNum( sprintf( '%.2f', $edits ? $views / $edits : 0 ) ) );
	}
	# Set active user count
	if( !$wgMiserMode ) {
		$dbw = wfGetDB( DB_MASTER );
		SiteStatsUpdate::cacheUpdate( $dbw );
	}

	if( $wgRequest->getVal( 'action' ) == 'raw' ) {
		# Depreciated, kept for backwards compatibility
		# http://lists.wikimedia.org/pipermail/wikitech-l/2008-August/039202.html
		$wgOut->disable();
		header( 'Pragma: nocache' );
		echo "total=$total;good=$good;views=$views;edits=$edits;users=$users;";
		echo "activeusers=$activeUsers;admins=$admins;images=$images;jobs=$numJobs\n";
		return;
	} else {
		$text = Xml::openElement( 'table', array( 'class' => 'mw-statistics-table' ) ) .
			# Statistic - pages
			Xml::tags( 'th', array( 'colspan' => '2' ), wfMsg( 'statistics-header-pages' ) ) .
			formatRow( wfMsgExt( 'statistics-articles', array( 'parseinline' ) ),
					$wgLang->formatNum( $good ) ) .
			formatRow( wfMsgExt( 'statistics-pages', array( 'parseinline' ) . '</div>' ),
					$wgLang->formatNum( $total ), NULL, 'statistics-pages-tooltip' ) .
			formatRow( wfMsgExt( 'statistics-files', array( 'parseinline' ) ),
					$wgLang->formatNum( $images ) ) .

			# Statistic - edits
			Xml::tags( 'th', array( 'colspan' => '2' ), wfMsg( 'statistics-header-edits' ) ) .
			formatRow( wfMsgExt( 'statistics-edits', array( 'parseinline' ) ),
					$wgLang->formatNum( $edits ) ) .
			formatRow( wfMsgExt( 'statistics-edits-average', array( 'parseinline' ) ),
					$wgLang->formatNum( sprintf( '%.2f', $total ? $edits / $total : 0 ) ) ) .
			formatRow( wfMsgExt( 'statistics-jobqueue', array( 'parseinline' ) ),
					$wgLang->formatNum( $numJobs ) ) .

			# Statistic - users
			Xml::tags( 'th', array( 'colspan' => '2' ), wfMsg( 'statistics-header-users' ) ) .
			formatRow( wfMsgExt( 'statistics-users', array( 'parseinline' ) ),
					$wgLang->formatNum( $users ) ) .
			formatRow( wfMsgExt( 'statistics-users-active', array( 'parseinline' ) ),
					$wgLang->formatNum( $activeUsers ), NULL, 'statistics-users-active-tooltip' );

		# Statistic - usergroups
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
			$text .= formatRow( $grouppage . ' ' . $grouplink,
				$wgLang->formatNum( SiteStats::numberingroup( $groupname ) ),
				' class="statistics-group-' . Sanitizer::escapeClass( $group ) . '"' );
		}
	}
	$text .= $viewsStats;

	# Statistic - popular pages
	if( !$wgDisableCounters && !$wgMiserMode ) {
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
					$text .= formatRow( $sk->link( $title ),
							$wgLang->formatNum( $row->page_counter ) );

				}
			}
			$res->free();
		}
	}

	$text .= Xml::closeElement( 'table' );

	# Customizable footer
	$footer = wfMsgExt( 'statistics-footer', array('parseinline') );
	if( !wfEmptyMsg( 'statistics-footer', $footer ) && $footer != '' ) {
		$text .= "\n" . $footer;
	}

	$wgOut->addHtml( $text );
}

/**
 * Format a row
 *
 * @param string $text description of the row
 * @param float $number a number
 * @return string table row in HTML format
 */
function formatRow( $text, $number, $trExtraParams = '', $tooltip = '' ) {
	if( $tooltip ) {
		$text = '<div title="' . wfMsg( $tooltip ) . '">' . $text . '</div>';
	}

	return "<tr{$trExtraParams}>
			<td>" .
				$text .
			'</td>
			<td class="mw-statistics-numbers">' .
				 $number .
			'</td>
		</tr>';
}
