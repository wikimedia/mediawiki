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
 */
namespace MediaWiki\Rest\Handler;

use Content;
use IBufferingStatsdDataFactory;
use Language;
use LogicException;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Edit\SelserContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageRecord;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use ParserOptions;
use ParserOutput;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Helper for getting output of a given wikitext page rendered by parsoid.
 *
 * @since 1.36
 *
 * @unstable Pending consolidation of the Parsoid extension with core code.
 */
class HtmlOutputRendererHelper {
	/**
	 * @internal
	 * @var string[]
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParsoidCacheConfig
	];

	/** @var string[] */
	private const OUTPUT_FLAVORS = [ 'view', 'stash', 'fragment' ];

	/** @var ParsoidOutputStash */
	private $parsoidOutputStash;

	/** @var PageIdentity|null */
	private $page = null;

	/** @var RevisionRecord|int|null */
	private $revisionOrId = null;

	/** @var Language|null */
	private $pageLanguage = null;

	/** @var ?string One of the flavors from OUTPUT_FLAVORS */
	private $flavor = null;

	/** @var bool */
	private $stash = false;

	/** @var IBufferingStatsdDataFactory */
	private $stats;

	/** @var User */
	private $user;

	/** @var ParsoidOutputAccess */
	private $parsoidOutputAccess;

	/** @var ParserOutput */
	private $parserOutput;

	/** @var ParserOutput */
	private $processedParserOutput;

	/** @var HtmlTransformFactory */
	private $htmlTransformFactory;

	/** @var string|null */
	private $sourceLanguageCode;

	/** @var string|null */
	private $targetLanguageCode;

	/**
	 * @param ParsoidOutputStash $parsoidOutputStash
	 * @param IBufferingStatsdDataFactory $statsDataFactory
	 * @param ParsoidOutputAccess $parsoidOutputAccess
	 * @param HtmlTransformFactory $htmlTransformFactory
	 */
	public function __construct(
		ParsoidOutputStash $parsoidOutputStash,
		IBufferingStatsdDataFactory $statsDataFactory,
		ParsoidOutputAccess $parsoidOutputAccess,
		HtmlTransformFactory $htmlTransformFactory
	) {
		$this->parsoidOutputStash = $parsoidOutputStash;
		$this->stats = $statsDataFactory;
		$this->parsoidOutputAccess = $parsoidOutputAccess;
		$this->htmlTransformFactory = $htmlTransformFactory;
	}

	/**
	 * Sets the given flavor to use for Wikitext -> HTML
	 * transformations.
	 *
	 * @param string $flavor
	 *
	 * @return void
	 */
	public function setFlavor( string $flavor ): void {
		if ( !in_array( $flavor, self::OUTPUT_FLAVORS ) ) {
			throw new LogicException( 'Invalid flavor supplied' );
		}

		$this->flavor = $flavor;
	}

	/**
	 * Determine whether stashing should be applied.
	 *
	 * @param bool $stash
	 *
	 * @return void
	 */
	public function setStashingEnabled( bool $stash ): void {
		$this->stash = $stash;

		if ( $stash ) {
			$this->setFlavor( 'stash' );
		} elseif ( $this->flavor === 'stash' ) {
			$this->setFlavor( 'view' );
		}
	}

	/**
	 * Set the revision to render.
	 *
	 * This can take a fake RevisionRecord when rendering for previews
	 * or when switching the editor from source mode to visual mode.
	 *
	 * In that case, $revisionOrId->getId() must return 0 to indicate
	 * that the ParserCache should be bypassed. Stashing may still apply.
	 *
	 * @param RevisionRecord|int $revisionOrId
	 */
	public function setRevision( $revisionOrId ): void {
		Assert::parameterType( [ RevisionRecord::class, 'integer' ], $revisionOrId, '$revision' );
		$this->revisionOrId = $revisionOrId;
	}

	/**
	 * Set the content to render. Useful when rendering for previews
	 * or when switching the editor from source mode to visual mode.
	 *
	 * This will create a fake revision for rendering, the revision ID will be 0.
	 *
	 * @see setRevision
	 *
	 * @param Content $content
	 */
	public function setContent( Content $content ): void {
		$rev = new MutableRevisionRecord( $this->page );
		$rev->setId( 0 );
		$rev->setPageId( $this->page->getId() );
		$rev->setContent( SlotRecord::MAIN, $content );
		$this->setRevision( $rev );
	}

	/**
	 * @param Language $pageLanguage
	 */
	public function setPageLanguage( Language $pageLanguage ): void {
		$this->pageLanguage = $pageLanguage;
	}

	/**
	 * @param PageIdentity $page
	 * @param array $parameters
	 * @param User $user
	 * @param RevisionRecord|int|null $revision DEPRECATED, use setRevision()
	 * @param Language|null $pageLanguage
	 */
	public function init(
		PageIdentity $page,
		array $parameters,
		User $user,
		$revision = null,
		?Language $pageLanguage = null
	) {
		$this->page = $page;
		$this->user = $user;
		$this->revisionOrId = $revision;
		$this->pageLanguage = $pageLanguage;
		$this->stash = $parameters['stash'] ?? false;

		if ( $this->stash ) {
			$this->setFlavor( 'stash' );
		} else {
			$this->setFlavor( $parameters['flavor'] ?? 'view' );
		}
	}

	/**
	 * Set the language to be used for variant conversion
	 * @param string $targetLanguageCode
	 * @param null|string $sourceLanguageCode
	 */
	public function setVariantConversionLanguage( string $targetLanguageCode, ?string $sourceLanguageCode = null ) {
		$this->targetLanguageCode = $targetLanguageCode;
		$this->sourceLanguageCode = $sourceLanguageCode;
	}

	/**
	 * @return ParserOutput a tuple with html and content-type
	 * @throws LocalizedHttpException
	 */
	public function getHtml(): ParserOutput {
		if ( $this->processedParserOutput ) {
			return $this->processedParserOutput;
		}

		$parserOutput = $this->getParserOutput();

		if ( $this->stash ) {
			if ( $this->user->pingLimiter( 'stashbasehtml' ) ) {
				throw new LocalizedHttpException(
					MessageValue::new( 'parsoid-stash-rate-limit-error' ),
					// See https://www.rfc-editor.org/rfc/rfc6585#section-4
					429,
					[ 'reason' => 'Rate limiter tripped, wait for a few minutes and try again' ]
				);
			}

			$fakeRevision = ( is_object( $this->revisionOrId ) && $this->revisionOrId->getId() < 1 );
			$parsoidStashKey = ParsoidRenderID::newFromKey(
				$this->parsoidOutputAccess->getParsoidRenderID( $parserOutput )
			);
			$stashSuccess = $this->parsoidOutputStash->set(
				$parsoidStashKey,
				new SelserContext(
					PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput ),
					$parsoidStashKey->getRevisionID(),
					$fakeRevision ? $this->revisionOrId->getContent( SlotRecord::MAIN ) : null
				)
			);
			if ( !$stashSuccess ) {
				$this->stats->increment( 'htmloutputrendererhelper.stash.fail' );
				throw new LocalizedHttpException(
					MessageValue::new( 'rest-html-backend-error' ),
					500,
					[ 'reason' => 'Failed to stash parser output' ]
				);
			}
			$this->stats->increment( 'htmloutputrendererhelper.stash.save' );
		}

		// Check if variant conversion has to be performed
		// NOTE: Variant conversion is performed on the fly, and kept outside the stash.
		if ( $this->targetLanguageCode ) {
			$languageVariantConverter = $this->htmlTransformFactory->getLanguageVariantConverter( $this->page );
			$parserOutput = $languageVariantConverter->convertParserOutputVariant(
				$parserOutput,
				$this->targetLanguageCode,
				$this->sourceLanguageCode
			);
		}

		$this->processedParserOutput = $parserOutput;
		return $parserOutput;
	}

	/**
	 * Returns an ETag uniquely identifying the HTML output.
	 *
	 * @param string $suffix A suffix to attach to the etag.
	 *
	 * @return string|null
	 */
	public function getETag( string $suffix = '' ): ?string {
		$parserOutput = $this->getParserOutput();

		$renderID = $this->parsoidOutputAccess->getParsoidRenderID( $parserOutput )->getKey();

		if ( $suffix !== '' ) {
			$eTag = "$renderID/{$this->flavor}/$suffix";
		} else {
			$eTag = "$renderID/{$this->flavor}";
		}

		if ( $this->targetLanguageCode ) {
			$eTag .= "+lang:{$this->targetLanguageCode}";
		}

		return "\"{$eTag}\"";
	}

	/**
	 * Returns the time at which the HTML was rendered.
	 *
	 * @return string|null
	 */
	public function getLastModified(): ?string {
		return $this->getParserOutput()->getCacheTime();
	}

	/**
	 * @return array
	 */
	public function getParamSettings(): array {
		return [
			'stash' => [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_REQUIRED => false,
			],
			'flavor' => [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => self::OUTPUT_FLAVORS,
				ParamValidator::PARAM_DEFAULT => 'view',
				ParamValidator::PARAM_REQUIRED => false,
			],
		];
	}

	/**
	 * @return ParserOutput
	 */
	private function getParserOutput(): ParserOutput {
		if ( !$this->parserOutput ) {
			$parserOptions = ParserOptions::newFromAnon();

			if ( $this->pageLanguage ) {
				$parserOptions->setTargetLanguage( $this->pageLanguage );
			}

			// XXX: $envOptions are really parser options, and they should be integrated with
			//      the ParserOptions class. That would allow us to use hte ParserCache with
			//      various flavors.
			$envOptions = [];

			// NOTE: VisualEditor would set this flavor when transforming from Wikitext to HTML
			//       for the purpose of editing when doing parsefragment (in body only mode).
			if ( $this->flavor === 'fragment' ) {
				$envOptions += [
					'body_only' => true,
					'wrapSections' => false,
				];
			}

			// NOTE: ParsoidOutputAccess::getParserOutput() should be used for revisions
			//       that comes from the database. Either this revision is null to indicate
			//       the current revision or the revision must have an ID.
			// If we have a revision and the ID is 0 or null, then it's a fake revision
			// representing a preview.
			$fakeRevision = ( is_object( $this->revisionOrId ) && $this->revisionOrId->getId() < 1 );
			$pageRecordAvailable = $this->page instanceof PageRecord;

			if ( $pageRecordAvailable && !$fakeRevision && !$envOptions ) {
				$status = $this->parsoidOutputAccess->getParserOutput(
					$this->page,
					$parserOptions,
					$this->revisionOrId
				);
			} else {
				$status = $this->parsoidOutputAccess->parse(
					$this->page,
					$parserOptions,
					$envOptions,
					$this->revisionOrId
				);
			}

			if ( !$status->isOK() ) {
				if ( $status->hasMessage( 'parsoid-client-error' ) ) {
					throw new LocalizedHttpException(
						MessageValue::new( 'rest-html-backend-error' ),
						400,
						[ 'reason' => $status->getErrors() ]
					);
				} elseif ( $status->hasMessage( 'parsoid-resource-limit-exceeded' ) ) {
					throw new LocalizedHttpException(
						MessageValue::new( 'rest-resource-limit-exceeded' ),
						413,
						[ 'reason' => $status->getErrors() ]
					);
				} else {
					throw new LocalizedHttpException(
						MessageValue::new( 'rest-html-backend-error' ),
						500,
						[ 'reason' => $status->getErrors() ]
					);
				}
			}

			$this->parserOutput = $status->getValue();
		}

		return $this->parserOutput;
	}

	/**
	 * The content language of the HTML output after parsing.
	 *
	 * @return string
	 */
	public function getHtmlOutputContentLanguage(): string {
		$pageBundleData = $this->getHtml()->getExtensionData(
			PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY
		);

		// XXX: We need a canonical way of getting the output language from
		//      ParserOutput since we may not be getting parser outputs from
		//		Parsoid always in the future.
		if ( !isset( $pageBundleData['headers']['content-language'] ) ) {
			throw new LogicException( 'Failed to find content language in page bundle data' );
		}

		return $pageBundleData['headers']['content-language'];
	}

	/**
	 * Set the HTTP headers based on the response generated
	 *
	 * @param ResponseInterface $response
	 * @param bool $setContentLanguageHeader
	 * @return void
	 */
	public function putHeaders( ResponseInterface $response, bool $setContentLanguageHeader ) {
		if ( $this->targetLanguageCode ) {
			if ( $setContentLanguageHeader ) {
				$response->setHeader( 'Content-Language', $this->getHtmlOutputContentLanguage() );
			}

			$response->addHeader( 'Vary', 'Accept-Language' );
		}
	}

}
