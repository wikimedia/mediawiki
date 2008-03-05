<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 * Main function
 */
function wfSpecialSpecialpages() {
	global $wgOut, $wgUser, $wgMessageCache;

	$wgMessageCache->loadAllMessages();

	$wgOut->setRobotpolicy( 'noindex,nofollow' );  # Is this really needed?

	# Read the special pages
	$pagesRegular = SpecialPage::getRegularPages();
	$pagesRestricted = SpecialPage::getRestrictedPages();
	if( count( $pagesRegular ) == 0 && count( $pagesRestricted ) == 0 ) {
		# Yeah, that was pointless. Thanks for coming.
		return;
	}

	# Put regular and restricted special pages into sortable arrays
	$unsortedPagesRegular = wfSpecialSpecialpages_read( $pagesRegular );
	$unsortedPagesRestricted = wfSpecialSpecialpages_read( $pagesRestricted );

	# Read the template
	$tpl = wfMsg( 'specialpages-tpl' );
	$newSpecialPage = '';

	# Parse the template and create a localized wikitext page
	foreach ( explode( "\n", $tpl ) as $line ) {
		# Look for 'special:' in the line
		$pos = strpos( strtolower( $line ), 'special:' );
		if( $pos >= 0 ) {

			# Preserve the line start
			$lineStart = ( $pos > 0 ) ? substr( $line, 0, $pos ) : '';

			# Get the canonical special page name
			$canonical = strtolower( trim( substr( $line, $pos + strlen( 'special:' ) ) ) );

			# Check if it is a valid regular special page name
			# Restricted pages will be added at the end
			if( isset( $unsortedPagesRegular[$canonical] ) ) {
				# Add a new line
				$newSpecialPage .= $lineStart . "[[special:" . $canonical . "|" . $unsortedPagesRegular[$canonical] . "]]\n";
				# Delete this regular special page from the array to avoid double output
				unset( $unsortedPagesRegular[$canonical] );
			} else {
				# Ok, no valid special page, but add the line to the output
				$newSpecialPage .= $line . "\n";
			}
		} else {
			$newSpecialPage .= $line . "\n";
		}
	}

	# Add the rest
	$newSpecialPage .= wfSpecialSpecialpages_out( 'specialpages-other', $unsortedPagesRegular );
	# Add the restricted special pages
	$newSpecialPage .= wfSpecialSpecialpages_out( 'restrictedpheading', $unsortedPagesRestricted );

	# Output the customized special pages list
	$wgOut->addWikiText( $newSpecialPage );

	if ( $wgUser->isAllowed( 'editinterface' ) ) {
		# Output a nice link to edit the template
		$wgOut->addHtml( wfSpecialSpecialpages_edit() );
	}
}

/**
 * Output the rest special pages as wikitext
 * @param string $heading Message name for the heading
 * @param array $sortedPages List of other special pages
 * @return string $rest Wikitext
 */
function wfSpecialSpecialpages_out( $heading, $pages ) {
	global $wgOut, $wgSortSpecialPages;

	if( count( $pages ) == 0 ) {
		# Yeah, that was pointless. Thanks for coming.
		return;
	}

	# Sort
	if ( $wgSortSpecialPages ) {
		asort( $pages );
	}

	# Now output the rest special pages as wikitext
	$rest = '== ' . wfMsgExt( $heading, array( 'parseinline' ) ) . " ==\n";
	foreach ( $pages as $title => $desc ) {
		$rest .= "* [[special:" . $title . "|" . $desc . "]]\n";
	}
	return $rest;
}

/**
 * Create array with descriptions and names of special pages
 * @param array $pagelist List of special pages
 * @return array $unsortedPagesList Wikitext
 */
function wfSpecialSpecialpages_read( $pagelist ) {
	$unsortedPagesList = array();
	foreach ( $pagelist as $page ) {
		if ( $page->isListed() ) {
			$name = strtolower( $page->getName() );
			$unsortedPagesList[$name] = $page->getDescription();
		}
	}
	return $unsortedPagesList;
}

/**
 * Build edit link to MediaWiki:specialpages-tpl
 *
 * @return string
 */
function wfSpecialSpecialpages_edit() {
	global $wgUser, $wgContLang;
	$align = $wgContLang->isRtl() ? 'left' : 'right';
	$skin = $wgUser->getSkin();
	$link = $skin->makeLink ( 'MediaWiki:specialpages-tpl', wfMsgHtml( 'specialpages-edit' ) );
	return '<p style="float:' . $align . ';" class="mw-specialspecialpages-edit">' . $link . '</p>';
}
