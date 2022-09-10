<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * A base class for functions common to producing a list of revisions.
 *
 * @stable to extend
 *
 * @ingroup API
 */
abstract class ApiQueryRevisionsBase extends ApiQueryGeneratorBase {

	// region Constants for internal use. Don't use externally.
	/** @name Constants for internal use. Don't use externally. */

	// Bits to indicate the results of the revdel permission check on a revision,
	// see self::checkRevDel()
	private const IS_DELETED = 1; // Whether the field is revision-deleted
	private const CANNOT_VIEW = 2; // Whether the user cannot view the field due to revdel

	// endregion

	protected $limit, $diffto, $difftotext, $difftotextpst, $expandTemplates, $generateXML,
		$section, $parseContent, $fetchContent, $contentFormat, $setParsedLimit = true,
		$slotRoles = null, $needSlots;

	protected $fld_ids = false, $fld_flags = false, $fld_timestamp = false,
		$fld_size = false, $fld_slotsize = false, $fld_sha1 = false, $fld_slotsha1 = false,
		$fld_comment = false, $fld_parsedcomment = false, $fld_user = false, $fld_userid = false,
		$fld_content = false, $fld_tags = false, $fld_contentmodel = false, $fld_roles = false,
		$fld_parsetree = false;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var ParserFactory */
	private $parserFactory;

	/** @var SlotRoleRegistry */
	private $slotRoleRegistry;

	/** @var ContentRenderer */
	private $contentRenderer;

	/** @var ContentTransformer */
	private $contentTransformer;

	/**
	 * @since 1.37 Support injection of services
	 * @stable to call
	 * @param ApiQuery $queryModule
	 * @param string $moduleName
	 * @param string $paramPrefix
	 * @param RevisionStore|null $revisionStore
	 * @param IContentHandlerFactory|null $contentHandlerFactory
	 * @param ParserFactory|null $parserFactory
	 * @param SlotRoleRegistry|null $slotRoleRegistry
	 * @param ContentRenderer|null $contentRenderer
	 * @param ContentTransformer|null $contentTransformer
	 */
	public function __construct(
		ApiQuery $queryModule,
		$moduleName,
		$paramPrefix = '',
		RevisionStore $revisionStore = null,
		IContentHandlerFactory $contentHandlerFactory = null,
		ParserFactory $parserFactory = null,
		SlotRoleRegistry $slotRoleRegistry = null,
		ContentRenderer $contentRenderer = null,
		ContentTransformer $contentTransformer = null
	) {
		parent::__construct( $queryModule, $moduleName, $paramPrefix );
		// This class is part of the stable interface and
		// therefor fallback to global state, if services are not provided
		$services = MediaWikiServices::getInstance();
		$this->revisionStore = $revisionStore ?? $services->getRevisionStore();
		$this->contentHandlerFactory = $contentHandlerFactory ?? $services->getContentHandlerFactory();
		$this->parserFactory = $parserFactory ?? $services->getParserFactory();
		$this->slotRoleRegistry = $slotRoleRegistry ?? $services->getSlotRoleRegistry();
		$this->contentRenderer = $contentRenderer ?? $services->getContentRenderer();
		$this->contentTransformer = $contentTransformer ?? $services->getContentTransformer();
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	abstract protected function run( ApiPageSet $resultPageSet = null );

	/**
	 * Parse the parameters into the various instance fields.
	 *
	 * @param array $params
	 */
	protected function parseParameters( $params ) {
		$prop = array_fill_keys( $params['prop'], true );

		$this->fld_ids = isset( $prop['ids'] );
		$this->fld_flags = isset( $prop['flags'] );
		$this->fld_timestamp = isset( $prop['timestamp'] );
		$this->fld_comment = isset( $prop['comment'] );
		$this->fld_parsedcomment = isset( $prop['parsedcomment'] );
		$this->fld_size = isset( $prop['size'] );
		$this->fld_slotsize = isset( $prop['slotsize'] );
		$this->fld_sha1 = isset( $prop['sha1'] );
		$this->fld_slotsha1 = isset( $prop['slotsha1'] );
		$this->fld_content = isset( $prop['content'] );
		$this->fld_contentmodel = isset( $prop['contentmodel'] );
		$this->fld_userid = isset( $prop['userid'] );
		$this->fld_user = isset( $prop['user'] );
		$this->fld_tags = isset( $prop['tags'] );
		$this->fld_roles = isset( $prop['roles'] );
		$this->fld_parsetree = isset( $prop['parsetree'] );

		$this->slotRoles = $params['slots'];

		if ( $this->slotRoles !== null ) {
			if ( $this->fld_parsetree ) {
				$this->dieWithError( [
					'apierror-invalidparammix-cannotusewith',
					$this->encodeParamName( 'prop=parsetree' ),
					$this->encodeParamName( 'slots' ),
				], 'invalidparammix' );
			}
			foreach ( [
				'expandtemplates', 'generatexml', 'parse', 'diffto', 'difftotext', 'difftotextpst',
				'contentformat'
			] as $p ) {
				if ( $params[$p] !== null && $params[$p] !== false ) {
					$this->dieWithError( [
						'apierror-invalidparammix-cannotusewith',
						$this->encodeParamName( $p ),
						$this->encodeParamName( 'slots' ),
					], 'invalidparammix' );
				}
			}
		}

		if ( !empty( $params['contentformat'] ) ) {
			$this->contentFormat = $params['contentformat'];
		}

		$this->limit = $params['limit'];

		if ( $params['difftotext'] !== null ) {
			$this->difftotext = $params['difftotext'];
			$this->difftotextpst = $params['difftotextpst'];
		} elseif ( $params['diffto'] !== null ) {
			if ( $params['diffto'] == 'cur' ) {
				$params['diffto'] = 0;
			}
			if ( ( !ctype_digit( $params['diffto'] ) || $params['diffto'] < 0 )
				&& $params['diffto'] != 'prev' && $params['diffto'] != 'next'
			) {
				$p = $this->getModulePrefix();
				$this->dieWithError( [ 'apierror-baddiffto', $p ], 'diffto' );
			}
			// Check whether the revision exists and is readable,
			// DifferenceEngine returns a rather ambiguous empty
			// string if that's not the case
			if ( $params['diffto'] != 0 ) {
				$difftoRev = $this->revisionStore->getRevisionById( $params['diffto'] );
				if ( !$difftoRev ) {
					$this->dieWithError( [ 'apierror-nosuchrevid', $params['diffto'] ] );
				}
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable T240141
				$revDel = $this->checkRevDel( $difftoRev, RevisionRecord::DELETED_TEXT );
				if ( $revDel & self::CANNOT_VIEW ) {
					$this->addWarning( [ 'apiwarn-difftohidden', $difftoRev->getId() ] );
					$params['diffto'] = null;
				}
			}
			$this->diffto = $params['diffto'];
		}

		$this->fetchContent = $this->fld_content || $this->diffto !== null
			|| $this->difftotext !== null || $this->fld_parsetree;

		$smallLimit = false;
		if ( $this->fetchContent ) {
			$smallLimit = true;
			$this->expandTemplates = $params['expandtemplates'];
			$this->generateXML = $params['generatexml'];
			$this->parseContent = $params['parse'];
			if ( $this->parseContent ) {
				// Must manually initialize unset limit
				if ( $this->limit === null ) {
					$this->limit = 1;
				}
			}
			$this->section = $params['section'] ?? false;
		}

		$userMax = $this->parseContent ? 1 : ( $smallLimit ? ApiBase::LIMIT_SML1 : ApiBase::LIMIT_BIG1 );
		$botMax = $this->parseContent ? 1 : ( $smallLimit ? ApiBase::LIMIT_SML2 : ApiBase::LIMIT_BIG2 );
		if ( $this->limit == 'max' ) {
			$this->limit = $this->getMain()->canApiHighLimits() ? $botMax : $userMax;
			if ( $this->setParsedLimit ) {
				$this->getResult()->addParsedLimit( $this->getModuleName(), $this->limit );
			}
		}

		$this->limit = $this->getMain()->getParamValidator()->validateValue(
			$this, 'limit', $this->limit ?? 10, [
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => $userMax,
				IntegerDef::PARAM_MAX2 => $botMax,
				IntegerDef::PARAM_IGNORE_RANGE => true,
			]
		);

		$this->needSlots = $this->fetchContent || $this->fld_contentmodel ||
			$this->fld_slotsize || $this->fld_slotsha1;
		if ( $this->needSlots && $this->slotRoles === null ) {
			$encParam = $this->encodeParamName( 'slots' );
			$name = $this->getModuleName();
			$parent = $this->getParent();
			$parentParam = $parent->encodeParamName( $parent->getModuleManager()->getModuleGroup( $name ) );
			$this->addDeprecation(
				[ 'apiwarn-deprecation-missingparam', $encParam ],
				"action=query&{$parentParam}={$name}&!{$encParam}"
			);
		}
	}

	/**
	 * Test revision deletion status
	 * @param RevisionRecord $revision Revision to check
	 * @param int $field One of the RevisionRecord::DELETED_* constants
	 * @return int Revision deletion status flags. Bitwise OR of
	 *  self::IS_DELETED and self::CANNOT_VIEW, as appropriate.
	 */
	private function checkRevDel( RevisionRecord $revision, $field ) {
		$ret = $revision->isDeleted( $field ) ? self::IS_DELETED : 0;
		if ( $ret ) {
			$canSee = $revision->userCan( $field, $this->getAuthority() );
			$ret |= ( $canSee ? 0 : self::CANNOT_VIEW );
		}
		return $ret;
	}

	/**
	 * Extract information from the RevisionRecord
	 *
	 * @since 1.32, takes a RevisionRecord instead of a Revision
	 * @param RevisionRecord $revision
	 * @param stdClass $row Should have a field 'ts_tags' if $this->fld_tags is set
	 * @return array
	 */
	protected function extractRevisionInfo( RevisionRecord $revision, $row ) {
		$vals = [];
		$anyHidden = false;

		if ( $this->fld_ids ) {
			$vals['revid'] = (int)$revision->getId();
			if ( $revision->getParentId() !== null ) {
				$vals['parentid'] = (int)$revision->getParentId();
			}
		}

		if ( $this->fld_flags ) {
			$vals['minor'] = $revision->isMinor();
		}

		if ( $this->fld_user || $this->fld_userid ) {
			$revDel = $this->checkRevDel( $revision, RevisionRecord::DELETED_USER );
			if ( $revDel & self::IS_DELETED ) {
				$vals['userhidden'] = true;
				$anyHidden = true;
			}
			if ( !( $revDel & self::CANNOT_VIEW ) ) {
				$u = $revision->getUser( RevisionRecord::RAW );
				if ( $this->fld_user ) {
					$vals['user'] = $u->getName();
				}
				if ( !$u->isRegistered() ) {
					$vals['anon'] = true;
				}

				if ( $this->fld_userid ) {
					$vals['userid'] = $u->getId();
				}
			}
		}

		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $revision->getTimestamp() );
		}

		if ( $this->fld_size ) {
			try {
				$vals['size'] = (int)$revision->getSize();
			} catch ( RevisionAccessException $e ) {
				// Back compat: If there's no size, return 0.
				// @todo: Gergő says to mention T198099 as a "todo" here.
				$vals['size'] = 0;
			}
		}

		if ( $this->fld_sha1 ) {
			$revDel = $this->checkRevDel( $revision, RevisionRecord::DELETED_TEXT );
			if ( $revDel & self::IS_DELETED ) {
				$vals['sha1hidden'] = true;
				$anyHidden = true;
			}
			if ( !( $revDel & self::CANNOT_VIEW ) ) {
				try {
					$vals['sha1'] = Wikimedia\base_convert( $revision->getSha1(), 36, 16, 40 );
				} catch ( RevisionAccessException $e ) {
					// Back compat: If there's no sha1, return empty string.
					// @todo: Gergő says to mention T198099 as a "todo" here.
					$vals['sha1'] = '';
				}
			}
		}

		try {
			if ( $this->fld_roles ) {
				$vals['roles'] = $revision->getSlotRoles();
			}

			if ( $this->needSlots ) {
				$revDel = $this->checkRevDel( $revision, RevisionRecord::DELETED_TEXT );
				if ( ( $this->fld_slotsha1 || $this->fetchContent ) && ( $revDel & self::IS_DELETED ) ) {
					$anyHidden = true;
				}
				$vals = array_merge( $vals, $this->extractAllSlotInfo( $revision, $revDel ) );
			}
		} catch ( RevisionAccessException $ex ) {
			// This is here so T212428 doesn't spam the log.
			// TODO: find out why T212428 happens in the first place!
			$vals['slotsmissing'] = true;

			LoggerFactory::getInstance( 'api-warning' )->error(
				'Failed to access revision slots',
				[ 'revision' => $revision->getId(), 'exception' => $ex, ]
			);
		}

		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			$revDel = $this->checkRevDel( $revision, RevisionRecord::DELETED_COMMENT );
			if ( $revDel & self::IS_DELETED ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}
			if ( !( $revDel & self::CANNOT_VIEW ) ) {
				$comment = $revision->getComment( RevisionRecord::RAW );
				$comment = $comment->text ?? '';

				if ( $this->fld_comment ) {
					$vals['comment'] = $comment;
				}

				if ( $this->fld_parsedcomment ) {
					$vals['parsedcomment'] = Linker::formatComment(
						$comment, Title::newFromLinkTarget( $revision->getPageAsLinkTarget() )
					);
				}
			}
		}

		if ( $this->fld_tags ) {
			if ( $row->ts_tags ) {
				$tags = explode( ',', $row->ts_tags );
				ApiResult::setIndexedTagName( $tags, 'tag' );
				$vals['tags'] = $tags;
			} else {
				$vals['tags'] = [];
			}
		}

		if ( $anyHidden && $revision->isDeleted( RevisionRecord::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		return $vals;
	}

	/**
	 * Extracts information about all relevant slots.
	 *
	 * @param RevisionRecord $revision
	 * @param int $revDel
	 *
	 * @return array
	 * @throws ApiUsageException
	 */
	private function extractAllSlotInfo( RevisionRecord $revision, $revDel ): array {
		$vals = [];

		if ( $this->slotRoles === null ) {
			try {
				$slot = $revision->getSlot( SlotRecord::MAIN, RevisionRecord::RAW );
			} catch ( RevisionAccessException $e ) {
				// Back compat: If there's no slot, there's no content, so set 'textmissing'
				// @todo: Gergő says to mention T198099 as a "todo" here.
				$vals['textmissing'] = true;
				$slot = null;
			}

			if ( $slot ) {
				$content = null;
				$vals += $this->extractSlotInfo( $slot, $revDel, $content );
				if ( !empty( $vals['nosuchsection'] ) ) {
					$this->dieWithError(
						[
							'apierror-nosuchsection-what',
							wfEscapeWikiText( $this->section ),
							$this->msg( 'revid', $revision->getId() )
						],
						'nosuchsection'
					);
				}
				if ( $content ) {
					$vals += $this->extractDeprecatedContent( $content, $revision );
				}
			}
		} else {
			$roles = array_intersect( $this->slotRoles, $revision->getSlotRoles() );
			$vals['slots'] = [
				ApiResult::META_KVP_MERGE => true,
			];
			foreach ( $roles as $role ) {
				try {
					$slot = $revision->getSlot( $role, RevisionRecord::RAW );
				} catch ( RevisionAccessException $e ) {
					// Don't error out here so the client can still process other slots/revisions.
					// @todo: Gergő says to mention T198099 as a "todo" here.
					$vals['slots'][$role]['missing'] = true;
					continue;
				}
				$content = null;
				$vals['slots'][$role] = $this->extractSlotInfo( $slot, $revDel, $content );
				// @todo Move this into extractSlotInfo() (and remove its $content parameter)
				// when extractDeprecatedContent() is no more.
				if ( $content ) {
					/** @var Content $content */
					$vals['slots'][$role]['contentmodel'] = $content->getModel();
					$vals['slots'][$role]['contentformat'] = $content->getDefaultFormat();
					ApiResult::setContentValue(
						$vals['slots'][$role],
						'content',
						$content->serialize()
					);
				}
			}
			ApiResult::setArrayType( $vals['slots'], 'kvp', 'role' );
			ApiResult::setIndexedTagName( $vals['slots'], 'slot' );
		}
		return $vals;
	}

	/**
	 * Extract information from the SlotRecord
	 *
	 * @param SlotRecord $slot
	 * @param int $revDel Revdel status flags, from self::checkRevDel()
	 * @param Content|null &$content Set to the slot's content, if available
	 *  and $this->fetchContent is true
	 * @return array
	 */
	private function extractSlotInfo( SlotRecord $slot, $revDel, &$content = null ) {
		$vals = [];
		ApiResult::setArrayType( $vals, 'assoc' );

		if ( $this->fld_slotsize ) {
			$vals['size'] = (int)$slot->getSize();
		}

		if ( $this->fld_slotsha1 ) {
			if ( $revDel & self::IS_DELETED ) {
				$vals['sha1hidden'] = true;
			}
			if ( !( $revDel & self::CANNOT_VIEW ) ) {
				if ( $slot->getSha1() != '' ) {
					$vals['sha1'] = Wikimedia\base_convert( $slot->getSha1(), 36, 16, 40 );
				} else {
					$vals['sha1'] = '';
				}
			}
		}

		if ( $this->fld_contentmodel ) {
			$vals['contentmodel'] = $slot->getModel();
		}

		$content = null;
		if ( $this->fetchContent ) {
			if ( $revDel & self::IS_DELETED ) {
				$vals['texthidden'] = true;
			}
			if ( !( $revDel & self::CANNOT_VIEW ) ) {
				try {
					$content = $slot->getContent();
				} catch ( RevisionAccessException $e ) {
					// @todo: Gergő says to mention T198099 as a "todo" here.
					$vals['textmissing'] = true;
				}
				// Expand templates after getting section content because
				// template-added sections don't count and Parser::preprocess()
				// will have less input
				if ( $content && $this->section !== false ) {
					$content = $content->getSection( $this->section );
					if ( !$content ) {
						$vals['nosuchsection'] = true;
					}
				}
			}
		}

		return $vals;
	}

	/**
	 * Format a Content using deprecated options
	 * @param Content $content Content to format
	 * @param RevisionRecord $revision Revision being processed
	 * @return array
	 */
	private function extractDeprecatedContent( Content $content, RevisionRecord $revision ) {
		$vals = [];
		$title = Title::newFromLinkTarget( $revision->getPageAsLinkTarget() );

		if ( $this->fld_parsetree || ( $this->fld_content && $this->generateXML ) ) {
			if ( $content->getModel() === CONTENT_MODEL_WIKITEXT ) {
				/** @var WikitextContent $content */
				'@phan-var WikitextContent $content';
				$t = $content->getText(); # note: don't set $text

				$parser = $this->parserFactory->create();
				$parser->startExternalParse(
					$title,
					ParserOptions::newFromContext( $this->getContext() ),
					Parser::OT_PREPROCESS
				);
				$dom = $parser->preprocessToDom( $t );
				if ( is_callable( [ $dom, 'saveXML' ] ) ) {
					// @phan-suppress-next-line PhanUndeclaredMethod
					$xml = $dom->saveXML();
				} else {
					// @phan-suppress-next-line PhanUndeclaredMethod
					$xml = $dom->__toString();
				}
				$vals['parsetree'] = $xml;
			} else {
				$vals['badcontentformatforparsetree'] = true;
				$this->addWarning(
					[
						'apierror-parsetree-notwikitext-title',
						wfEscapeWikiText( $title->getPrefixedText() ),
						$content->getModel()
					],
					'parsetree-notwikitext'
				);
			}
		}

		if ( $this->fld_content ) {
			$text = null;

			if ( $this->expandTemplates && !$this->parseContent ) {
				if ( $content->getModel() === CONTENT_MODEL_WIKITEXT ) {
					/** @var WikitextContent $content */
					'@phan-var WikitextContent $content';
					$text = $content->getText();

					$text = $this->parserFactory->create()->preprocess(
						$text,
						$title,
						ParserOptions::newFromContext( $this->getContext() )
					);
				} else {
					$this->addWarning( [
						'apierror-templateexpansion-notwikitext',
						wfEscapeWikiText( $title->getPrefixedText() ),
						$content->getModel()
					] );
					$vals['badcontentformat'] = true;
					$text = false;
				}
			}
			if ( $this->parseContent ) {
				$po = $this->contentRenderer->getParserOutput(
					$content,
					$title,
					$revision->getId(),
					ParserOptions::newFromContext( $this->getContext() )
				);
				$text = $po->getText();
			}

			if ( $text === null ) {
				$format = $this->contentFormat ?: $content->getDefaultFormat();
				$model = $content->getModel();

				if ( !$content->isSupportedFormat( $format ) ) {
					$name = wfEscapeWikiText( $title->getPrefixedText() );
					$this->addWarning( [ 'apierror-badformat', $this->contentFormat, $model, $name ] );
					$vals['badcontentformat'] = true;
					$text = false;
				} else {
					$text = $content->serialize( $format );
					// always include format and model.
					// Format is needed to deserialize, model is needed to interpret.
					$vals['contentformat'] = $format;
					$vals['contentmodel'] = $model;
				}
			}

			if ( $text !== false ) {
				ApiResult::setContentValue( $vals, 'content', $text );
			}
		}

		if ( $content && ( $this->diffto !== null || $this->difftotext !== null ) ) {
			static $n = 0; // Number of uncached diffs we've had

			if ( $n < $this->getConfig()->get( MainConfigNames::APIMaxUncachedDiffs ) ) {
				$vals['diff'] = [];
				$context = new DerivativeContext( $this->getContext() );
				$context->setTitle( $title );
				$handler = $content->getContentHandler();

				if ( $this->difftotext !== null ) {
					$model = $title->getContentModel();

					if ( $this->contentFormat
						&& !$this->contentHandlerFactory->getContentHandler( $model )
							->isSupportedFormat( $this->contentFormat )
					) {
						$name = wfEscapeWikiText( $title->getPrefixedText() );
						$this->addWarning( [ 'apierror-badformat', $this->contentFormat, $model, $name ] );
						$vals['diff']['badcontentformat'] = true;
						$engine = null;
					} else {
						$difftocontent = $this->contentHandlerFactory->getContentHandler( $model )
							->unserializeContent( $this->difftotext, $this->contentFormat );

						if ( $this->difftotextpst ) {
							$popts = ParserOptions::newFromContext( $this->getContext() );
							$difftocontent = $this->contentTransformer->preSaveTransform(
								$difftocontent,
								$title,
								$this->getUser(),
								$popts
							);
						}

						$engine = $handler->createDifferenceEngine( $context );
						$engine->setContent( $content, $difftocontent );
					}
				} else {
					$engine = $handler->createDifferenceEngine( $context, $revision->getId(), $this->diffto );
					$vals['diff']['from'] = $engine->getOldid();
					$vals['diff']['to'] = $engine->getNewid();
				}
				if ( $engine ) {
					$difftext = $engine->getDiffBody();
					ApiResult::setContentValue( $vals['diff'], 'body', $difftext );
					if ( !$engine->wasCacheHit() ) {
						$n++;
					}
				}
			} else {
				$vals['diff']['notcached'] = true;
			}
		}

		return $vals;
	}

	/**
	 * @stable to override
	 * @param array $params
	 *
	 * @return string
	 */
	public function getCacheMode( $params ) {
		if ( $this->userCanSeeRevDel() ) {
			return 'private';
		}

		return 'public';
	}

	/**
	 * @stable to override
	 * @return array
	 * @throws MWException
	 */
	public function getAllowedParams() {
		$slotRoles = $this->slotRoleRegistry->getKnownRoles();
		sort( $slotRoles, SORT_STRING );

		return [
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'ids|timestamp|flags|comment|user',
				ParamValidator::PARAM_TYPE => [
					'ids',
					'flags',
					'timestamp',
					'user',
					'userid',
					'size',
					'slotsize',
					'sha1',
					'slotsha1',
					'contentmodel',
					'comment',
					'parsedcomment',
					'content',
					'tags',
					'roles',
					'parsetree',
				],
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-prop',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'ids' => 'apihelp-query+revisions+base-paramvalue-prop-ids',
					'flags' => 'apihelp-query+revisions+base-paramvalue-prop-flags',
					'timestamp' => 'apihelp-query+revisions+base-paramvalue-prop-timestamp',
					'user' => 'apihelp-query+revisions+base-paramvalue-prop-user',
					'userid' => 'apihelp-query+revisions+base-paramvalue-prop-userid',
					'size' => 'apihelp-query+revisions+base-paramvalue-prop-size',
					'slotsize' => 'apihelp-query+revisions+base-paramvalue-prop-slotsize',
					'sha1' => 'apihelp-query+revisions+base-paramvalue-prop-sha1',
					'slotsha1' => 'apihelp-query+revisions+base-paramvalue-prop-slotsha1',
					'contentmodel' => 'apihelp-query+revisions+base-paramvalue-prop-contentmodel',
					'comment' => 'apihelp-query+revisions+base-paramvalue-prop-comment',
					'parsedcomment' => 'apihelp-query+revisions+base-paramvalue-prop-parsedcomment',
					'content' => 'apihelp-query+revisions+base-paramvalue-prop-content',
					'tags' => 'apihelp-query+revisions+base-paramvalue-prop-tags',
					'roles' => 'apihelp-query+revisions+base-paramvalue-prop-roles',
					'parsetree' => [ 'apihelp-query+revisions+base-paramvalue-prop-parsetree',
						CONTENT_MODEL_WIKITEXT ],
				],
				EnumDef::PARAM_DEPRECATED_VALUES => [
					'parsetree' => true,
				],
			],
			'slots' => [
				ParamValidator::PARAM_TYPE => $slotRoles,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-slots',
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_ALL => true,
			],
			'limit' => [
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-limit',
			],
			'expandtemplates' => [
				ParamValidator::PARAM_DEFAULT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-expandtemplates',
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'generatexml' => [
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-generatexml',
			],
			'parse' => [
				ParamValidator::PARAM_DEFAULT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-parse',
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'section' => [
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-section',
			],
			'diffto' => [
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-diffto',
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'difftotext' => [
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-difftotext',
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'difftotextpst' => [
				ParamValidator::PARAM_DEFAULT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-difftotextpst',
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'contentformat' => [
				ParamValidator::PARAM_TYPE => $this->contentHandlerFactory->getAllContentFormats(),
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-contentformat',
				ParamValidator::PARAM_DEPRECATED => true,
			],
		];
	}
}
