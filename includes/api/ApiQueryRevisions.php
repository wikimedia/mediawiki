<?php
/**
 * API for MediaWiki 1.8+
 *
 * Created on Sep 7, 2006
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiQueryBase.php' );
}

/**
 * A query action to enumerate revisions of a given page, or show top revisions of multiple pages.
 * Various pieces of information may be shown - flags, comments, and the actual wiki markup of the rev.
 * In the enumeration mode, ranges of revisions may be requested and filtered.
 *
 * @ingroup API
 */
class ApiQueryRevisions extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'rv' );
	}

	private $fld_ids = false, $fld_flags = false, $fld_timestamp = false, $fld_size = false,
			$fld_comment = false, $fld_parsedcomment = false, $fld_user = false, $fld_userid = false,
			$fld_content = false, $fld_tags = false;

	protected function getTokenFunctions() {
		// tokenname => function
		// function prototype is func($pageid, $title, $rev)
		// should return token or false

		// Don't call the hooks twice
		if ( isset( $this->tokenFunctions ) ) {
			return $this->tokenFunctions;
		}

		// If we're in JSON callback mode, no tokens can be obtained
		if ( !is_null( $this->getMain()->getRequest()->getVal( 'callback' ) ) ) {
			return array();
		}

		$this->tokenFunctions = array(
			'rollback' => array( 'ApiQueryRevisions', 'getRollbackToken' )
		);
		wfRunHooks( 'APIQueryRevisionsTokens', array( &$this->tokenFunctions ) );
		return $this->tokenFunctions;
	}

	public static function getRollbackToken( $pageid, $title, $rev ) {
		global $wgUser;
		if ( !$wgUser->isAllowed( 'rollback' ) ) {
			return false;
		}
		return $wgUser->editToken( array( $title->getPrefixedText(),
						$rev->getUserText() ) );
	}

	public function execute() {
		$params = $this->extractRequestParams( false );

		// If any of those parameters are used, work in 'enumeration' mode.
		// Enum mode can only be used when exactly one page is provided.
		// Enumerating revisions on multiple pages make it extremely
		// difficult to manage continuations and require additional SQL indexes
		$enumRevMode = ( !is_null( $params['user'] ) || !is_null( $params['excludeuser'] ) ||
				!is_null( $params['limit'] ) || !is_null( $params['startid'] ) ||
				!is_null( $params['endid'] ) || $params['dir'] === 'newer' ||
				!is_null( $params['start'] ) || !is_null( $params['end'] ) );


		$pageSet = $this->getPageSet();
		$pageCount = $pageSet->getGoodTitleCount();
		$revCount = $pageSet->getRevisionCount();

		// Optimization -- nothing to do
		if ( $revCount === 0 && $pageCount === 0 ) {
			return;
		}

		if ( $revCount > 0 && $enumRevMode ) {
			$this->dieUsage( 'The revids= parameter may not be used with the list options (limit, startid, endid, dirNewer, start, end).', 'revids' );
		}

		if ( $pageCount > 1 && $enumRevMode ) {
			$this->dieUsage( 'titles, pageids or a generator was used to supply multiple pages, but the limit, startid, endid, dirNewer, user, excludeuser, start and end parameters may only be used on a single page.', 'multpages' );
		}

		$this->diffto = $this->difftotext = null;
		if ( !is_null( $params['difftotext'] ) ) {
			$this->difftotext = $params['difftotext'];
		} elseif ( !is_null( $params['diffto'] ) ) {
			if ( $params['diffto'] == 'cur' ) {
				$params['diffto'] = 0;
			}
			if ( ( !ctype_digit( $params['diffto'] ) || $params['diffto'] < 0 )
					&& $params['diffto'] != 'prev' && $params['diffto'] != 'next' )
			{
				$this->dieUsage( 'rvdiffto must be set to a non-negative number, "prev", "next" or "cur"', 'diffto' );
			}
			// Check whether the revision exists and is readable,
			// DifferenceEngine returns a rather ambiguous empty
			// string if that's not the case
			if ( $params['diffto'] != 0 ) {
				$difftoRev = Revision::newFromID( $params['diffto'] );
				if ( !$difftoRev ) {
					$this->dieUsageMsg( array( 'nosuchrevid', $params['diffto'] ) );
				}
				if ( !$difftoRev->userCan( Revision::DELETED_TEXT ) ) {
					$this->setWarning( "Couldn't diff to r{$difftoRev->getID()}: content is hidden" );
					$params['diffto'] = null;
				}
			}
			$this->diffto = $params['diffto'];
		}

		$db = $this->getDB();
		$this->addTables( array( 'page', 'revision' ) );
		$this->addFields( Revision::selectFields() );
		$this->addWhere( 'page_id = rev_page' );

		$prop = array_flip( $params['prop'] );

		// Optional fields
		$this->fld_ids = isset ( $prop['ids'] );
		// $this->addFieldsIf('rev_text_id', $this->fld_ids); // should this be exposed?
		$this->fld_flags = isset ( $prop['flags'] );
		$this->fld_timestamp = isset ( $prop['timestamp'] );
		$this->fld_comment = isset ( $prop['comment'] );
		$this->fld_parsedcomment = isset ( $prop['parsedcomment'] );
		$this->fld_size = isset ( $prop['size'] );
		$this->fld_userid = isset( $prop['userid'] );
		$this->fld_user = isset ( $prop['user'] );
		$this->token = $params['token'];

		// Possible indexes used
		$index = array();

		if ( !is_null( $this->token ) || $pageCount > 0 ) {
			$this->addFields( Revision::selectPageFields() );
		}

		if ( isset( $prop['tags'] ) ) {
			$this->fld_tags = true;
			$this->addTables( 'tag_summary' );
			$this->addJoinConds( array( 'tag_summary' => array( 'LEFT JOIN', array( 'rev_id=ts_rev_id' ) ) ) );
			$this->addFields( 'ts_tags' );
		}

		if ( !is_null( $params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( array( 'change_tag' => array( 'INNER JOIN', array( 'rev_id=ct_rev_id' ) ) ) );
			$this->addWhereFld( 'ct_tag' , $params['tag'] );
			global $wgOldChangeTagsIndex;
			$index['change_tag'] = $wgOldChangeTagsIndex ? 'ct_tag' : 'change_tag_tag_id';
		}

		if ( isset( $prop['content'] ) || !is_null( $this->difftotext ) ) {
			// For each page we will request, the user must have read rights for that page
			foreach ( $pageSet->getGoodTitles() as $title ) {
				if ( !$title->userCanRead() ) {
					$this->dieUsage(
						'The current user is not allowed to read ' . $title->getPrefixedText(),
						'accessdenied' );
				}
			}

			$this->addTables( 'text' );
			$this->addWhere( 'rev_text_id=old_id' );
			$this->addFields( 'old_id' );
			$this->addFields( Revision::selectTextFields() );

			$this->fld_content = isset( $prop['content'] );

			$this->expandTemplates = $params['expandtemplates'];
			$this->generateXML = $params['generatexml'];
			if ( isset( $params['section'] ) ) {
				$this->section = $params['section'];
			} else {
				$this->section = false;
			}
		}

		$userMax = ( $this->fld_content ? ApiBase::LIMIT_SML1 : ApiBase::LIMIT_BIG1 );
		$botMax  = ( $this->fld_content ? ApiBase::LIMIT_SML2 : ApiBase::LIMIT_BIG2 );
		$limit = $params['limit'];
		if ( $limit == 'max' ) {
			$limit = $this->getMain()->canApiHighLimits() ? $botMax : $userMax;
			$this->getResult()->setParsedLimit( $this->getModuleName(), $limit );
		}

		if ( $enumRevMode ) {
			// This is mostly to prevent parameter errors (and optimize SQL?)
			if ( !is_null( $params['startid'] ) && !is_null( $params['start'] ) ) {
				$this->dieUsage( 'start and startid cannot be used together', 'badparams' );
			}

			if ( !is_null( $params['endid'] ) && !is_null( $params['end'] ) ) {
				$this->dieUsage( 'end and endid cannot be used together', 'badparams' );
			}

			if ( !is_null( $params['user'] ) && !is_null( $params['excludeuser'] ) ) {
				$this->dieUsage( 'user and excludeuser cannot be used together', 'badparams' );
			}

			// This code makes an assumption that sorting by rev_id and rev_timestamp produces
			// the same result. This way users may request revisions starting at a given time,
			// but to page through results use the rev_id returned after each page.
			// Switching to rev_id removes the potential problem of having more than
			// one row with the same timestamp for the same page.
			// The order needs to be the same as start parameter to avoid SQL filesort.
			if ( is_null( $params['startid'] ) && is_null( $params['endid'] ) ) {
				$this->addWhereRange( 'rev_timestamp', $params['dir'],
					$params['start'], $params['end'] );
			} else {
				$this->addWhereRange( 'rev_id', $params['dir'],
					$params['startid'], $params['endid'] );
				// One of start and end can be set
				// If neither is set, this does nothing
				$this->addWhereRange( 'rev_timestamp', $params['dir'],
					$params['start'], $params['end'], false );
			}

			// must manually initialize unset limit
			if ( is_null( $limit ) ) {
				$limit = 10;
			}
			$this->validateLimit( 'limit', $limit, 1, $userMax, $botMax );

			// There is only one ID, use it
			$ids = array_keys( $pageSet->getGoodTitles() );
			$this->addWhereFld( 'rev_page', reset( $ids ) );

			if ( !is_null( $params['user'] ) ) {
				$this->addWhereFld( 'rev_user_text', $params['user'] );
			} elseif ( !is_null( $params['excludeuser'] ) ) {
				$this->addWhere( 'rev_user_text != ' .
					$db->addQuotes( $params['excludeuser'] ) );
			}
			if ( !is_null( $params['user'] ) || !is_null( $params['excludeuser'] ) ) {
				// Paranoia: avoid brute force searches (bug 17342)
				$this->addWhere( $db->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0' );
			}
		} elseif ( $revCount > 0 ) {
			$max = $this->getMain()->canApiHighLimits() ? $botMax : $userMax;
			$revs = $pageSet->getRevisionIDs();
			if ( self::truncateArray( $revs, $max ) ) {
				$this->setWarning( "Too many values supplied for parameter 'revids': the limit is $max" );
			}

			// Get all revision IDs
			$this->addWhereFld( 'rev_id', array_keys( $revs ) );

			if ( !is_null( $params['continue'] ) ) {
				$this->addWhere( "rev_id >= '" . intval( $params['continue'] ) . "'" );
			}
			$this->addOption( 'ORDER BY', 'rev_id' );

			// assumption testing -- we should never get more then $revCount rows.
			$limit = $revCount;
		} elseif ( $pageCount > 0 ) {
			$max = $this->getMain()->canApiHighLimits() ? $botMax : $userMax;
			$titles = $pageSet->getGoodTitles();
			if ( self::truncateArray( $titles, $max ) ) {
				$this->setWarning( "Too many values supplied for parameter 'titles': the limit is $max" );
			}

			// When working in multi-page non-enumeration mode,
			// limit to the latest revision only
			$this->addWhere( 'page_id=rev_page' );
			$this->addWhere( 'page_latest=rev_id' );

			// Get all page IDs
			$this->addWhereFld( 'page_id', array_keys( $titles ) );
			// Every time someone relies on equality propagation, god kills a kitten :)
			$this->addWhereFld( 'rev_page', array_keys( $titles ) );

			if ( !is_null( $params['continue'] ) ) {
				$cont = explode( '|', $params['continue'] );
				if ( count( $cont ) != 2 ) {
					$this->dieUsage( 'Invalid continue param. You should pass the original ' .
							'value returned by the previous query', '_badcontinue' );
				}
				$pageid = intval( $cont[0] );
				$revid = intval( $cont[1] );
				$this->addWhere(
					"rev_page > '$pageid' OR " .
					"(rev_page = '$pageid' AND " .
					"rev_id >= '$revid')"
				);
			}
			$this->addOption( 'ORDER BY', 'rev_page, rev_id' );

			// assumption testing -- we should never get more then $pageCount rows.
			$limit = $pageCount;
		} else {
			ApiBase::dieDebug( __METHOD__, 'param validation?' );
		}

		$this->addOption( 'LIMIT', $limit + 1 );
		$this->addOption( 'USE INDEX', $index );

		$count = 0;
		$res = $this->select( __METHOD__ );

		foreach ( $res as $row ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				if ( !$enumRevMode ) {
					ApiBase::dieDebug( __METHOD__, 'Got more rows then expected' ); // bug report
				}
				$this->setContinueEnumParameter( 'startid', intval( $row->rev_id ) );
				break;
			}

			$fit = $this->addPageSubItem( $row->rev_page, $this->extractRowInfo( $row ), 'rev' );
			if ( !$fit ) {
				if ( $enumRevMode ) {
					$this->setContinueEnumParameter( 'startid', intval( $row->rev_id ) );
				} elseif ( $revCount > 0 ) {
					$this->setContinueEnumParameter( 'continue', intval( $row->rev_id ) );
				} else {
					$this->setContinueEnumParameter( 'continue', intval( $row->rev_page ) .
						'|' . intval( $row->rev_id ) );
				}
				break;
			}
		}
	}

	private function extractRowInfo( $row ) {
		$revision = new Revision( $row );
		$title = $revision->getTitle();
		$vals = array();

		if ( $this->fld_ids ) {
			$vals['revid'] = intval( $revision->getId() );
			// $vals['oldid'] = intval( $row->rev_text_id ); // todo: should this be exposed?
			if ( !is_null( $revision->getParentId() ) ) {
				$vals['parentid'] = intval( $revision->getParentId() );
			}
		}

		if ( $this->fld_flags && $revision->isMinor() ) {
			$vals['minor'] = '';
		}

		if ( $this->fld_user || $this->fld_userid ) {
			if ( $revision->isDeleted( Revision::DELETED_USER ) ) {
				$vals['userhidden'] = '';
			} else {
				if ( $this->fld_user ) {
					$vals['user'] = $revision->getUserText();
				}
				if ( $this->fld_userid ) {
					$user = User::newFromText( $revision->getUserText() );
					$vals['userid'] = $user->getId();
				}
				if ( !$revision->getUser() ) {
					$vals['anon'] = '';
				}
			}
		}

		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $revision->getTimestamp() );
		}

		if ( $this->fld_size && !is_null( $revision->getSize() ) ) {
			$vals['size'] = intval( $revision->getSize() );
		}

		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			if ( $revision->isDeleted( Revision::DELETED_COMMENT ) ) {
				$vals['commenthidden'] = '';
			} else {
				$comment = $revision->getComment();

				if ( $this->fld_comment ) {
					$vals['comment'] = $comment;
				}

				if ( $this->fld_parsedcomment ) {
					global $wgUser;
					$vals['parsedcomment'] = $wgUser->getSkin()->formatComment( $comment, $title );
				}
			}
		}

		if ( $this->fld_tags ) {
			if ( $row->ts_tags ) {
				$tags = explode( ',', $row->ts_tags );
				$this->getResult()->setIndexedTagName( $tags, 'tag' );
				$vals['tags'] = $tags;
			} else {
				$vals['tags'] = array();
			}
		}

		if ( !is_null( $this->token ) ) {
			$tokenFunctions = $this->getTokenFunctions();
			foreach ( $this->token as $t ) {
				$val = call_user_func( $tokenFunctions[$t], $title->getArticleID(), $title, $revision );
				if ( $val === false ) {
					$this->setWarning( "Action '$t' is not allowed for the current user" );
				} else {
					$vals[$t . 'token'] = $val;
				}
			}
		}

		$text = null;
		global $wgParser;
		if ( $this->fld_content || !is_null( $this->difftotext ) ) {
			$text = $revision->getText();
			// Expand templates after getting section content because
			// template-added sections don't count and Parser::preprocess()
			// will have less input
			if ( $this->section !== false ) {
				$text = $wgParser->getSection( $text, $this->section, false );
				if ( $text === false ) {
					$this->dieUsage( "There is no section {$this->section} in r" . $revision->getId(), 'nosuchsection' );
				}
			}
		}
		if ( $this->fld_content && !$revision->isDeleted( Revision::DELETED_TEXT ) ) {
			if ( $this->generateXML ) {
				$wgParser->startExternalParse( $title, new ParserOptions(), OT_PREPROCESS );
				$dom = $wgParser->preprocessToDom( $text );
				if ( is_callable( array( $dom, 'saveXML' ) ) ) {
					$xml = $dom->saveXML();
				} else {
					$xml = $dom->__toString();
				}
				$vals['parsetree'] = $xml;

			}
			if ( $this->expandTemplates ) {
				$text = $wgParser->preprocess( $text, $title, new ParserOptions() );
			}
			ApiResult::setContent( $vals, $text );
		} elseif ( $this->fld_content ) {
			$vals['texthidden'] = '';
		}

		if ( !is_null( $this->diffto ) || !is_null( $this->difftotext ) ) {
			global $wgAPIMaxUncachedDiffs;
			static $n = 0; // Number of uncached diffs we've had
			if ( $n < $wgAPIMaxUncachedDiffs ) {
				$vals['diff'] = array();
				if ( !is_null( $this->difftotext ) ) {
					$engine = new DifferenceEngine( $title );
					$engine->setText( $text, $this->difftotext );
				} else {
					$engine = new DifferenceEngine( $title, $revision->getID(), $this->diffto );
					$vals['diff']['from'] = $engine->getOldid();
					$vals['diff']['to'] = $engine->getNewid();
				}
				$difftext = $engine->getDiffBody();
				ApiResult::setContent( $vals['diff'], $difftext );
				if ( !$engine->wasCacheHit() ) {
					$n++;
				}
			} else {
				$vals['diff']['notcached'] = '';
			}
		}
		return $vals;
	}

	public function getCacheMode( $params ) {
		if ( isset( $params['token'] ) ) {
			return 'private';
		}
		if ( !is_null( $params['prop'] ) && in_array( 'parsedcomment', $params['prop'] ) ) {
			// formatComment() calls wfMsg() among other things
			return 'anon-public-user-private';
		}
		return 'public';
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'ids|timestamp|flags|comment|user',
				ApiBase::PARAM_TYPE => array(
					'ids',
					'flags',
					'timestamp',
					'user',
					'userid',
					'size',
					'comment',
					'parsedcomment',
					'content',
					'tags'
				)
			),
			'limit' => array(
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'startid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'endid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_TYPE => array(
					'newer',
					'older'
				)
			),
			'user' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'excludeuser' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'tag' => null,
			'expandtemplates' => false,
			'generatexml' => false,
			'section' => null,
			'token' => array(
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenFunctions() ),
				ApiBase::PARAM_ISMULTI => true
			),
			'continue' => null,
			'diffto' => null,
			'difftotext' => null,
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'prop' => array(
				'Which properties to get for each revision:',
				' ids            - The ID of the revision',
				' flags          - Revision flags (minor)',
				' timestamp      - The timestamp of the revision',
				' user           - User that made the revision',
				' userid		 - User id of revision creator',
				' size           - Length of the revision',
				' comment        - Comment by the user for revision',
				' parsedcomment  - Parsed comment by the user for the revision',
				' content        - Text of the revision',
				' tags           - Tags for the revision',
			),
			'limit' => 'Limit how many revisions will be returned (enum)',
			'startid' => 'From which revision id to start enumeration (enum)',
			'endid' => 'Stop revision enumeration on this revid (enum)',
			'start' => 'From which revision timestamp to start enumeration (enum)',
			'end' => 'Enumerate up to this timestamp (enum)',
			'dir' => 'Direction of enumeration - towards "newer" or "older" revisions (enum)',
			'user' => 'Only include revisions made by user',
			'excludeuser' => 'Exclude revisions made by user',
			'expandtemplates' => 'Expand templates in revision content',
			'generatexml' => 'Generate XML parse tree for revision content',
			'section' => 'Only retrieve the content of this section number',
			'token' => 'Which tokens to obtain for each revision',
			'continue' => 'When more results are available, use this to continue',
			'diffto' => array( 'Revision ID to diff each revision to.',
				'Use "prev", "next" and "cur" for the previous, next and current revision respectively' ),
			'difftotext' => array( 'Text to diff each revision to. Only diffs a limited number of revisions.',
				"Overrides {$p}diffto. If {$p}section is set, only that section will be diffed against this text" ),
			'tag' => 'Only list revisions tagged with this tag',
		);
	}

	public function getDescription() {
		return array(
			'Get revision information',
			'This module may be used in several ways:',
			' 1) Get data about a set of pages (last revision), by setting titles or pageids parameter',
			' 2) Get revisions for one given page, by using titles/pageids with start/end/limit params',
			' 3) Get data about a set of revisions by setting their IDs with revids parameter',
			'All parameters marked as (enum) may only be used with a single page (#2)'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'nosuchrevid', 'diffto' ),
			array( 'code' => 'revids', 'info' => 'The revids= parameter may not be used with the list options (limit, startid, endid, dirNewer, start, end).' ),
			array( 'code' => 'multpages', 'info' => 'titles, pageids or a generator was used to supply multiple pages, but the limit, startid, endid, dirNewer, user, excludeuser, start and end parameters may only be used on a single page.' ),
			array( 'code' => 'diffto', 'info' => 'rvdiffto must be set to a non-negative number, "prev", "next" or "cur"' ),
			array( 'code' => 'badparams', 'info' => 'start and startid cannot be used together' ),
			array( 'code' => 'badparams', 'info' => 'end and endid cannot be used together' ),
			array( 'code' => 'badparams', 'info' => 'user and excludeuser cannot be used together' ),
			array( 'code' => 'nosuchsection', 'info' => 'There is no section section in rID' ),
		) );
	}

	protected function getExamples() {
		return array(
			'Get data with content for the last revision of titles "API" and "Main Page":',
			'  api.php?action=query&prop=revisions&titles=API|Main%20Page&rvprop=timestamp|user|comment|content',
			'Get last 5 revisions of the "Main Page":',
			'  api.php?action=query&prop=revisions&titles=Main%20Page&rvlimit=5&rvprop=timestamp|user|comment',
			'Get first 5 revisions of the "Main Page":',
			'  api.php?action=query&prop=revisions&titles=Main%20Page&rvlimit=5&rvprop=timestamp|user|comment&rvdir=newer',
			'Get first 5 revisions of the "Main Page" made after 2006-05-01:',
			'  api.php?action=query&prop=revisions&titles=Main%20Page&rvlimit=5&rvprop=timestamp|user|comment&rvdir=newer&rvstart=20060501000000',
			'Get first 5 revisions of the "Main Page" that were not made made by anonymous user "127.0.0.1"',
			'  api.php?action=query&prop=revisions&titles=Main%20Page&rvlimit=5&rvprop=timestamp|user|comment&rvexcludeuser=127.0.0.1',
			'Get first 5 revisions of the "Main Page" that were made by the user "MediaWiki default"',
			'  api.php?action=query&prop=revisions&titles=Main%20Page&rvlimit=5&rvprop=timestamp|user|comment&rvuser=MediaWiki%20default',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
