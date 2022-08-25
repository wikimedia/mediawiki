<?php

namespace MediaWiki\Parser\Parsoid;

use Composer\Semver\Semver;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\HttpException;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Core\SelserData;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;
use Wikimedia\Parsoid\Utils\Timing;

/**
 * @unstable
 */
class HTMLTransform {
	/** @var array */
	private $options = [];

	/** @var ?int */
	private $oldid = null;

	/**
	 * Whether $this->doc has had any necessary processing applied,
	 * such as injecting data-parsoid attributes from a PageBundle.
	 * @var bool
	 */
	private $docHasBeenProcessed = false;

	/** @var ?Document */
	private $doc = null;

	/** @var ?Element */
	private $originalBody = null;

	/** @var ?StatsdDataFactoryInterface A statistics aggregator */
	protected $metrics = null;

	/** @var PageBundle */
	private $modifiedPageBundle;

	/** @var PageBundle */
	private $originalPageBundle;

	/** @var PageConfig */
	private $pageConfig;

	/** @var Parsoid */
	private $parsoid;

	/** @var array */
	private $parsoidSettings;

	/**
	 * @param string $modifiedHTML
	 * @param PageConfig $pageConfig
	 * @param Parsoid $parsoid
	 * @param array $parsoidSettings
	 */
	public function __construct(
		string $modifiedHTML,
		PageConfig $pageConfig,
		Parsoid $parsoid,
		array $parsoidSettings
	) {
		$this->pageConfig = $pageConfig;
		$this->parsoid = $parsoid;
		$this->parsoidSettings = $parsoidSettings;
		$this->modifiedPageBundle = new PageBundle( $modifiedHTML );
		$this->originalPageBundle = new PageBundle( '' );
	}

	/**
	 * @param StatsdDataFactoryInterface $metrics
	 */
	public function setMetrics( StatsdDataFactoryInterface $metrics ): void {
		$this->metrics = $metrics;
	}

	private function startTiming(): Timing {
		return Timing::start( $this->metrics );
	}

	private function incrementMetrics( string $key ) {
		if ( $this->metrics ) {
			$this->metrics->increment( $key );
		}
	}

	public function setOptions( array $options ) {
		$this->options = $options;
	}

	/**
	 * @param int $oldid
	 */
	public function setOriginalRevisionId( int $oldid ): void {
		$this->oldid = $oldid;
	}

	private function validatePageBundle( PageBundle $pb ) {
		if ( !$pb->version ) {
			return;
		}

		$errorMessage = '';
		if ( !$pb->validate( $pb->version, $errorMessage ) ) {
			throw new ClientError( $errorMessage );
		}
	}

	/**
	 * @note Call this after all original data has been set!
	 *
	 * @param array $modifiedDataMW
	 */
	public function setModifiedDataMW( array $modifiedDataMW ): void {
		// Relies on setOriginalSchemaVersion having been called already.
		if ( !Semver::satisfies( $this->getSchemaVersion(), '^999.0.0' ) ) {
			throw new ClientError( 'Modified data-mw is not supported by schema version '
				. $this->getSchemaVersion() );
		}

		$this->modifiedPageBundle->mw = $modifiedDataMW;
	}

	/**
	 * @param string $originalSchemaVeraion
	 */
	public function setOriginalSchemaVersion( string $originalSchemaVeraion ): void {
		$this->originalPageBundle->version = $originalSchemaVeraion;
	}

	/**
	 * @param string $originalHtml
	 */
	public function setOriginalHtml( string $originalHtml ): void {
		if ( $this->doc ) {
			throw new LogicException( __FUNCTION__ . ' cannot be called after' .
				' getModifiedDocument()' );
		}

		$this->originalPageBundle->html = $originalHtml;
	}

	/**
	 * @param array $originalDataMW
	 */
	public function setOriginalDataMW( array $originalDataMW ): void {
		if ( $this->doc ) {
			throw new LogicException( __FUNCTION__ . ' cannot be called after getModifiedDocument()' );
		}

		$this->originalPageBundle->mw = $originalDataMW;

		// Modified data-mw is going to be the same as original data-mw,
		// unless specified otherwise.
		if ( $this->modifiedPageBundle->mw === null ) {
			$this->modifiedPageBundle->mw = $originalDataMW;
		}
	}

	/**
	 * @param array $originalDataParsoid
	 */
	public function setOriginalDataParsoid( array $originalDataParsoid ): void {
		if ( $this->doc ) {
			throw new LogicException( __FUNCTION__ . ' cannot be called after getModifiedDocument()' );
		}

		// data-parsoid is going to be the same for original and modified.
		$this->originalPageBundle->parsoid = $originalDataParsoid;
		$this->modifiedPageBundle->parsoid = $originalDataParsoid;
	}

	/**
	 * The size of the modified HTML in characters.
	 *
	 * @return int
	 */
	public function getModifiedHtmlSize(): int {
		return mb_strlen( $this->modifiedPageBundle->html );
	}

	private function getModifiedDocumentRaw(): Document {
		if ( !$this->doc ) {
			$this->doc = $this->parseHTML( $this->modifiedPageBundle->html, true );
			$this->modifiedPageBundle->version = DOMUtils::extractInlinedContentVersion( $this->doc );
		}

		return $this->doc;
	}

	public function getModifiedDocument(): Document {
		$doc = $this->getModifiedDocumentRaw();

		if ( !$this->docHasBeenProcessed ) {
			$this->applyPageBundle( $this->doc, $this->modifiedPageBundle );

			$this->docHasBeenProcessed = true;
		}

		return $doc;
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return bool
	 */
	public function hasOriginalHtml(): bool {
		return $this->originalPageBundle->html !== null && $this->originalPageBundle->html !== '';
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return bool
	 */
	public function hasOriginalDataParsoid(): bool {
		return $this->originalPageBundle->parsoid !== null;
	}

	/**
	 * Returns the original HTML, with any necessary processing applied.
	 *
	 * @todo Make this method redundant, nothing should operate on HTML strings.
	 *
	 * @return string
	 */
	public function getOriginalHtml(): string {
		// NOTE: Schema version should have been set explicitly,
		//       so don't call getOriginalSchemaVersion,
		//       which will silently fall back to the default.
		if ( !$this->originalPageBundle->version ) {
			throw new ClientError(
				'Content-type of original html is missing.'
			);
		}

		if ( !$this->originalBody ) {
			// NOTE: Make sure we called getOriginalBody() at least once before we
			//       return the original HTML, so downgrades can be applied,
			//       data-parsoid can be injected, and $this->originalPageBundle->html
			//       is updated accordingly.

			if ( $this->hasOriginalDataParsoid() || $this->needsDowngrade( $this->originalPageBundle ) ) {
				$this->getOriginalBody();
			}
		}

		return $this->originalPageBundle->html ?: '';
	}

	/**
	 * @param string $html
	 * @param bool $validateXMLNames
	 *
	 * @return Document
	 * @throws ClientError
	 */
	protected function parseHTML( string $html, bool $validateXMLNames = false ): Document {
		return DOMUtils::parseHTML( $html, $validateXMLNames );
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return Element
	 * @throws ClientError
	 */
	public function getOriginalBody(): Element {
		if ( !$this->hasOriginalHtml() ) {
			throw new LogicException(
				'No original data supplied, call hasOriginalHtml() first.'
			);
		}

		if ( $this->originalBody ) {
			return $this->originalBody;
		}

		// NOTE: Schema version should have been set explicitly,
		//       so don't call getOriginalSchemaVersion,
		//       which will silently fall back to the default.
		if ( !$this->originalPageBundle->version ) {
			throw new ClientError(
				'Content-type of original html is missing.'
			);
		}

		if ( $this->needsDowngrade( $this->originalPageBundle ) ) {
			$this->downgradeOriginalData( $this->originalPageBundle, $this->getSchemaVersion() );
		}

		$doc = $this->parseHTML( $this->originalPageBundle->html );

		$this->applyPageBundle( $doc, $this->originalPageBundle );

		$this->originalBody = DOMCompat::getBody( $doc );

		// XXX: use a separate field??
		$this->originalPageBundle->html = ContentUtils::toXML( $this->originalBody );

		return $this->originalBody;
	}

	public function getOriginalSchemaVersion(): string {
		return $this->originalPageBundle->version ?: $this->getSchemaVersion();
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return string
	 */
	public function getSchemaVersion(): string {
		// Get the content version of the edited doc, if available.
		// Make sure $this->modifiedPageBundle->version is initialized.
		$this->getModifiedDocumentRaw();
		$inputContentVersion = $this->modifiedPageBundle->version;

		if ( !$inputContentVersion ) {
			$this->incrementMetrics( 'html2wt.original.version.notinline' );
			$inputContentVersion = $this->originalPageBundle->version ?: Parsoid::defaultHTMLVersion();
		}

		return $inputContentVersion;
	}

	public function getOriginalRevisionId(): ?int {
		return $this->oldid;
	}

	public function getContentModel(): ?string {
		return $this->options['contentmodel'] ?? null;
	}

	public function getOffsetType(): string {
		return $this->options['offsetType'];
	}

	private function needsDowngrade( PageBundle $pb ): bool {
		$vOriginal = $pb->version;
		$vEdited = $this->getSchemaVersion();

		return $vOriginal !== null && $vOriginal !== $vEdited;
	}

	private function downgradeOriginalData( PageBundle $pb, string $targetSchemaVersion ) {
		if ( $pb->version === null ) {
			throw new ClientError( 'Missing schema version' );
		}

		if ( $targetSchemaVersion === $pb->version ) {
			// nothing to do.
			return;
		}

		if ( !$pb->parsoid ) {
			// XXX: Should we also support downgrades if $pb->html has everything inlined?
			// XXX: The downgrade should really be an operation on the DOM.
			return;
		}

		// We need to downgrade the original to match the edited doc's version.
		$downgrade = Parsoid::findDowngrade( $pb->version, $targetSchemaVersion );

		if ( !$downgrade ) {
			throw new ClientError(
				"No downgrade possible from schema version {$pb->version} to {$targetSchemaVersion}."
			);
		}

		$this->incrementMetrics(
			"downgrade.from.{$downgrade['from']}.to.{$downgrade['to']}"
		);
		$downgradeTiming = $this->startTiming();
		Parsoid::downgrade( $downgrade, $pb );
		$downgradeTiming->end( 'downgrade.time' );

		// NOTE: Set $this->originalBody to null so getOriginalBody() will re-generate it.
		// XXX: Parsoid::downgrade operates on the parsed Document, would be nice
		//      if we could get that instead of getting back HTML which we have to
		//      parse again!
		$this->originalBody = null;
	}

	/**
	 * @param Document $doc
	 * @param PageBundle $pb
	 *
	 * @throws ClientError
	 */
	private function applyPageBundle( Document $doc, PageBundle $pb ): void {
		if ( $pb->parsoid === null && $pb->mw === null ) {
			return;
		}

		// Verify that the top-level parsoid object either doesn't contain
		// offsetType, or that it matches the conversion that has been
		// explicitly requested.
		if ( isset( $pb->parsoid['offsetType'] ) ) {
			$offsetType = $this->getOffsetType();
			$origOffsetType = $pb->parsoid['offsetType'] ?? $offsetType;
			if ( $origOffsetType !== $offsetType ) {
				throw new ClientError(
					'DSR offsetType mismatch: ' . $origOffsetType . ' vs ' . $offsetType
				);
			}
		}

		$this->validatePageBundle( $pb );
		PageBundle::apply( $doc, $pb );
	}

	/**
	 * Get a selective serialization (selser) data object. This
	 * can be null if selser is not enabled or oldid is not available.
	 *
	 * @return SelserData|null
	 * @throws HttpException
	 */
	private function getSelserData(): ?SelserData {
		if ( !$this->hasOriginalHtml() ) {
			// No original HTML, no selser.
			return null;
		}

		$oldhtml = $this->getOriginalHtml();

		// As per https://www.mediawiki.org/wiki/Parsoid/API#v1_API_entry_points
		//   "Both it and the oldid parameter are needed for
		//    clean round-tripping of HTML retrieved earlier with"
		// So, no oldid => no selser
		$hasOldId = ( $this->getOriginalRevisionId() !== null );

		if ( $hasOldId && !empty( $this->parsoidSettings['useSelser'] ) ) {
			if ( !$this->pageConfig->getRevisionContent() ) {
				throw new HttpException( 'Could not find previous revision. Has the page been locked / deleted?',
					409 );
			}

			// FIXME: T234548/T234549 - $pageConfig->getPageMainContent() is deprecated:
			// should use $env->topFrame->getSrcText()
			$selserData = new SelserData( $this->pageConfig->getPageMainContent(),
				$oldhtml );
		} else {
			$selserData = null;
		}

		return $selserData;
	}

	public function htmlToWikitext(): string {
		if ( $this->metrics ) {
			$metrics = $this->metrics;
		} else {
			$metrics = MediaWikiServices::getInstance()->getParsoidSiteConfig()->metrics();
		}

		// Performance Timing options
		$timing = Timing::start( $metrics );

		$doc = $this->getModifiedDocument();
		$htmlSize = $this->getModifiedHtmlSize();

		// Send input size to statsd/Graphite
		$metrics->timing( 'html2wt.size.input', $htmlSize );

		$inputContentVersion = $this->getSchemaVersion();

		$metrics->increment(
			'html2wt.original.version.' . $inputContentVersion
		);

		$selserData = $this->getSelserData();

		$timing->end( 'html2wt.init' );

		try {
			$wikitext = $this->parsoid->dom2wikitext( $this->pageConfig, $doc, [
				'inputContentVersion' => $inputContentVersion,
				'offsetType' => $this->getOffsetType(),
				'contentmodel' => $this->getContentModel(),
				'htmlSize' => $htmlSize, // used to trigger status 413 if the input is too big
			], $selserData );
		} catch ( ClientError $e ) {
			throw new HttpException( $e->getMessage(), 400 );
		} catch ( ResourceLimitExceededException $e ) {
			throw new HttpException( $e->getMessage(), 413 );
		}

		$total = $timing->end( 'html2wt.total' );
		$metrics->timing( 'html2wt.size.output', strlen( $wikitext ) );

		if ( $htmlSize ) {  // Avoid division by zero
			// NOTE: the name timePerInputKB is misleading, since $htmlSize is
			//       in characters, not bytes.
			$metrics->timing( 'html2wt.timePerInputKB', $total * 1024 / $htmlSize );
		}

		return $wikitext;
	}

}
