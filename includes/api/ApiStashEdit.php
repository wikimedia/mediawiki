<?php
/**
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
 * @author Aaron Schulz
 */

/**
 * Prepare and edit in shared cache so that it can be reused on edit
 *
 * This endpoint can be called via AJAX as the user focuses on the edit
 * summary box. By the time of submission, the parse may have already
 * finished, and can be immediately used on page save. Certain parser
 * functions like {{REVISIONID}} or {{CURRENTTIME}} may cause the cache
 * to not be used on edit. Template and files used are check for changes
 * since the output was generated. The cache TTL is also kept low for sanity.
 *
 * @ingroup API
 * @since 1.25
 */
class ApiStashEdit extends ApiBase {
	const ERROR_NONE = 'stashed';
	const ERROR_PARSE = 'error_parse';
	const ERROR_CACHE = 'error_cache';
	const ERROR_UNCACHEABLE = 'uncacheable';

	public function execute() {
		global $wgMemc;

		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$page = $this->getTitleOrPageId( $params );
		$title = $page->getTitle();

		if ( !ContentHandler::getForModelID( $params['contentmodel'] )
			->isSupportedFormat( $params['contentformat'] )
		) {
			$this->dieUsage( "Unsupported content model/format", 'badmodelformat' );
		}

		// Trim and fix newlines so the key SHA1's match (see RequestContext::getText())
		$text = rtrim( str_replace( "\r\n", "\n", $params['text'] ) );
		$textContent = ContentHandler::makeContent(
			$text, $title, $params['contentmodel'], $params['contentformat'] );

		$page = WikiPage::factory( $title );
		if ( $page->exists() ) {
			// Page exists: get the merged content with the proposed change
			$baseRev = Revision::newFromPageId( $page->getId(), $params['baserevid'] );
			if ( !$baseRev ) {
				$this->dieUsage( "No revision ID {$params['baserevid']}", 'missingrev' );
			}
			$currentRev = $page->getRevision();
			if ( !$currentRev ) {
				$this->dieUsage( "No current revision of page ID {$page->getId()}", 'missingrev' );
			}
			// Merge in the new version of the section to get the proposed version
			$editContent = $page->replaceSectionAtRev(
				$params['section'],
				$textContent,
				$params['sectiontitle'],
				$baseRev->getId()
			);
			if ( !$editContent ) {
				$this->dieUsage( "Could not merge updated section.", 'replacefailed' );
			}
			if ( $currentRev->getId() == $baseRev->getId() ) {
				// Base revision was still the latest; nothing to merge
				$content = $editContent;
			} else {
				// Merge the edit into the current version
				$baseContent = $baseRev->getContent();
				$currentContent = $currentRev->getContent();
				if ( !$baseContent || !$currentContent ) {
					$this->dieUsage( "Missing content for page ID {$page->getId()}", 'missingrev' );
				}
				$handler = ContentHandler::getForModelID( $baseContent->getModel() );
				$content = $handler->merge3( $baseContent, $editContent, $currentContent );
			}
		} else {
			// New pages: use the user-provided content model
			$content = $textContent;
		}

		if ( !$content ) { // merge3() failed
			$this->getResult()->addValue( null,
				$this->getModuleName(), array( 'status' => 'editconflict' ) );
			return;
		}

		// The user will abort the AJAX request by pressing "save", so ignore that
		ignore_user_abort( true );

		// Get a key based on the source text, format, and user preferences
		$key = self::getStashKey( $title, $content, $user );
		// De-duplicate requests on the same key
		if ( $user->pingLimiter( 'stashedit' ) ) {
			$status = 'ratelimited';
		} elseif ( $wgMemc->lock( $key, 0, 30 ) ) {
			$unlocker = new ScopedCallback( function() use ( $key ) {
				global $wgMemc;
				$wgMemc->unlock( $key );
			} );
			$status = self::parseAndStash( $page, $content, $user );
		} else {
			$status = 'busy';
		}

		$this->getResult()->addValue( null, $this->getModuleName(), array( 'status' => $status ) );
	}

	/**
	 * @param WikiPage $page
	 * @param Content $content
	 * @param User $user
	 * @return integer ApiStashEdit::ERROR_* constant
	 * @since 1.25
	 */
	public static function parseAndStash( WikiPage $page, Content $content, User $user ) {
		global $wgMemc;

		$format = $content->getDefaultFormat();
		$editInfo = $page->prepareContentForEdit( $content, null, $user, $format, false );

		if ( $editInfo && $editInfo->output ) {
			$key = self::getStashKey( $page->getTitle(), $content, $user );

			list( $stashInfo, $ttl ) = self::buildStashValue(
				$editInfo->pstContent, $editInfo->output, $editInfo->timestamp
			);

			if ( $stashInfo ) {
				$ok = $wgMemc->set( $key, $stashInfo, $ttl );
				if ( $ok ) {
					wfDebugLog( 'StashEdit', "Cached parser output for key '$key'." );
					return self::ERROR_NONE;
				} else {
					wfDebugLog( 'StashEdit', "Failed to cache parser output for key '$key'." );
					return self::ERROR_CACHE;
				}
			} else {
				wfDebugLog( 'StashEdit', "Uncacheable parser output for key '$key'." );
				return self::ERROR_UNCACHEABLE;
			}
		}

		return self::ERROR_PARSE;
	}

	/**
	 * Attempt to cache PST content and corresponding parser output in passing
	 *
	 * This method can be called when the output was already generated for other
	 * reasons. Parsing should not be done just to call this method, however.
	 * $pstOpts must be that of the user doing the edit preview. If $pOpts does
	 * not match the options of WikiPage::makeParserOptions( 'canonical' ), this
	 * will do nothing. Provided the values are cacheable, they will be stored
	 * in memcached so that final edit submission might make use of them.
	 *
	 * @param Article|WikiPage $page Page title
	 * @param Content $content Proposed page content
	 * @param Content $pstContent The result of preSaveTransform() on $content
	 * @param ParserOutput $pOut The result of getParserOutput() on $pstContent
	 * @param ParserOptions $pstOpts Options for $pstContent (MUST be for prospective author)
	 * @param ParserOptions $pOpts Options for $pOut
	 * @param string $timestamp TS_MW timestamp of parser output generation
	 * @return bool Success
	 */
	public static function stashEditFromPreview(
		Page $page, Content $content, Content $pstContent, ParserOutput $pOut,
		ParserOptions $pstOpts, ParserOptions $pOpts, $timestamp
	) {
		global $wgMemc;

		// getIsPreview() controls parser function behavior that references things
		// like user/revision that don't exists yet. The user/text should already
		// be set correctly by callers, just double check the preview flag.
		if ( !$pOpts->getIsPreview() ) {
			return false; // sanity
		} elseif ( $pOpts->getIsSectionPreview() ) {
			return false; // short-circuit (need the full content)
		}

		// PST parser options are for the user (handles signatures, etc...)
		$user = $pstOpts->getUser();
		// Get a key based on the source text, format, and user preferences
		$key = self::getStashKey( $page->getTitle(), $content, $user );

		// Parser output options must match cannonical options.
		// Treat some options as matching that are different but don't matter.
		$canonicalPOpts = $page->makeParserOptions( 'canonical' );
		$canonicalPOpts->setIsPreview( true ); // force match
		$canonicalPOpts->setTimestamp( $pOpts->getTimestamp() ); // force match
		if ( !$pOpts->matches( $canonicalPOpts ) ) {
			wfDebugLog( 'StashEdit', "Uncacheable preview output for key '$key' (options)." );
			return false;
		}

		// Build a value to cache with a proper TTL
		list( $stashInfo, $ttl ) = self::buildStashValue( $pstContent, $pOut, $timestamp );
		if ( !$stashInfo ) {
			wfDebugLog( 'StashEdit', "Uncacheable parser output for key '$key' (rev/TTL)." );
			return false;
		}

		$ok = $wgMemc->set( $key, $stashInfo, $ttl );
		if ( !$ok ) {
			wfDebugLog( 'StashEdit', "Failed to cache preview parser output for key '$key'." );
		} else {
			wfDebugLog( 'StashEdit', "Cached preview output for key '$key'." );
		}

		return $ok;
	}

	/**
	 * Check that a prepared edit is in cache and still up-to-date
	 *
	 * This method blocks if the prepared edit is already being rendered,
	 * waiting until rendering finishes before doing final validity checks.
	 *
	 * The cache is rejected if template or file changes are detected.
	 * Note that foreign template or file transclusions are not checked.
	 *
	 * The result is a map (pstContent,output,timestamp) with fields
	 * extracted directly from WikiPage::prepareContentForEdit().
	 *
	 * @param Title $title
	 * @param Content $content
	 * @param User $user User to get parser options from
	 * @return stdClass|bool Returns false on cache miss
	 */
	public static function checkCache( Title $title, Content $content, User $user ) {
		global $wgMemc;

		$key = self::getStashKey( $title, $content, $user );
		$editInfo = $wgMemc->get( $key );
		if ( !is_object( $editInfo ) ) {
			$start = microtime( true );
			// We ignore user aborts and keep parsing. Block on any prior parsing
			// so as to use it's results and make use of the time spent parsing.
			if ( $wgMemc->lock( $key, 30, 30 ) ) {
				$editInfo = $wgMemc->get( $key );
				$wgMemc->unlock( $key );
			}
			$sec = microtime( true ) - $start;
			if ( $sec > .01 ) {
				wfDebugLog( 'StashEdit', "Waited $sec seconds on '$key'." );
			}
		}

		if ( !is_object( $editInfo ) || !$editInfo->output ) {
			wfDebugLog( 'StashEdit', "No cache value for key '$key'." );
			return false;
		}

		$time = wfTimestamp( TS_UNIX, $editInfo->output->getTimestamp() );
		if ( ( time() - $time ) <= 3 ) {
			wfDebugLog( 'StashEdit', "Timestamp-based cache hit for key '$key'." );
			return $editInfo; // assume nothing changed
		}

		$dbr = wfGetDB( DB_SLAVE );

		$templates = array(); // conditions to find changes/creations
		$templateUses = 0; // expected existing templates
		foreach ( $editInfo->output->getTemplateIds() as $ns => $stuff ) {
			foreach ( $stuff as $dbkey => $revId ) {
				$templates[(string)$ns][$dbkey] = (int)$revId;
				++$templateUses;
			}
		}
		// Check that no templates used in the output changed...
		if ( count( $templates ) ) {
			$res = $dbr->select(
				'page',
				array( 'ns' => 'page_namespace', 'dbk' => 'page_title', 'page_latest' ),
				$dbr->makeWhereFrom2d( $templates, 'page_namespace', 'page_title' ),
				__METHOD__
			);
			$changed = false;
			foreach ( $res as $row ) {
				$changed = $changed || ( $row->page_latest != $templates[$row->ns][$row->dbk] );
			}

			if ( $changed || $res->numRows() != $templateUses ) {
				wfDebugLog( 'StashEdit', "Stale cache for key '$key'; template changed." );
				return false;
			}
		}

		$files = array(); // conditions to find changes/creations
		foreach ( $editInfo->output->getFileSearchOptions() as $name => $options ) {
			$files[$name] = (string)$options['sha1'];
		}
		// Check that no files used in the output changed...
		if ( count( $files ) ) {
			$res = $dbr->select(
				'image',
				array( 'name' => 'img_name', 'img_sha1' ),
				array( 'img_name' => array_keys( $files ) ),
				__METHOD__
			);
			$changed = false;
			foreach ( $res as $row ) {
				$changed = $changed || ( $row->img_sha1 != $files[$row->name] );
			}

			if ( $changed || $res->numRows() != count( $files ) ) {
				wfDebugLog( 'StashEdit', "Stale cache for key '$key'; file changed." );
				return false;
			}
		}

		wfDebugLog( 'StashEdit', "Cache hit for key '$key'." );

		return $editInfo;
	}

	/**
	 * Get the temporary prepared edit stash key for a user
	 *
	 * This key can be used for caching prepared edits provided:
	 *   - a) The $user was used for PST options
	 *   - b) The parser output was made from the PST using cannonical matching options
	 *
	 * @param Title $title
	 * @param Content $content
	 * @param User $user User to get parser options from
	 * @return string
	 */
	protected static function getStashKey( Title $title, Content $content, User $user ) {
		$hash = sha1( implode( ':', array(
			$content->getModel(),
			$content->getDefaultFormat(),
			sha1( $content->serialize( $content->getDefaultFormat() ) ),
			$user->getId() ?: md5( $user->getName() ), // account for user parser options
			$user->getId() ? $user->getTouched() : '-' // handle preference change races
		) ) );

		return wfMemcKey( 'prepared-edit', md5( $title->getPrefixedDBkey() ), $hash );
	}

	/**
	 * Build a value to store in memcached based on the PST content and parser output
	 *
	 * This makes a simple version of WikiPage::prepareContentForEdit() as stash info
	 *
	 * @param Content $pstContent
	 * @param ParserOutput $parserOutput
	 * @param string $timestamp TS_MW
	 * @return array (stash info array, TTL in seconds) or (null, 0)
	 */
	protected static function buildStashValue(
		Content $pstContent, ParserOutput $parserOutput, $timestamp
	) {
		// If an item is renewed, mind the cache TTL determined by config and parser functions
		$since = time() - wfTimestamp( TS_UNIX, $parserOutput->getTimestamp() );
		$ttl = min( $parserOutput->getCacheExpiry() - $since, 5 * 60 );

		if ( $ttl > 0 && !$parserOutput->getFlag( 'vary-revision' ) ) {
			// Only store what is actually needed
			$stashInfo = (object)array(
				'pstContent' => $pstContent,
				'output'     => $parserOutput,
				'timestamp'  => $timestamp
			);
			return array( $stashInfo, $ttl );
		}

		return array( null, 0 );
	}

	public function getAllowedParams() {
		return array(
			'title' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'section' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'sectiontitle' => array(
				ApiBase::PARAM_TYPE => 'string'
			),
			'text' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'contentmodel' => array(
				ApiBase::PARAM_TYPE => ContentHandler::getContentModels(),
				ApiBase::PARAM_REQUIRED => true
			),
			'contentformat' => array(
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats(),
				ApiBase::PARAM_REQUIRED => true
			),
			'baserevid' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true
			)
		);
	}

	function needsToken() {
		return 'csrf';
	}

	function mustBePosted() {
		return true;
	}

	function isInternal() {
		return true;
	}
}
