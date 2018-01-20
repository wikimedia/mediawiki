<?php
/**
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

class ApiComparePages extends ApiBase {

	private $guessed = false, $guessedTitle, $guessedModel, $props;

	public function execute() {
		$params = $this->extractRequestParams();

		// Parameter validation
		$this->requireAtLeastOneParameter( $params, 'fromtitle', 'fromid', 'fromrev', 'fromtext' );
		$this->requireAtLeastOneParameter( $params, 'totitle', 'toid', 'torev', 'totext', 'torelative' );

		$this->props = array_flip( $params['prop'] );

		// Cache responses publicly by default. This may be overridden later.
		$this->getMain()->setCacheMode( 'public' );

		// Get the 'from' Revision and Content
		list( $fromRev, $fromContent, $relRev ) = $this->getDiffContent( 'from', $params );

		// Get the 'to' Revision and Content
		if ( $params['torelative'] !== null ) {
			if ( !$relRev ) {
				$this->dieWithError( 'apierror-compare-relative-to-nothing' );
			}
			switch ( $params['torelative'] ) {
				case 'prev':
					// Swap 'from' and 'to'
					$toRev = $fromRev;
					$toContent = $fromContent;
					$fromRev = $relRev->getPrevious();
					$fromContent = $fromRev
						? $fromRev->getContent( Revision::FOR_THIS_USER, $this->getUser() )
						: $toContent->getContentHandler()->makeEmptyContent();
					if ( !$fromContent ) {
						$this->dieWithError(
							[ 'apierror-missingcontent-revid', $fromRev->getId() ], 'missingcontent'
						);
					}
					break;

				case 'next':
					$toRev = $relRev->getNext();
					$toContent = $toRev
						? $toRev->getContent( Revision::FOR_THIS_USER, $this->getUser() )
						: $fromContent;
					if ( !$toContent ) {
						$this->dieWithError( [ 'apierror-missingcontent-revid', $toRev->getId() ], 'missingcontent' );
					}
					break;

				case 'cur':
					$title = $relRev->getTitle();
					$id = $title->getLatestRevID();
					$toRev = $id ? Revision::newFromId( $id ) : null;
					if ( !$toRev ) {
						$this->dieWithError(
							[ 'apierror-missingrev-title', wfEscapeWikiText( $title->getPrefixedText() ) ], 'nosuchrevid'
						);
					}
					$toContent = $toRev->getContent( Revision::FOR_THIS_USER, $this->getUser() );
					if ( !$toContent ) {
						$this->dieWithError( [ 'apierror-missingcontent-revid', $toRev->getId() ], 'missingcontent' );
					}
					break;
			}
			$relRev2 = null;
		} else {
			list( $toRev, $toContent, $relRev2 ) = $this->getDiffContent( 'to', $params );
		}

		// Should never happen, but just in case...
		if ( !$fromContent || !$toContent ) {
			$this->dieWithError( 'apierror-baddiff' );
		}

		// Extract sections, if told to
		if ( isset( $params['fromsection'] ) ) {
			$fromContent = $fromContent->getSection( $params['fromsection'] );
			if ( !$fromContent ) {
				$this->dieWithError(
					[ 'apierror-compare-nosuchfromsection', wfEscapeWikiText( $params['fromsection'] ) ],
					'nosuchfromsection'
				);
			}
		}
		if ( isset( $params['tosection'] ) ) {
			$toContent = $toContent->getSection( $params['tosection'] );
			if ( !$toContent ) {
				$this->dieWithError(
					[ 'apierror-compare-nosuchtosection', wfEscapeWikiText( $params['tosection'] ) ],
					'nosuchtosection'
				);
			}
		}

		// Get the diff
		$context = new DerivativeContext( $this->getContext() );
		if ( $relRev && $relRev->getTitle() ) {
			$context->setTitle( $relRev->getTitle() );
		} elseif ( $relRev2 && $relRev2->getTitle() ) {
			$context->setTitle( $relRev2->getTitle() );
		} else {
			$this->guessTitleAndModel();
			if ( $this->guessedTitle ) {
				$context->setTitle( $this->guessedTitle );
			}
		}
		$de = $fromContent->getContentHandler()->createDifferenceEngine(
			$context,
			$fromRev ? $fromRev->getId() : 0,
			$toRev ? $toRev->getId() : 0,
			/* $rcid = */ null,
			/* $refreshCache = */ false,
			/* $unhide = */ true
		);
		$de->setContent( $fromContent, $toContent );
		$difftext = $de->getDiffBody();
		if ( $difftext === false ) {
			$this->dieWithError( 'apierror-baddiff' );
		}

		// Fill in the response
		$vals = [];
		$this->setVals( $vals, 'from', $fromRev );
		$this->setVals( $vals, 'to', $toRev );

		if ( isset( $this->props['rel'] ) ) {
			if ( $fromRev ) {
				$rev = $fromRev->getPrevious();
				if ( $rev ) {
					$vals['prev'] = $rev->getId();
				}
			}
			if ( $toRev ) {
				$rev = $toRev->getNext();
				if ( $rev ) {
					$vals['next'] = $rev->getId();
				}
			}
		}

		if ( isset( $this->props['diffsize'] ) ) {
			$vals['diffsize'] = strlen( $difftext );
		}
		if ( isset( $this->props['diff'] ) ) {
			ApiResult::setContentValue( $vals, 'body', $difftext );
		}

		// Diffs can be really big and there's little point in having
		// ApiResult truncate it to an empty response since the diff is the
		// whole reason this module exists. So pass NO_SIZE_CHECK here.
		$this->getResult()->addValue( null, $this->getModuleName(), $vals, ApiResult::NO_SIZE_CHECK );
	}

	/**
	 * Guess an appropriate default Title and content model for this request
	 *
	 * Fills in $this->guessedTitle based on the first of 'fromrev',
	 * 'fromtitle', 'fromid', 'torev', 'totitle', and 'toid' that's present and
	 * valid.
	 *
	 * Fills in $this->guessedModel based on the Revision or Title used to
	 * determine $this->guessedTitle, or the 'fromcontentmodel' or
	 * 'tocontentmodel' parameters if no title was guessed.
	 */
	private function guessTitleAndModel() {
		if ( $this->guessed ) {
			return;
		}

		$this->guessed = true;
		$params = $this->extractRequestParams();

		foreach ( [ 'from', 'to' ] as $prefix ) {
			if ( $params["{$prefix}rev"] !== null ) {
				$revId = $params["{$prefix}rev"];
				$rev = Revision::newFromId( $revId );
				if ( !$rev ) {
					// Titles of deleted revisions aren't secret, per T51088
					$arQuery = Revision::getArchiveQueryInfo();
					$row = $this->getDB()->selectRow(
						$arQuery['tables'],
						array_merge(
							$arQuery['fields'],
							[ 'ar_namespace', 'ar_title' ]
						),
						[ 'ar_rev_id' => $revId ],
						__METHOD__,
						[],
						$arQuery['joins']
					);
					if ( $row ) {
						$rev = Revision::newFromArchiveRow( $row );
					}
				}
				if ( $rev ) {
					$this->guessedTitle = $rev->getTitle();
					$this->guessedModel = $rev->getContentModel();
					break;
				}
			}

			if ( $params["{$prefix}title"] !== null ) {
				$title = Title::newFromText( $params["{$prefix}title"] );
				if ( $title && !$title->isExternal() ) {
					$this->guessedTitle = $title;
					break;
				}
			}

			if ( $params["{$prefix}id"] !== null ) {
				$title = Title::newFromID( $params["{$prefix}id"] );
				if ( $title ) {
					$this->guessedTitle = $title;
					break;
				}
			}
		}

		if ( !$this->guessedModel ) {
			if ( $this->guessedTitle ) {
				$this->guessedModel = $this->guessedTitle->getContentModel();
			} elseif ( $params['fromcontentmodel'] !== null ) {
				$this->guessedModel = $params['fromcontentmodel'];
			} elseif ( $params['tocontentmodel'] !== null ) {
				$this->guessedModel = $params['tocontentmodel'];
			}
		}
	}

	/**
	 * Get the Revision and Content for one side of the diff
	 *
	 * This uses the appropriate set of 'rev', 'id', 'title', 'text', 'pst',
	 * 'contentmodel', and 'contentformat' parameters to determine what content
	 * should be diffed.
	 *
	 * Returns three values:
	 * - The revision used to retrieve the content, if any
	 * - The content to be diffed
	 * - The revision specified, if any, even if not used to retrieve the
	 *   Content
	 *
	 * @param string $prefix 'from' or 'to'
	 * @param array $params
	 * @return array [ Revision|null, Content, Revision|null ]
	 */
	private function getDiffContent( $prefix, array $params ) {
		$title = null;
		$rev = null;
		$suppliedContent = $params["{$prefix}text"] !== null;

		// Get the revision and title, if applicable
		$revId = null;
		if ( $params["{$prefix}rev"] !== null ) {
			$revId = $params["{$prefix}rev"];
		} elseif ( $params["{$prefix}title"] !== null || $params["{$prefix}id"] !== null ) {
			if ( $params["{$prefix}title"] !== null ) {
				$title = Title::newFromText( $params["{$prefix}title"] );
				if ( !$title || $title->isExternal() ) {
					$this->dieWithError(
						[ 'apierror-invalidtitle', wfEscapeWikiText( $params["{$prefix}title"] ) ]
					);
				}
			} else {
				$title = Title::newFromID( $params["{$prefix}id"] );
				if ( !$title ) {
					$this->dieWithError( [ 'apierror-nosuchpageid', $params["{$prefix}id"] ] );
				}
			}
			$revId = $title->getLatestRevID();
			if ( !$revId ) {
				$revId = null;
				// Only die here if we're not using supplied text
				if ( !$suppliedContent ) {
					if ( $title->exists() ) {
						$this->dieWithError(
							[ 'apierror-missingrev-title', wfEscapeWikiText( $title->getPrefixedText() ) ], 'nosuchrevid'
						);
					} else {
						$this->dieWithError(
							[ 'apierror-missingtitle-byname', wfEscapeWikiText( $title->getPrefixedText() ) ],
							'missingtitle'
						);
					}
				}
			}
		}
		if ( $revId !== null ) {
			$rev = Revision::newFromId( $revId );
			if ( !$rev && $this->getUser()->isAllowedAny( 'deletedtext', 'undelete' ) ) {
				// Try the 'archive' table
				$arQuery = Revision::getArchiveQueryInfo();
				$row = $this->getDB()->selectRow(
					$arQuery['tables'],
					array_merge(
						$arQuery['fields'],
						[ 'ar_namespace', 'ar_title' ]
					),
					[ 'ar_rev_id' => $revId ],
					__METHOD__,
					[],
					$arQuery['joins']
				);
				if ( $row ) {
					$rev = Revision::newFromArchiveRow( $row );
					$rev->isArchive = true;
				}
			}
			if ( !$rev ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $revId ] );
			}
			$title = $rev->getTitle();

			// If we don't have supplied content, return here. Otherwise,
			// continue on below with the supplied content.
			if ( !$suppliedContent ) {
				$content = $rev->getContent( Revision::FOR_THIS_USER, $this->getUser() );
				if ( !$content ) {
					$this->dieWithError( [ 'apierror-missingcontent-revid', $revId ], 'missingcontent' );
				}
				return [ $rev, $content, $rev ];
			}
		}

		// Override $content based on supplied text
		$model = $params["{$prefix}contentmodel"];
		$format = $params["{$prefix}contentformat"];

		if ( !$model && $rev ) {
			$model = $rev->getContentModel();
		}
		if ( !$model && $title ) {
			$model = $title->getContentModel();
		}
		if ( !$model ) {
			$this->guessTitleAndModel();
			$model = $this->guessedModel;
		}
		if ( !$model ) {
			$model = CONTENT_MODEL_WIKITEXT;
			$this->addWarning( [ 'apiwarn-compare-nocontentmodel', $model ] );
		}

		if ( !$title ) {
			$this->guessTitleAndModel();
			$title = $this->guessedTitle;
		}

		try {
			$content = ContentHandler::makeContent( $params["{$prefix}text"], $title, $model, $format );
		} catch ( MWContentSerializationException $ex ) {
			$this->dieWithException( $ex, [
				'wrap' => ApiMessage::create( 'apierror-contentserializationexception', 'parseerror' )
			] );
		}

		if ( $params["{$prefix}pst"] ) {
			if ( !$title ) {
				$this->dieWithError( 'apierror-compare-no-title' );
			}
			$popts = ParserOptions::newFromContext( $this->getContext() );
			$content = $content->preSaveTransform( $title, $this->getUser(), $popts );
		}

		return [ null, $content, $rev ];
	}

	/**
	 * Set value fields from a Revision object
	 * @param array &$vals Result array to set data into
	 * @param string $prefix 'from' or 'to'
	 * @param Revision|null $rev
	 */
	private function setVals( &$vals, $prefix, $rev ) {
		if ( $rev ) {
			$title = $rev->getTitle();
			if ( isset( $this->props['ids'] ) ) {
				$vals["{$prefix}id"] = $title->getArticleID();
				$vals["{$prefix}revid"] = $rev->getId();
			}
			if ( isset( $this->props['title'] ) ) {
				ApiQueryBase::addTitleInfo( $vals, $title, $prefix );
			}
			if ( isset( $this->props['size'] ) ) {
				$vals["{$prefix}size"] = $rev->getSize();
			}

			$anyHidden = false;
			if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
				$vals["{$prefix}texthidden"] = true;
				$anyHidden = true;
			}

			if ( $rev->isDeleted( Revision::DELETED_USER ) ) {
				$vals["{$prefix}userhidden"] = true;
				$anyHidden = true;
			}
			if ( isset( $this->props['user'] ) &&
				$rev->userCan( Revision::DELETED_USER, $this->getUser() )
			) {
				$vals["{$prefix}user"] = $rev->getUserText( Revision::RAW );
				$vals["{$prefix}userid"] = $rev->getUser( Revision::RAW );
			}

			if ( $rev->isDeleted( Revision::DELETED_COMMENT ) ) {
				$vals["{$prefix}commenthidden"] = true;
				$anyHidden = true;
			}
			if ( $rev->userCan( Revision::DELETED_COMMENT, $this->getUser() ) ) {
				if ( isset( $this->props['comment'] ) ) {
					$vals["{$prefix}comment"] = $rev->getComment( Revision::RAW );
				}
				if ( isset( $this->props['parsedcomment'] ) ) {
					$vals["{$prefix}parsedcomment"] = Linker::formatComment(
						$rev->getComment( Revision::RAW ),
						$rev->getTitle()
					);
				}
			}

			if ( $anyHidden ) {
				$this->getMain()->setCacheMode( 'private' );
				if ( $rev->isDeleted( Revision::DELETED_RESTRICTED ) ) {
					$vals["{$prefix}suppressed"] = true;
				}
			}

			if ( !empty( $rev->isArchive ) ) {
				$this->getMain()->setCacheMode( 'private' );
				$vals["{$prefix}archive"] = true;
			}
		}
	}

	public function getAllowedParams() {
		// Parameters for the 'from' and 'to' content
		$fromToParams = [
			'title' => null,
			'id' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'rev' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'text' => [
				ApiBase::PARAM_TYPE => 'text'
			],
			'section' => null,
			'pst' => false,
			'contentformat' => [
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats(),
			],
			'contentmodel' => [
				ApiBase::PARAM_TYPE => ContentHandler::getContentModels(),
			]
		];

		$ret = [];
		foreach ( $fromToParams as $k => $v ) {
			$ret["from$k"] = $v;
		}
		foreach ( $fromToParams as $k => $v ) {
			$ret["to$k"] = $v;
		}

		$ret = wfArrayInsertAfter(
			$ret,
			[ 'torelative' => [ ApiBase::PARAM_TYPE => [ 'prev', 'next', 'cur' ], ] ],
			'torev'
		);

		$ret['prop'] = [
			ApiBase::PARAM_DFLT => 'diff|ids|title',
			ApiBase::PARAM_TYPE => [
				'diff',
				'diffsize',
				'rel',
				'ids',
				'title',
				'user',
				'comment',
				'parsedcomment',
				'size',
			],
			ApiBase::PARAM_ISMULTI => true,
			ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
		];

		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=compare&fromrev=1&torev=2'
				=> 'apihelp-compare-example-1',
		];
	}
}
