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

/**
 * A base class for functions common to producing a list of revisions.
 *
 * @ingroup API
 */
abstract class ApiQueryRevisionsBase extends ApiQueryGeneratorBase {

	protected $limit, $diffto, $difftotext, $difftotextpst, $expandTemplates, $generateXML,
		$section, $parseContent, $fetchContent, $contentFormat, $setParsedLimit = true;

	protected $fld_ids = false, $fld_flags = false, $fld_timestamp = false,
		$fld_size = false, $fld_sha1 = false, $fld_comment = false,
		$fld_parsedcomment = false, $fld_user = false, $fld_userid = false,
		$fld_content = false, $fld_tags = false, $fld_contentmodel = false, $fld_parsetree = false;

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	abstract protected function run( ApiPageSet $resultPageSet = null );

	/**
	 * Parse the parameters into the various instance fields.
	 *
	 * @param array $params
	 */
	protected function parseParameters( $params ) {
		if ( !is_null( $params['difftotext'] ) ) {
			$this->difftotext = $params['difftotext'];
			$this->difftotextpst = $params['difftotextpst'];
		} elseif ( !is_null( $params['diffto'] ) ) {
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
				$difftoRev = Revision::newFromId( $params['diffto'] );
				if ( !$difftoRev ) {
					$this->dieWithError( [ 'apierror-nosuchrevid', $params['diffto'] ] );
				}
				if ( !$difftoRev->userCan( Revision::DELETED_TEXT, $this->getUser() ) ) {
					$this->addWarning( [ 'apiwarn-difftohidden', $difftoRev->getId() ] );
					$params['diffto'] = null;
				}
			}
			$this->diffto = $params['diffto'];
		}

		$prop = array_flip( $params['prop'] );

		$this->fld_ids = isset( $prop['ids'] );
		$this->fld_flags = isset( $prop['flags'] );
		$this->fld_timestamp = isset( $prop['timestamp'] );
		$this->fld_comment = isset( $prop['comment'] );
		$this->fld_parsedcomment = isset( $prop['parsedcomment'] );
		$this->fld_size = isset( $prop['size'] );
		$this->fld_sha1 = isset( $prop['sha1'] );
		$this->fld_content = isset( $prop['content'] );
		$this->fld_contentmodel = isset( $prop['contentmodel'] );
		$this->fld_userid = isset( $prop['userid'] );
		$this->fld_user = isset( $prop['user'] );
		$this->fld_tags = isset( $prop['tags'] );
		$this->fld_parsetree = isset( $prop['parsetree'] );

		if ( $this->fld_parsetree ) {
			$encParam = $this->encodeParamName( 'prop' );
			$name = $this->getModuleName();
			$parent = $this->getParent();
			$parentParam = $parent->encodeParamName( $parent->getModuleManager()->getModuleGroup( $name ) );
			$this->addDeprecation(
				[ 'apiwarn-deprecation-parameter', "{$encParam}=parsetree" ],
				"action=query&{$parentParam}={$name}&{$encParam}=parsetree"
			);
		}

		if ( !empty( $params['contentformat'] ) ) {
			$this->contentFormat = $params['contentformat'];
		}

		$this->limit = $params['limit'];

		$this->fetchContent = $this->fld_content || !is_null( $this->diffto )
			|| !is_null( $this->difftotext ) || $this->fld_parsetree;

		$smallLimit = false;
		if ( $this->fetchContent ) {
			$smallLimit = true;
			$this->expandTemplates = $params['expandtemplates'];
			$this->generateXML = $params['generatexml'];
			$this->parseContent = $params['parse'];
			if ( $this->parseContent ) {
				// Must manually initialize unset limit
				if ( is_null( $this->limit ) ) {
					$this->limit = 1;
				}
			}
			if ( isset( $params['section'] ) ) {
				$this->section = $params['section'];
			} else {
				$this->section = false;
			}
		}

		$userMax = $this->parseContent ? 1 : ( $smallLimit ? ApiBase::LIMIT_SML1 : ApiBase::LIMIT_BIG1 );
		$botMax = $this->parseContent ? 1 : ( $smallLimit ? ApiBase::LIMIT_SML2 : ApiBase::LIMIT_BIG2 );
		if ( $this->limit == 'max' ) {
			$this->limit = $this->getMain()->canApiHighLimits() ? $botMax : $userMax;
			if ( $this->setParsedLimit ) {
				$this->getResult()->addParsedLimit( $this->getModuleName(), $this->limit );
			}
		}

		if ( is_null( $this->limit ) ) {
			$this->limit = 10;
		}
		$this->validateLimit( 'limit', $this->limit, 1, $userMax, $botMax );
	}

	/**
	 * Extract information from the Revision
	 *
	 * @param Revision $revision
	 * @param object $row Should have a field 'ts_tags' if $this->fld_tags is set
	 * @return array
	 */
	protected function extractRevisionInfo( Revision $revision, $row ) {
		$title = $revision->getTitle();
		$user = $this->getUser();
		$vals = [];
		$anyHidden = false;

		if ( $this->fld_ids ) {
			$vals['revid'] = intval( $revision->getId() );
			if ( !is_null( $revision->getParentId() ) ) {
				$vals['parentid'] = intval( $revision->getParentId() );
			}
		}

		if ( $this->fld_flags ) {
			$vals['minor'] = $revision->isMinor();
		}

		if ( $this->fld_user || $this->fld_userid ) {
			if ( $revision->isDeleted( Revision::DELETED_USER ) ) {
				$vals['userhidden'] = true;
				$anyHidden = true;
			}
			if ( $revision->userCan( Revision::DELETED_USER, $user ) ) {
				if ( $this->fld_user ) {
					$vals['user'] = $revision->getUserText( Revision::RAW );
				}
				$userid = $revision->getUser( Revision::RAW );
				if ( !$userid ) {
					$vals['anon'] = true;
				}

				if ( $this->fld_userid ) {
					$vals['userid'] = $userid;
				}
			}
		}

		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $revision->getTimestamp() );
		}

		if ( $this->fld_size ) {
			if ( !is_null( $revision->getSize() ) ) {
				$vals['size'] = intval( $revision->getSize() );
			} else {
				$vals['size'] = 0;
			}
		}

		if ( $this->fld_sha1 ) {
			if ( $revision->isDeleted( Revision::DELETED_TEXT ) ) {
				$vals['sha1hidden'] = true;
				$anyHidden = true;
			}
			if ( $revision->userCan( Revision::DELETED_TEXT, $user ) ) {
				if ( $revision->getSha1() != '' ) {
					$vals['sha1'] = Wikimedia\base_convert( $revision->getSha1(), 36, 16, 40 );
				} else {
					$vals['sha1'] = '';
				}
			}
		}

		if ( $this->fld_contentmodel ) {
			$vals['contentmodel'] = $revision->getContentModel();
		}

		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			if ( $revision->isDeleted( Revision::DELETED_COMMENT ) ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}
			if ( $revision->userCan( Revision::DELETED_COMMENT, $user ) ) {
				$comment = $revision->getComment( Revision::RAW );

				if ( $this->fld_comment ) {
					$vals['comment'] = $comment;
				}

				if ( $this->fld_parsedcomment ) {
					$vals['parsedcomment'] = Linker::formatComment( $comment, $title );
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

		$content = null;
		global $wgParser;
		if ( $this->fetchContent ) {
			$content = $revision->getContent( Revision::FOR_THIS_USER, $this->getUser() );
			// Expand templates after getting section content because
			// template-added sections don't count and Parser::preprocess()
			// will have less input
			if ( $content && $this->section !== false ) {
				$content = $content->getSection( $this->section, false );
				if ( !$content ) {
					$this->dieWithError(
						[
							'apierror-nosuchsection-what',
							wfEscapeWikiText( $this->section ),
							$this->msg( 'revid', $revision->getId() )
						],
						'nosuchsection'
					);
				}
			}
			if ( $revision->isDeleted( Revision::DELETED_TEXT ) ) {
				$vals['texthidden'] = true;
				$anyHidden = true;
			} elseif ( !$content ) {
				$vals['textmissing'] = true;
			}
		}
		if ( $this->fld_parsetree || ( $this->fld_content && $this->generateXML ) ) {
			if ( $content ) {
				if ( $content->getModel() === CONTENT_MODEL_WIKITEXT ) {
					$t = $content->getNativeData(); # note: don't set $text

					$wgParser->startExternalParse(
						$title,
						ParserOptions::newFromContext( $this->getContext() ),
						Parser::OT_PREPROCESS
					);
					$dom = $wgParser->preprocessToDom( $t );
					if ( is_callable( [ $dom, 'saveXML' ] ) ) {
						$xml = $dom->saveXML();
					} else {
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
		}

		if ( $this->fld_content && $content ) {
			$text = null;

			if ( $this->expandTemplates && !$this->parseContent ) {
				# XXX: implement template expansion for all content types in ContentHandler?
				if ( $content->getModel() === CONTENT_MODEL_WIKITEXT ) {
					$text = $content->getNativeData();

					$text = $wgParser->preprocess(
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
				$po = $content->getParserOutput(
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

		if ( $content && ( !is_null( $this->diffto ) || !is_null( $this->difftotext ) ) ) {
			static $n = 0; // Number of uncached diffs we've had

			if ( $n < $this->getConfig()->get( 'APIMaxUncachedDiffs' ) ) {
				$vals['diff'] = [];
				$context = new DerivativeContext( $this->getContext() );
				$context->setTitle( $title );
				$handler = $revision->getContentHandler();

				if ( !is_null( $this->difftotext ) ) {
					$model = $title->getContentModel();

					if ( $this->contentFormat
						&& !ContentHandler::getForModelID( $model )->isSupportedFormat( $this->contentFormat )
					) {
						$name = wfEscapeWikiText( $title->getPrefixedText() );
						$this->addWarning( [ 'apierror-badformat', $this->contentFormat, $model, $name ] );
						$vals['diff']['badcontentformat'] = true;
						$engine = null;
					} else {
						$difftocontent = ContentHandler::makeContent(
							$this->difftotext,
							$title,
							$model,
							$this->contentFormat
						);

						if ( $this->difftotextpst ) {
							$popts = ParserOptions::newFromContext( $this->getContext() );
							$difftocontent = $difftocontent->preSaveTransform( $title, $user, $popts );
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

		if ( $anyHidden && $revision->isDeleted( Revision::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		return $vals;
	}

	public function getCacheMode( $params ) {
		if ( $this->userCanSeeRevDel() ) {
			return 'private';
		}

		return 'public';
	}

	public function getAllowedParams() {
		return [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'ids|timestamp|flags|comment|user',
				ApiBase::PARAM_TYPE => [
					'ids',
					'flags',
					'timestamp',
					'user',
					'userid',
					'size',
					'sha1',
					'contentmodel',
					'comment',
					'parsedcomment',
					'content',
					'tags',
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
					'sha1' => 'apihelp-query+revisions+base-paramvalue-prop-sha1',
					'contentmodel' => 'apihelp-query+revisions+base-paramvalue-prop-contentmodel',
					'comment' => 'apihelp-query+revisions+base-paramvalue-prop-comment',
					'parsedcomment' => 'apihelp-query+revisions+base-paramvalue-prop-parsedcomment',
					'content' => 'apihelp-query+revisions+base-paramvalue-prop-content',
					'tags' => 'apihelp-query+revisions+base-paramvalue-prop-tags',
					'parsetree' => [ 'apihelp-query+revisions+base-paramvalue-prop-parsetree',
						CONTENT_MODEL_WIKITEXT ],
				],
			],
			'limit' => [
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-limit',
			],
			'expandtemplates' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-expandtemplates',
				ApiBase::PARAM_DEPRECATED => true,
			],
			'generatexml' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-generatexml',
			],
			'parse' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-parse',
				ApiBase::PARAM_DEPRECATED => true,
			],
			'section' => [
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-section',
			],
			'diffto' => [
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-diffto',
				ApiBase::PARAM_DEPRECATED => true,
			],
			'difftotext' => [
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-difftotext',
				ApiBase::PARAM_DEPRECATED => true,
			],
			'difftotextpst' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-difftotextpst',
				ApiBase::PARAM_DEPRECATED => true,
			],
			'contentformat' => [
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats(),
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+revisions+base-param-contentformat',
			],
		];
	}

}
