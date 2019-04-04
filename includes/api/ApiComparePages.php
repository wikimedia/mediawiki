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

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;

class ApiComparePages extends ApiBase {

	/** @var RevisionStore */
	private $revisionStore;

	/** @var \MediaWiki\Revision\SlotRoleRegistry */
	private $slotRoleRegistry;

	private $guessedTitle = false, $props;

	public function __construct( ApiMain $mainModule, $moduleName, $modulePrefix = '' ) {
		parent::__construct( $mainModule, $moduleName, $modulePrefix );
		$this->revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$this->slotRoleRegistry = MediaWikiServices::getInstance()->getSlotRoleRegistry();
	}

	public function execute() {
		$params = $this->extractRequestParams();

		// Parameter validation
		$this->requireAtLeastOneParameter(
			$params, 'fromtitle', 'fromid', 'fromrev', 'fromtext', 'fromslots'
		);
		$this->requireAtLeastOneParameter(
			$params, 'totitle', 'toid', 'torev', 'totext', 'torelative', 'toslots'
		);

		$this->props = array_flip( $params['prop'] );

		// Cache responses publicly by default. This may be overridden later.
		$this->getMain()->setCacheMode( 'public' );

		// Get the 'from' RevisionRecord
		list( $fromRev, $fromRelRev, $fromValsRev ) = $this->getDiffRevision( 'from', $params );

		// Get the 'to' RevisionRecord
		if ( $params['torelative'] !== null ) {
			if ( !$fromRelRev ) {
				$this->dieWithError( 'apierror-compare-relative-to-nothing' );
			}
			if ( $params['torelative'] !== 'cur' && $fromRelRev instanceof RevisionArchiveRecord ) {
				// RevisionStore's getPreviousRevision/getNextRevision blow up
				// when passed an RevisionArchiveRecord for a deleted page
				$this->dieWithError( [ 'apierror-compare-relative-to-deleted', $params['torelative'] ] );
			}
			switch ( $params['torelative'] ) {
				case 'prev':
					// Swap 'from' and 'to'
					list( $toRev, $toRelRev, $toValsRev ) = [ $fromRev, $fromRelRev, $fromValsRev ];
					$fromRev = $this->revisionStore->getPreviousRevision( $toRelRev );
					$fromRelRev = $fromRev;
					$fromValsRev = $fromRev;
					if ( !$fromRev ) {
						$title = Title::newFromLinkTarget( $toRelRev->getPageAsLinkTarget() );
						$this->addWarning( [
							'apiwarn-compare-no-prev',
							wfEscapeWikiText( $title->getPrefixedText() ),
							$toRelRev->getId()
						] );

						// (T203433) Create an empty dummy revision as the "previous".
						// The main slot has to exist, the rest will be handled by DifferenceEngine.
						$fromRev = $this->revisionStore->newMutableRevisionFromArray( [
							'title' => $title ?: Title::makeTitle( NS_SPECIAL, 'Badtitle/' . __METHOD__ )
						] );
						$fromRev->setContent(
							SlotRecord::MAIN,
							$toRelRev->getContent( SlotRecord::MAIN, RevisionRecord::RAW )
								->getContentHandler()
								->makeEmptyContent()
						);
					}
					break;

				case 'next':
					$toRev = $this->revisionStore->getNextRevision( $fromRelRev );
					$toRelRev = $toRev;
					$toValsRev = $toRev;
					if ( !$toRev ) {
						$title = Title::newFromLinkTarget( $fromRelRev->getPageAsLinkTarget() );
						$this->addWarning( [
							'apiwarn-compare-no-next',
							wfEscapeWikiText( $title->getPrefixedText() ),
							$fromRelRev->getId()
						] );

						// (T203433) The web UI treats "next" as "cur" in this case.
						// Avoid repeating metadata by making a MutableRevisionRecord with no changes.
						$toRev = MutableRevisionRecord::newFromParentRevision( $fromRelRev );
					}
					break;

				case 'cur':
					$title = $fromRelRev->getPageAsLinkTarget();
					$toRev = $this->revisionStore->getRevisionByTitle( $title );
					if ( !$toRev ) {
						$title = Title::newFromLinkTarget( $title );
						$this->dieWithError(
							[ 'apierror-missingrev-title', wfEscapeWikiText( $title->getPrefixedText() ) ], 'nosuchrevid'
						);
					}
					$toRelRev = $toRev;
					$toValsRev = $toRev;
					break;
			}
		} else {
			list( $toRev, $toRelRev, $toValsRev ) = $this->getDiffRevision( 'to', $params );
		}

		// Handle missing from or to revisions (should never happen)
		// @codeCoverageIgnoreStart
		if ( !$fromRev || !$toRev ) {
			$this->dieWithError( 'apierror-baddiff' );
		}
		// @codeCoverageIgnoreEnd

		// Handle revdel
		if ( !$fromRev->audienceCan(
			RevisionRecord::DELETED_TEXT, RevisionRecord::FOR_THIS_USER, $this->getUser()
		) ) {
			$this->dieWithError( [ 'apierror-missingcontent-revid', $fromRev->getId() ], 'missingcontent' );
		}
		if ( !$toRev->audienceCan(
			RevisionRecord::DELETED_TEXT, RevisionRecord::FOR_THIS_USER, $this->getUser()
		) ) {
			$this->dieWithError( [ 'apierror-missingcontent-revid', $toRev->getId() ], 'missingcontent' );
		}

		// Get the diff
		$context = new DerivativeContext( $this->getContext() );
		if ( $fromRelRev && $fromRelRev->getPageAsLinkTarget() ) {
			$context->setTitle( Title::newFromLinkTarget( $fromRelRev->getPageAsLinkTarget() ) );
		} elseif ( $toRelRev && $toRelRev->getPageAsLinkTarget() ) {
			$context->setTitle( Title::newFromLinkTarget( $toRelRev->getPageAsLinkTarget() ) );
		} else {
			$guessedTitle = $this->guessTitle();
			if ( $guessedTitle ) {
				$context->setTitle( $guessedTitle );
			}
		}
		$de = new DifferenceEngine( $context );
		$de->setRevisions( $fromRev, $toRev );
		if ( $params['slots'] === null ) {
			$difftext = $de->getDiffBody();
			if ( $difftext === false ) {
				$this->dieWithError( 'apierror-baddiff' );
			}
		} else {
			$difftext = [];
			foreach ( $params['slots'] as $role ) {
				$difftext[$role] = $de->getDiffBodyForRole( $role );
			}
		}

		// Fill in the response
		$vals = [];
		$this->setVals( $vals, 'from', $fromValsRev );
		$this->setVals( $vals, 'to', $toValsRev );

		if ( isset( $this->props['rel'] ) ) {
			if ( !$fromRev instanceof MutableRevisionRecord ) {
				$rev = $this->revisionStore->getPreviousRevision( $fromRev );
				if ( $rev ) {
					$vals['prev'] = $rev->getId();
				}
			}
			if ( !$toRev instanceof MutableRevisionRecord ) {
				$rev = $this->revisionStore->getNextRevision( $toRev );
				if ( $rev ) {
					$vals['next'] = $rev->getId();
				}
			}
		}

		if ( isset( $this->props['diffsize'] ) ) {
			$vals['diffsize'] = 0;
			foreach ( (array)$difftext as $text ) {
				$vals['diffsize'] += strlen( $text );
			}
		}
		if ( isset( $this->props['diff'] ) ) {
			if ( is_array( $difftext ) ) {
				ApiResult::setArrayType( $difftext, 'kvp', 'diff' );
				$vals['bodies'] = $difftext;
			} else {
				ApiResult::setContentValue( $vals, 'body', $difftext );
			}
		}

		// Diffs can be really big and there's little point in having
		// ApiResult truncate it to an empty response since the diff is the
		// whole reason this module exists. So pass NO_SIZE_CHECK here.
		$this->getResult()->addValue( null, $this->getModuleName(), $vals, ApiResult::NO_SIZE_CHECK );
	}

	/**
	 * Load a revision by ID
	 *
	 * Falls back to checking the archive table if appropriate.
	 *
	 * @param int $id
	 * @return RevisionRecord|null
	 */
	private function getRevisionById( $id ) {
		$rev = $this->revisionStore->getRevisionById( $id );
		if ( !$rev && $this->getUser()->isAllowedAny( 'deletedtext', 'undelete' ) ) {
			// Try the 'archive' table
			$arQuery = $this->revisionStore->getArchiveQueryInfo();
			$row = $this->getDB()->selectRow(
				$arQuery['tables'],
				array_merge(
					$arQuery['fields'],
					[ 'ar_namespace', 'ar_title' ]
				),
				[ 'ar_rev_id' => $id ],
				__METHOD__,
				[],
				$arQuery['joins']
			);
			if ( $row ) {
				$rev = $this->revisionStore->newRevisionFromArchiveRow( $row );
				$rev->isArchive = true;
			}
		}
		return $rev;
	}

	/**
	 * Guess an appropriate default Title for this request
	 *
	 * @return Title|null
	 */
	private function guessTitle() {
		if ( $this->guessedTitle !== false ) {
			return $this->guessedTitle;
		}

		$this->guessedTitle = null;
		$params = $this->extractRequestParams();

		foreach ( [ 'from', 'to' ] as $prefix ) {
			if ( $params["{$prefix}rev"] !== null ) {
				$rev = $this->getRevisionById( $params["{$prefix}rev"] );
				if ( $rev ) {
					$this->guessedTitle = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );
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

		return $this->guessedTitle;
	}

	/**
	 * Guess an appropriate default content model for this request
	 * @param string $role Slot for which to guess the model
	 * @return string|null Guessed content model
	 */
	private function guessModel( $role ) {
		$params = $this->extractRequestParams();

		$title = null;
		foreach ( [ 'from', 'to' ] as $prefix ) {
			if ( $params["{$prefix}rev"] !== null ) {
				$rev = $this->getRevisionById( $params["{$prefix}rev"] );
				if ( $rev && $rev->hasSlot( $role ) ) {
					return $rev->getSlot( $role, RevisionRecord::RAW )->getModel();
				}
			}
		}

		$guessedTitle = $this->guessTitle();
		if ( $guessedTitle ) {
			return $this->slotRoleRegistry->getRoleHandler( $role )->getDefaultModel( $guessedTitle );
		}

		if ( isset( $params["fromcontentmodel-$role"] ) ) {
			return $params["fromcontentmodel-$role"];
		}
		if ( isset( $params["tocontentmodel-$role"] ) ) {
			return $params["tocontentmodel-$role"];
		}

		if ( $role === SlotRecord::MAIN ) {
			if ( isset( $params['fromcontentmodel'] ) ) {
				return $params['fromcontentmodel'];
			}
			if ( isset( $params['tocontentmodel'] ) ) {
				return $params['tocontentmodel'];
			}
		}

		return null;
	}

	/**
	 * Get the RevisionRecord for one side of the diff
	 *
	 * This uses the appropriate set of parameters to determine what content
	 * should be diffed.
	 *
	 * Returns three values:
	 * - A RevisionRecord holding the content
	 * - The revision specified, if any, even if content was supplied
	 * - The revision to pass to setVals(), if any
	 *
	 * @param string $prefix 'from' or 'to'
	 * @param array $params
	 * @return array [ RevisionRecord|null, RevisionRecord|null, RevisionRecord|null ]
	 */
	private function getDiffRevision( $prefix, array $params ) {
		// Back compat params
		$this->requireMaxOneParameter( $params, "{$prefix}text", "{$prefix}slots" );
		$this->requireMaxOneParameter( $params, "{$prefix}section", "{$prefix}slots" );
		if ( $params["{$prefix}text"] !== null ) {
			$params["{$prefix}slots"] = [ SlotRecord::MAIN ];
			$params["{$prefix}text-main"] = $params["{$prefix}text"];
			$params["{$prefix}section-main"] = null;
			$params["{$prefix}contentmodel-main"] = $params["{$prefix}contentmodel"];
			$params["{$prefix}contentformat-main"] = $params["{$prefix}contentformat"];
		}

		$title = null;
		$rev = null;
		$suppliedContent = $params["{$prefix}slots"] !== null;

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
			$rev = $this->getRevisionById( $revId );
			if ( !$rev ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $revId ] );
			}
			$title = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );

			// If we don't have supplied content, return here. Otherwise,
			// continue on below with the supplied content.
			if ( !$suppliedContent ) {
				$newRev = $rev;

				// Deprecated 'fromsection'/'tosection'
				if ( isset( $params["{$prefix}section"] ) ) {
					$section = $params["{$prefix}section"];
					$newRev = MutableRevisionRecord::newFromParentRevision( $rev );
					$content = $rev->getContent( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER,
						$this->getUser() );
					if ( !$content ) {
						$this->dieWithError(
							[ 'apierror-missingcontent-revid-role', $rev->getId(), SlotRecord::MAIN ], 'missingcontent'
						);
					}
					$content = $content ? $content->getSection( $section ) : null;
					if ( !$content ) {
						$this->dieWithError(
							[ "apierror-compare-nosuch{$prefix}section", wfEscapeWikiText( $section ) ],
							"nosuch{$prefix}section"
						);
					}
					$newRev->setContent( SlotRecord::MAIN, $content );
				}

				return [ $newRev, $rev, $rev ];
			}
		}

		// Override $content based on supplied text
		if ( !$title ) {
			$title = $this->guessTitle();
		}
		if ( $rev ) {
			$newRev = MutableRevisionRecord::newFromParentRevision( $rev );
		} else {
			$newRev = $this->revisionStore->newMutableRevisionFromArray( [
				'title' => $title ?: Title::makeTitle( NS_SPECIAL, 'Badtitle/' . __METHOD__ )
			] );
		}
		foreach ( $params["{$prefix}slots"] as $role ) {
			$text = $params["{$prefix}text-{$role}"];
			if ( $text === null ) {
				// The SlotRecord::MAIN role can't be deleted
				if ( $role === SlotRecord::MAIN ) {
					$this->dieWithError( [ 'apierror-compare-maintextrequired', $prefix ] );
				}

				// These parameters make no sense without text. Reject them to avoid
				// confusion.
				foreach ( [ 'section', 'contentmodel', 'contentformat' ] as $param ) {
					if ( isset( $params["{$prefix}{$param}-{$role}"] ) ) {
						$this->dieWithError( [
							'apierror-compare-notext',
							wfEscapeWikiText( "{$prefix}{$param}-{$role}" ),
							wfEscapeWikiText( "{$prefix}text-{$role}" ),
						] );
					}
				}

				$newRev->removeSlot( $role );
				continue;
			}

			$model = $params["{$prefix}contentmodel-{$role}"];
			$format = $params["{$prefix}contentformat-{$role}"];

			if ( !$model && $rev && $rev->hasSlot( $role ) ) {
				$model = $rev->getSlot( $role, RevisionRecord::RAW )->getModel();
			}
			if ( !$model && $title && $role === SlotRecord::MAIN ) {
				// @todo: Use SlotRoleRegistry and do this for all slots
				$model = $title->getContentModel();
			}
			if ( !$model ) {
				$model = $this->guessModel( $role );
			}
			if ( !$model ) {
				$model = CONTENT_MODEL_WIKITEXT;
				$this->addWarning( [ 'apiwarn-compare-nocontentmodel', $model ] );
			}

			try {
				$content = ContentHandler::makeContent( $text, $title, $model, $format );
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

			$section = $params["{$prefix}section-{$role}"];
			if ( $section !== null && $section !== '' ) {
				if ( !$rev ) {
					$this->dieWithError( "apierror-compare-no{$prefix}revision" );
				}
				$oldContent = $rev->getContent( $role, RevisionRecord::FOR_THIS_USER, $this->getUser() );
				if ( !$oldContent ) {
					$this->dieWithError(
						[ 'apierror-missingcontent-revid-role', $rev->getId(), wfEscapeWikiText( $role ) ],
						'missingcontent'
					);
				}
				if ( !$oldContent->getContentHandler()->supportsSections() ) {
					$this->dieWithError( [ 'apierror-sectionsnotsupported', $content->getModel() ] );
				}
				try {
					$content = $oldContent->replaceSection( $section, $content, '' );
				} catch ( Exception $ex ) {
					// Probably a content model mismatch.
					$content = null;
				}
				if ( !$content ) {
					$this->dieWithError( [ 'apierror-sectionreplacefailed' ] );
				}
			}

			// Deprecated 'fromsection'/'tosection'
			if ( $role === SlotRecord::MAIN && isset( $params["{$prefix}section"] ) ) {
				$section = $params["{$prefix}section"];
				$content = $content->getSection( $section );
				if ( !$content ) {
					$this->dieWithError(
						[ "apierror-compare-nosuch{$prefix}section", wfEscapeWikiText( $section ) ],
						"nosuch{$prefix}section"
					);
				}
			}

			$newRev->setContent( $role, $content );
		}
		return [ $newRev, $rev, null ];
	}

	/**
	 * Set value fields from a RevisionRecord object
	 *
	 * @param array &$vals Result array to set data into
	 * @param string $prefix 'from' or 'to'
	 * @param RevisionRecord|null $rev
	 */
	private function setVals( &$vals, $prefix, $rev ) {
		if ( $rev ) {
			$title = $rev->getPageAsLinkTarget();
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
			if ( $rev->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
				$vals["{$prefix}texthidden"] = true;
				$anyHidden = true;
			}

			if ( $rev->isDeleted( RevisionRecord::DELETED_USER ) ) {
				$vals["{$prefix}userhidden"] = true;
				$anyHidden = true;
			}
			if ( isset( $this->props['user'] ) ) {
				$user = $rev->getUser( RevisionRecord::FOR_THIS_USER, $this->getUser() );
				if ( $user ) {
					$vals["{$prefix}user"] = $user->getName();
					$vals["{$prefix}userid"] = $user->getId();
				}
			}

			if ( $rev->isDeleted( RevisionRecord::DELETED_COMMENT ) ) {
				$vals["{$prefix}commenthidden"] = true;
				$anyHidden = true;
			}
			if ( isset( $this->props['comment'] ) || isset( $this->props['parsedcomment'] ) ) {
				$comment = $rev->getComment( RevisionRecord::FOR_THIS_USER, $this->getUser() );
				if ( $comment !== null ) {
					if ( isset( $this->props['comment'] ) ) {
						$vals["{$prefix}comment"] = $comment->text;
					}
					$vals["{$prefix}parsedcomment"] = Linker::formatComment(
						$comment->text, Title::newFromLinkTarget( $title )
					);
				}
			}

			if ( $anyHidden ) {
				$this->getMain()->setCacheMode( 'private' );
				if ( $rev->isDeleted( RevisionRecord::DELETED_RESTRICTED ) ) {
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
		$slotRoles = $this->slotRoleRegistry->getKnownRoles();
		sort( $slotRoles, SORT_STRING );

		// Parameters for the 'from' and 'to' content
		$fromToParams = [
			'title' => null,
			'id' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'rev' => [
				ApiBase::PARAM_TYPE => 'integer'
			],

			'slots' => [
				ApiBase::PARAM_TYPE => $slotRoles,
				ApiBase::PARAM_ISMULTI => true,
			],
			'text-{slot}' => [
				ApiBase::PARAM_TEMPLATE_VARS => [ 'slot' => 'slots' ], // fixed below
				ApiBase::PARAM_TYPE => 'text',
			],
			'section-{slot}' => [
				ApiBase::PARAM_TEMPLATE_VARS => [ 'slot' => 'slots' ], // fixed below
				ApiBase::PARAM_TYPE => 'string',
			],
			'contentformat-{slot}' => [
				ApiBase::PARAM_TEMPLATE_VARS => [ 'slot' => 'slots' ], // fixed below
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats(),
			],
			'contentmodel-{slot}' => [
				ApiBase::PARAM_TEMPLATE_VARS => [ 'slot' => 'slots' ], // fixed below
				ApiBase::PARAM_TYPE => ContentHandler::getContentModels(),
			],
			'pst' => false,

			'text' => [
				ApiBase::PARAM_TYPE => 'text',
				ApiBase::PARAM_DEPRECATED => true,
			],
			'contentformat' => [
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats(),
				ApiBase::PARAM_DEPRECATED => true,
			],
			'contentmodel' => [
				ApiBase::PARAM_TYPE => ContentHandler::getContentModels(),
				ApiBase::PARAM_DEPRECATED => true,
			],
			'section' => [
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_DEPRECATED => true,
			],
		];

		$ret = [];
		foreach ( $fromToParams as $k => $v ) {
			if ( isset( $v[ApiBase::PARAM_TEMPLATE_VARS]['slot'] ) ) {
				$v[ApiBase::PARAM_TEMPLATE_VARS]['slot'] = 'fromslots';
			}
			$ret["from$k"] = $v;
		}
		foreach ( $fromToParams as $k => $v ) {
			if ( isset( $v[ApiBase::PARAM_TEMPLATE_VARS]['slot'] ) ) {
				$v[ApiBase::PARAM_TEMPLATE_VARS]['slot'] = 'toslots';
			}
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

		$ret['slots'] = [
			ApiBase::PARAM_TYPE => $slotRoles,
			ApiBase::PARAM_ISMULTI => true,
			ApiBase::PARAM_ALL => true,
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
