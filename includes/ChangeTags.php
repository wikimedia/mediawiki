<?php

if( !defined( 'MEDIAWIKI' ) )
	die;

class ChangeTags {
	static function formatSummaryRow( $tags, $page ) {
		if( !$tags )
			return array( '', array() );

		$classes = array();

		$tags = explode( ',', $tags );
		$displayTags = array();
		foreach( $tags as $tag ) {
			$displayTags[] = Xml::tags(
				'span',
				array( 'class' => 'mw-tag-marker ' .
								Sanitizer::escapeClass( "mw-tag-marker-$tag" ) ),
				self::tagDescription( $tag )
			);
			$classes[] = Sanitizer::escapeClass( "mw-tag-$tag" );
		}

		$markers = '(' . implode( ', ', $displayTags ) . ')';
		$markers = Xml::tags( 'span', array( 'class' => 'mw-tag-markers' ), $markers );
		return array( $markers, $classes );
	}

	static function tagDescription( $tag ) {
		$msg = wfMsgExt( "tag-$tag", 'parseinline' );
		if ( wfEmptyMsg( "tag-$tag", $msg ) ) {
			return htmlspecialchars( $tag );
		}
		return $msg;
	}

	## Basic utility method to add tags to a particular change, given its rc_id, rev_id and/or log_id.
	static function addTags( $tags, $rc_id = null, $rev_id = null, $log_id = null, $params = null ) {
		if ( !is_array( $tags ) ) {
			$tags = array( $tags );
		}

		$tags = array_filter( $tags ); // Make sure we're submitting all tags...

		if( !$rc_id && !$rev_id && !$log_id ) {
			throw new MWException( "At least one of: RCID, revision ID, and log ID MUST be specified when adding a tag to a change!" );
		}

		$dbr = wfGetDB( DB_SLAVE );

		// Might as well look for rcids and so on.
		if( !$rc_id ) {
			$dbr = wfGetDB( DB_MASTER ); // Info might be out of date, somewhat fractionally, on slave.
			if( $log_id ) {
				$rc_id = $dbr->selectField( 'recentchanges', 'rc_id', array( 'rc_logid' => $log_id ), __METHOD__ );
			} elseif( $rev_id ) {
				$rc_id = $dbr->selectField( 'recentchanges', 'rc_id', array( 'rc_this_oldid' => $rev_id ), __METHOD__ );
			}
		} elseif( !$log_id && !$rev_id ) {
			$dbr = wfGetDB( DB_MASTER ); // Info might be out of date, somewhat fractionally, on slave.
			$log_id = $dbr->selectField( 'recentchanges', 'rc_logid', array( 'rc_id' => $rc_id ), __METHOD__ );
			$rev_id = $dbr->selectField( 'recentchanges', 'rc_this_oldid', array( 'rc_id' => $rc_id ), __METHOD__ );
		}

		$tsConds = array_filter( array( 'ts_rc_id' => $rc_id, 'ts_rev_id' => $rev_id, 'ts_log_id' => $log_id ) );

		## Update the summary row.
		$prevTags = $dbr->selectField( 'tag_summary', 'ts_tags', $tsConds, __METHOD__ );
		$prevTags = $prevTags ? $prevTags : '';
		$prevTags = array_filter( explode( ',', $prevTags ) );
		$newTags = array_unique( array_merge( $prevTags, $tags ) );
		sort( $prevTags );
		sort( $newTags );

		if ( $prevTags == $newTags ) {
			// No change.
			return false;
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
			'tag_summary',
			array( 'ts_rev_id', 'ts_rc_id', 'ts_log_id' ),
			array_filter( array_merge( $tsConds, array( 'ts_tags' => implode( ',', $newTags ) ) ) ),
			__METHOD__
		);

		// Insert the tags rows.
		$tagsRows = array();
		foreach( $tags as $tag ) { // Filter so we don't insert NULLs as zero accidentally.
			$tagsRows[] = array_filter(
				array(
					'ct_tag' => $tag,
					'ct_rc_id' => $rc_id,
					'ct_log_id' => $log_id,
					'ct_rev_id' => $rev_id,
					'ct_params' => $params
				)
			);
		}

		$dbw->insert( 'change_tag', $tagsRows, __METHOD__, array( 'IGNORE' ) );

		return true;
	}

	/**
	 * Applies all tags-related changes to a query.
	 * Handles selecting tags, and filtering.
	 * Needs $tables to be set up properly, so we can figure out which join conditions to use.
	 */
	static function modifyDisplayQuery( &$tables, &$fields,  &$conds,
										&$join_conds, &$options, $filter_tag = false ) {
		global $wgRequest, $wgUseTagFilter;

		if( $filter_tag === false ) {
			$filter_tag = $wgRequest->getVal( 'tagfilter' );
		}

		// Figure out which conditions can be done.
		if ( in_array( 'recentchanges', $tables ) ) {
			$join_cond = 'rc_id';
		} elseif( in_array( 'logging', $tables ) ) {
			$join_cond = 'log_id';
		} elseif ( in_array( 'revision', $tables ) ) {
			$join_cond = 'rev_id';
		} else {
			throw new MWException( 'Unable to determine appropriate JOIN condition for tagging.' );
		}

		// JOIN on tag_summary
		$tables[] = 'tag_summary';
		$join_conds['tag_summary'] = array( 'LEFT JOIN', "ts_$join_cond=$join_cond" );
		$fields[] = 'ts_tags';

		if( $wgUseTagFilter && $filter_tag ) {
			// Somebody wants to filter on a tag.
			// Add an INNER JOIN on change_tag

			// FORCE INDEX -- change_tags will almost ALWAYS be the correct query plan.
			global $wgOldChangeTagsIndex;
			$index = $wgOldChangeTagsIndex ? 'ct_tag' : 'change_tag_tag_id';
			$options['USE INDEX'] = array( 'change_tag' => $index );
			unset( $options['FORCE INDEX'] );
			$tables[] = 'change_tag';
			$join_conds['change_tag'] = array( 'INNER JOIN', "ct_$join_cond=$join_cond" );
			$conds['ct_tag'] = $filter_tag;
		}
	}

	/**
	 * If $fullForm is set to false, then it returns an array of (label, form).
	 * If $fullForm is true, it returns an entire form.
	 */
	static function buildTagFilterSelector( $selected='', $fullForm = false /* used to put a full form around the selector */ ) {
		global $wgUseTagFilter;

		if ( !$wgUseTagFilter || !count( self::listDefinedTags() ) )
			return $fullForm ? '' : array();

		global $wgTitle;

		$data = array( wfMsgExt( 'tag-filter', 'parseinline' ), Xml::input( 'tagfilter', 20, $selected ) );

		if ( !$fullForm ) {
			return $data;
		}

		$html = implode( '&#160;', $data );
		$html .= "\n" . Xml::element( 'input', array( 'type' => 'submit', 'value' => wfMsg( 'tag-filter-submit' ) ) );
		$html .= "\n" . Xml::hidden( 'title', $wgTitle-> getPrefixedText() );
		$html = Xml::tags( 'form', array( 'action' => $wgTitle->getLocalURL(), 'method' => 'get' ), $html );

		return $html;
	}

	/** Basically lists defined tags which count even if they aren't applied to anything */
	static function listDefinedTags() {
		// Caching...
		global $wgMemc;
		$key = wfMemcKey( 'valid-tags' );

		if ( $tags = $wgMemc->get( $key ) ) {
			return $tags;
		}

		$emptyTags = array();

		// Some DB stuff
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'valid_tag', 'vt_tag', array(), __METHOD__ );
		foreach ( $res as $row ) {
			$emptyTags[] = $row->vt_tag;
		}

		wfRunHooks( 'ListDefinedTags', array( &$emptyTags ) );

		$emptyTags = array_filter( array_unique( $emptyTags ) );

		// Short-term caching.
		$wgMemc->set( $key, $emptyTags, 300 );
		return $emptyTags;
	}
}
