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
class ApiPrepareEdit extends ApiBase {
	public function execute() {
		global $wgMemc;

		$params = $this->extractRequestParams();

		$title = Title::newFromText( $params['title'] );
		if ( !$title ) {
			$this->dieUsage( "Invalid title provided", 'badtitle' );
		} elseif ( !ContentHandler::getForModelID( $params['model'] )->isSupportedFormat( $params['format'] ) ) {
			$this->dieUsage( "Unsupported content model/format", 'badmodelformat' );
		}
		$user = $this->getUser();

		$text = trim( $params['text'] ); // needed so the key SHA1's match
		$textContent = ContentHandler::makeContent( $text, $title, $params['model'], $params['format'] );

		$page = WikiPage::factory( $title );
		if ( $page->getId() ) {
			// Page exists: get the merged content with the proposed change
			$baseRev = Revision::newFromPageId( $page->getId(), $params['baseRevId'] );
			if ( !$baseRev ) {
				$this->dieUsage( "Missing revision ID {$params['baseRevId']}", 'missingrev' );
			}
			// Merge in the new version of the section to get the proposed version
			$editContent = $page->replaceSectionAtRev(
				$params['section'],
				$textContent,
				$params['sectionTitle'],
				$baseRev->getId()
			);
			if ( !$editContent ) {
				$this->dieUsage( "Could not merge updates to page ID {$page->getId()}", 'replacefailed' );
			}
			// Merge the edit into the current version
			$baseContent = $baseRev->getContent();
			$currentContent = $page->getContent();
			if ( !$baseContent || !$currentContent ) {
				$this->dieUsage( "Missing content for page ID {$page->getId()}", 'missingrev' );
			}
			$handler = ContentHandler::getForModelID( $baseContent->getModel() );
			$content = $handler->merge3( $baseContent, $editContent, $currentContent );
			if ( !$content ) {
				$this->getResult()->addValue( null, $this->getModuleName(), array( 'status' => 'conflict' ) );
				return;
			}
		} else {
			// New pages: use the user-provided content model
			$content = $textContent;
		}

		// Serialization content format
		$contentFormat = $content->getDefaultFormat();

		// The user will abort the AJAX request by pressing "save", so ignore that
		ignore_user_abort( true );

		// Get a key based on the source text, format, and user preferences
		$key = self::getStashKey( $title, $content, $contentFormat, $user );
		// De-duplicate requests on the same key
		if ( $wgMemc->lock( $key, 0, 30 ) ) {
			$editInfo = $page->prepareContentForEdit( $content, null, $user, $contentFormat );
			$wgMemc->unlock( $key );
			$status = 'error'; // default
		} else {
			$editInfo = false;
			$status = 'busy';
		}

		if ( $editInfo && $editInfo->output ) {
			$parserOutput = $editInfo->output;
			// If a cached item is renewed, mind the cache TTL determined by config and parser functions
			$since = time() - wfTimestamp( TS_UNIX, $parserOutput->getTimestamp() );
			$ttl = min( $parserOutput->getCacheExpiry() - $since, 5 * 60 );
			if ( $ttl > 0 && !$parserOutput->getFlag( 'vary-revision' ) ) {
				// Only store what is actually needed
				$stashInfo = (object)array(
					'pstContent' => $editInfo->pstContent,
					'output' => $editInfo->output,
					'timestamp' => $editInfo->timestamp
				);
				$ok = $wgMemc->set( $key, $stashInfo, $ttl );
				if ( $ok ) {
					$status = 'stashed';
					wfDebugLog( 'PreparedEdit', "Cached parser output for key '$key'." );
				} else {
					$status = 'error';
					wfDebugLog( 'PreparedEdit', "Failed to cache parser output for key '$key'." );
				}
			} else {
				$status = 'uncacheable';
				wfDebugLog( 'PreparedEdit', "Uncacheable parser output for key '$key'." );
			}
		}

		$this->getResult()->addValue( null, $this->getModuleName(), array( 'status' => $status ) );
	}

	/**
	 * Get the temporary prepared edit stash key for a user
	 *
	 * @param Title $title
	 * @param Content $content
	 * @param string $serialization_format
	 * @param User $user User to get parser options from
	 * @return string
	 */
	protected static function getStashKey(
		Title $title, Content $content, $serialization_format, User $user
	) {
		return wfMemcKey( 'prepared-edit',
			md5( $title->getPrefixedDBkey() ), // handle rename races
			$serialization_format,
			sha1( $content->serialize( $serialization_format ) ),
			$user->getId() ?: md5( $user->getName() ), // account for user parser options
			$user->getId() ? $user->getTouched() : '-' // handle preference change races
		);
	}

	/**
	 * Check that a prepared edit is in cache and still up-to-date
	 *
	 * This method blocks if the prepared edit is already being rendered,
	 * waiting until rendering finishes before do final checks of the cache.
	 *
	 * The result is a map with (pstContent,output,timestamp), which are
	 * extracted from WikiPage::prepareContentForEdit().
	 *
	 * @param Title $title
	 * @param Content $content
	 * @param string $serialization_format
	 * @param User $user User to get parser options from
	 * @return stdClass|bool Returns false on cache miss
	 */
	public static function checkCache(
		Title $title, Content $content, $serialization_format, User $user
	) {
		global $wgMemc;

		$key = self::getStashKey( $title, $content, $serialization_format, $user );
		$editInfo = $wgMemc->get( $key );
		if ( !is_object( $editInfo ) ) {
			// We ignore user aborts and keep parsing. Block on any prior parsing
			// so as to use it's results and make use of the time spent parsing.
			if ( $wgMemc->lock( $key, 30, 30 ) ) {
				$editInfo = $wgMemc->get( $key );
				$wgMemc->unlock( $key );
			}
		}

		if ( !is_object( $editInfo ) || !$editInfo->output ) {
			return false;
		}

		$time = wfTimestamp( TS_UNIX, $editInfo->output->getTimestamp() );
		if ( ( time() - $time ) <= 3 ) {
			wfDebugLog( 'PreparedEdit', "Quick cache hit for key '$key'." );
			return $editInfo; // assume nothing changed
		}

		$dbr = wfGetDB( DB_SLAVE );
		// Check that no templates used in the output changed...
		$conds = array();
		foreach ( $editInfo->output->getTemplateIds() as $ns => $stuff ) {
			foreach ( $stuff as $dbkey => $revId ) {
				$conds[] = array( 'page_namespace' => $ns, 'page_title' => $dbkey,
					'page_latest != ' . intval( $revId ) );
			}
		}
		$row = $dbr->selectRow( 'page', '1', $dbr->makeList( $conds, LIST_OR ), __METHOD__ );
		if ( $row ) {
			wfDebugLog( 'PreparedEdit', "Stale cache for key '$key'; template changed." );
			return false;
		}
		// Check that no files used in the output changed...
		$conds = array();
		foreach ( $editInfo->output->getFileSearchOptions() as $name => $options ) {
			$conds[] = array( 'img_name' => $dbkey,
				'img_sha1 != ' . $dbr->addQuotes( $options['sha1'] ) );
		}
		$row = $dbr->selectRow( 'image', '1', $dbr->makeList( $conds, LIST_OR ), __METHOD__ );
		if ( $row ) {
			wfDebugLog( 'PreparedEdit', "Stale cache for key '$key'; file changed." );
			return false;
		}

		wfDebugLog( 'PreparedEdit', "Cache hit for key '$key'." );

		return $editInfo;
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
			'sectionTitle' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_DFLT => '',
			),
			'text' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'model' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'format' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'baseRevId' => array(
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
