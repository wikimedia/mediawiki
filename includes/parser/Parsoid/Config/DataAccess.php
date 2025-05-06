<?php
/**
 * Copyright (C) 2011-2022 Wikimedia Foundation and others.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace MediaWiki\Parser\Parsoid\Config;

use MediaTransformError;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Category\TrackingCategories;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\PPFrame;
use MediaWiki\Title\Title;
use Wikimedia\Assert\UnreachableException;
use Wikimedia\Parsoid\Config\DataAccess as IDataAccess;
use Wikimedia\Parsoid\Config\PageConfig as IPageConfig;
use Wikimedia\Parsoid\Config\PageContent as IPageContent;
use Wikimedia\Parsoid\Core\ContentMetadataCollector;
use Wikimedia\Parsoid\Core\LinkTarget as ParsoidLinkTarget;
use Wikimedia\Parsoid\Fragments\HtmlPFragment;
use Wikimedia\Parsoid\Fragments\PFragment;
use Wikimedia\Parsoid\Fragments\WikitextPFragment;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Implement Parsoid's abstract class for data access.
 *
 * @since 1.39
 * @internal
 */
class DataAccess extends IDataAccess {
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::SVGMaxSize,
	];

	private RepoGroup $repoGroup;
	private BadFileLookup $badFileLookup;
	private HookContainer $hookContainer;
	private HookRunner $hookRunner;
	private ContentTransformer $contentTransformer;
	private TrackingCategories $trackingCategories;
	private ParserFactory $parserFactory;
	/** Lazy-created via self::prepareParser() */
	private ?Parser $parser = null;
	private PPFrame $ppFrame;
	private ?PageConfig $previousPageConfig = null;
	private ServiceOptions $config;
	private ReadOnlyMode $readOnlyMode;
	private LinkBatchFactory $linkBatchFactory;
	private int $markerIndex = 0;

	/**
	 * @param ServiceOptions $config MediaWiki main configuration object
	 * @param RepoGroup $repoGroup
	 * @param BadFileLookup $badFileLookup
	 * @param HookContainer $hookContainer
	 * @param ContentTransformer $contentTransformer
	 * @param TrackingCategories $trackingCategories
	 * @param ReadOnlyMode $readOnlyMode used to disable linting when the
	 *   database is read-only.
	 * @param ParserFactory $parserFactory A legacy parser factory,
	 *   for PST/preprocessing/extension handling
	 * @param LinkBatchFactory $linkBatchFactory
	 */
	public function __construct(
		ServiceOptions $config,
		RepoGroup $repoGroup,
		BadFileLookup $badFileLookup,
		HookContainer $hookContainer,
		ContentTransformer $contentTransformer,
		TrackingCategories $trackingCategories,
		ReadOnlyMode $readOnlyMode,
		ParserFactory $parserFactory,
		LinkBatchFactory $linkBatchFactory
	) {
		$config->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->config = $config;
		$this->repoGroup = $repoGroup;
		$this->badFileLookup = $badFileLookup;
		$this->hookContainer = $hookContainer;
		$this->contentTransformer = $contentTransformer;
		$this->trackingCategories = $trackingCategories;
		$this->readOnlyMode = $readOnlyMode;
		$this->linkBatchFactory = $linkBatchFactory;

		$this->hookRunner = new HookRunner( $hookContainer );

		$this->parserFactory = $parserFactory;
		$this->previousPageConfig = null; // ensure we initialize parser options
	}

	/**
	 * @param IPageConfig $pageConfig
	 * @param File $file
	 * @param array $hp
	 * @return array
	 */
	private function makeTransformOptions( IPageConfig $pageConfig, $file, array $hp ): array {
		// Validate the input parameters like Parser::makeImage()
		$handler = $file->getHandler();
		if ( !$handler ) {
			return []; // will get iconThumb()
		}
		foreach ( $hp as $name => $value ) {
			if ( !$handler->validateParam( $name, $value ) ) {
				unset( $hp[$name] );
			}
		}

		// This part is similar to Linker::makeImageLink(). If there is no width,
		// set one based on the source file size.
		$page = $hp['page'] ?? 0;
		if ( !isset( $hp['width'] ) ) {
			if ( isset( $hp['height'] ) && $file->isVectorized() ) {
				// If it's a vector image, and user only specifies height
				// we don't want it to be limited by its "normal" width.
				$hp['width'] = $this->config->get( MainConfigNames::SVGMaxSize );
			} else {
				$hp['width'] = $file->getWidth( $page );
			}

			// We don't need to fill in a default thumbnail width here, since
			// that is done by Parsoid. Parsoid always sets the width parameter
			// for thumbnails.
		}

		// Parser::makeImage() always sets this
		$hp['targetlang'] = LanguageCode::bcp47ToInternal(
			$pageConfig->getPageLanguageBcp47()
		);

		return $hp;
	}

	/** @inheritDoc */
	public function getPageInfo( $pageConfigOrTitle, array $titles ): array {
		if ( $pageConfigOrTitle instanceof IPageConfig ) {
			$context_title = Title::newFromLinkTarget(
				$pageConfigOrTitle->getLinkTarget()
			);
		} elseif ( is_string( $pageConfigOrTitle ) ) {
			// Temporary, deprecated.
			$context_title = Title::newFromTextThrow( $pageConfigOrTitle );
		} elseif ( $pageConfigOrTitle instanceof ParsoidLinkTarget ) {
			$context_title = Title::newFromLinkTarget( $pageConfigOrTitle );
		} else {
			throw new UnreachableException( "Bad type for argument 1" );
		}
		$titleObjs = [];
		$pagemap = [];
		$classes = [];
		$ret = [];
		foreach ( $titles as $name ) {
			$t = Title::newFromText( $name );
			// Filter out invalid titles. Title::newFromText in core (not our bespoke
			// version in src/Utils/Title.php) can return null for invalid titles.
			if ( !$t ) {
				// FIXME: This is a bandaid to patch up the fact that Env::makeTitle treats
				// this as a valid title, but Title::newFromText treats it as invalid.
				// T237535
				// This matches what ApiQuery::outputGeneralPageInfo() would
				// return for an invalid title.
				$ret[$name] = [
					'pageId' => -1,
					'revId' => -1,
					'invalid' => true,
					'invalidreason' => 'The requested page title is invalid',
				];
			} else {
				$titleObjs[$name] = $t;
			}
		}
		$linkBatch = $this->linkBatchFactory->newLinkBatch( $titleObjs );
		$linkBatch->setCaller( __METHOD__ );
		$linkBatch->execute();

		foreach ( $titleObjs as $obj ) {
			$pdbk = $obj->getPrefixedDBkey();
			$pagemap[$obj->getArticleID()] = $pdbk;
			$classes[$pdbk] = $obj->isRedirect() ? 'mw-redirect' : '';
		}
		$this->hookRunner->onGetLinkColours(
			# $classes is passed by reference and mutated
			$pagemap, $classes, $context_title
		);

		foreach ( $titleObjs as $name => $obj ) {
			/** @var Title $obj */
			$pdbk = $obj->getPrefixedDBkey();
			$c = preg_split(
				'/\s+/', $classes[$pdbk] ?? '', -1, PREG_SPLIT_NO_EMPTY
			);
			$ret[$name] = [
				'pageId' => $obj->getArticleID(),
				'revId' => $obj->getLatestRevID(),
				'missing' => !$obj->exists(),
				'known' => $obj->isKnown(),
				'redirect' => $obj->isRedirect(),
				'linkclasses' => $c, # See ApiQueryInfo::getLinkClasses() in core
			];
		}
		return $ret;
	}

	/** @inheritDoc */
	public function getFileInfo( IPageConfig $pageConfig, array $files ): array {
		$page = Title::newFromLinkTarget( $pageConfig->getLinkTarget() );

		$keys = [];
		foreach ( $files as $f ) {
			$keys[] = $f[0];
		}
		$fileObjs = $this->repoGroup->findFiles( $keys );

		$ret = [];
		foreach ( $files as $f ) {
			$filename = $f[0];
			$dims = $f[1];

			/** @var File $file */
			$file = $fileObjs[$filename] ?? null;
			if ( !$file ) {
				$ret[] = null;
				continue;
			}

			// See Linker::makeImageLink; 'page' is a key in $handlerParams
			// core uses 'false' as the default then casts to (int) => 0
			$pageNum = $dims['page'] ?? 0;

			$result = [
				'width' => $file->getWidth( $pageNum ),
				'height' => $file->getHeight( $pageNum ),
				'size' => $file->getSize(),
				'mediatype' => $file->getMediaType(),
				'mime' => $file->getMimeType(),
				'url' => $file->getFullUrl(),
				'mustRender' => $file->mustRender(),
				'badFile' => $this->badFileLookup->isBadFile( $filename, $page ),
				'timestamp' => $file->getTimestamp(),
				'sha1' => $file->getSha1(),
			];

			$length = $file->getLength();
			if ( $length ) {
				$result['duration'] = (float)$length;
			}

			if ( isset( $dims['seek'] ) ) {
				$dims['thumbtime'] = $dims['seek'];
			}

			$txopts = $this->makeTransformOptions( $pageConfig, $file, $dims );
			$mto = $file->transform( $txopts );
			if ( $mto ) {
				if ( $mto->isError() && $mto instanceof MediaTransformError ) {
					$result['thumberror'] = $mto->toText();
				} else {
					if ( $txopts ) {
						// Do srcset scaling
						Linker::processResponsiveImages( $file, $mto, $txopts );
						if ( count( $mto->responsiveUrls ) ) {
							$result['responsiveUrls'] = [];
							foreach ( $mto->responsiveUrls as $density => $url ) {
								$result['responsiveUrls'][$density] = $url;
							}
						}
					}

					// Proposed MediaTransformOutput serialization method for T51896 etc.
					// Note that getAPIData(['fullurl']) would return
					// UrlUtils::expand(), which wouldn't respect the wiki's
					// protocol preferences -- instead it would use the
					// protocol used for the API request.
					if ( is_callable( [ $mto, 'getAPIData' ] ) ) {
						$result['thumbdata'] = $mto->getAPIData( [ 'withhash' ] );
					}

					$result['thumburl'] = $mto->getUrl();
					$result['thumbwidth'] = $mto->getWidth();
					$result['thumbheight'] = $mto->getHeight();
				}
			} else {
				$result['thumberror'] = "Presumably, invalid parameters, despite validation.";
			}

			$ret[] = $result;
		}

		return $ret;
	}

	/**
	 * Prepare MediaWiki's parser for preprocessing or extension tag parsing,
	 * clearing its state if necessary.
	 *
	 * @param IPageConfig $pageConfig
	 * @param int $outputType
	 * @return Parser
	 */
	private function prepareParser( IPageConfig $pageConfig, int $outputType ) {
		'@phan-var PageConfig $pageConfig'; // @var PageConfig $pageConfig
		// Clear the state only when the PageConfig changes, so that Parser's internal caches can
		// be retained. This should also provide better compatibility with extension tags.
		$clearState = $this->previousPageConfig !== $pageConfig;
		$this->previousPageConfig = $pageConfig;
		// Use the same legacy parser object for all calls to extension tag
		// processing, for greater compatibility.
		$this->parser ??= $this->parserFactory->create();
		$this->parser->setStripExtTags( false );
		$this->parser->startExternalParse(
			Title::newFromLinkTarget( $pageConfig->getLinkTarget() ),
			$pageConfig->getParserOptions(),
			$outputType, $clearState, $pageConfig->getRevisionId() );
		$this->parser->resetOutput();

		// Retain a PPFrame object between preprocess requests since it contains
		// some useful caches.
		if ( $clearState ) {
			$this->ppFrame = $this->parser->getPreprocessor()->newFrame();
		}
		return $this->parser;
	}

	/** @internal */
	public function makeLimitReport(
		IPageConfig $pageConfig,
		ParserOptions $parserOptions,
		ParserOutput $parserOutput
	) {
		$parser = $this->parser ??
			$this->prepareParser( $pageConfig, Parser::OT_HTML );
		$parser->makeLimitReport( $parserOptions, $parserOutput );
	}

	/** @inheritDoc */
	public function parseWikitext(
		IPageConfig $pageConfig,
		ContentMetadataCollector $metadata,
		string $wikitext
	): string {
		$parser = $this->prepareParser( $pageConfig, Parser::OT_HTML );
		$html = $parser->parseExtensionTagAsTopLevelDoc( $wikitext );
		// XXX: Ideally we will eventually have the legacy parser use our
		// ContentMetadataCollector instead of having a new ParserOutput
		// created (implicitly in ::prepareParser()/Parser::resetOutput() )
		// which we then have to manually merge.
		$out = $parser->getOutput();
		$out->setRawText( $html );
		$out->collectMetadata( $metadata ); # merges $out into $metadata
		return Parser::extractBody( $out->getRawText() );
	}

	/** @inheritDoc */
	public function preprocessWikitext(
		IPageConfig $pageConfig,
		ContentMetadataCollector $metadata,
		$wikitext
	) {
		$parser = $this->prepareParser( $pageConfig, Parser::OT_PREPROCESS );
		if ( $wikitext instanceof PFragment ) {
			$result = [];
			$index = 1;
			$split = $wikitext instanceof WikitextPFragment ?
				$wikitext->split() : [ $wikitext ];
			foreach ( $split as $fragment ) {
				if ( is_string( $fragment ) ) {
					$result[] = $fragment;
				} else {
					$marker = Parser::MARKER_PREFIX . '-parsoid-' .
						sprintf( '%08X', $this->markerIndex++ ) .
						Parser::MARKER_SUFFIX;
					$parser->getStripState()->addParsoidOpaque(
						$marker, $fragment
					);
					$result[] = $marker;
				}
			}
			$wikitext = implode( $result );
		}
		$this->hookRunner->onParserBeforePreprocess(
			# $wikitext is passed by reference and mutated
			$parser, $wikitext, $parser->getStripState()
		);
		// New PFragment-based support (T374616)
		$wikitext = $parser->replaceVariables(
			$wikitext, $this->ppFrame, false, [
				'parsoidTopLevelCall' => true,
				// This is implied by stripExtTags=false and
				// probably doesn't need to be explicitly passed
				// any more.
				'processNowiki' => true,
			]
		);
		// Where the result has strip state markers, tunnel this content
		// through Parsoid as a PFragment type.
		$pieces = $parser->getStripState()->split( $wikitext );
		if ( count( $pieces ) > 1 || ( $pieces[0]['type'] ?? null ) !== 'string' ) {
			for ( $i = 0; $i < count( $pieces ); $i++ ) {
				[ 'type' => $type, 'content' => $content ] = $pieces[$i];
				if ( $type === 'string' ) {
					// wikitext (could include extension tag snippets like <tag..>...</tag>)
					$pieces[$i] = $content;
				} elseif ( $type === 'parsoid' ) {
					$pieces[$i] = $pieces[$i]['extra']; // replace w/ fragment
				} elseif ( $type === 'nowiki' ) {
					$extra = $pieces[$i]['extra'] ?? null;
					// T388819: If this is from an actual <nowiki>, we
					// wrap <span typeof="mw:Nowiki"> around $contents.
					if ( $extra === 'nowiki' ) {
						$content = Html::rawElement( 'span', [
							'typeof' => 'mw:Nowiki',
						], $content );
					}
					$pieces[$i] = $content ? HtmlPFragment::newFromHtmlString( $content, null ) : '';
				} else {
					// T381709: technically this fragment should
					// be subject to language conversion and some
					// additional processing
					$pieces[$i] = $content ? HtmlPFragment::newFromHtmlString( $content, null ) : '';
				}
			}
			// Concatenate wikitext strings generated by extension tags,
			// so that PFragment doesn't try to add <nowiki>s between
			// the pieces to prevent token-gluing.
			$result = [];
			$wt = '';
			foreach ( $pieces as $p ) {
				if ( is_string( $p ) ) {
					$wt .= $p;
				} else {
					$result[] = $wt;
					$result[] = $p;
					$wt = '';
				}
			}
			$result[] = $wt;
			// result will be a PFragment, no longer a string.
			$wikitext = PFragment::fromSplitWt( $result );
		}

		// XXX: Ideally we will eventually have the legacy parser use our
		// ContentMetadataCollector instead of having a new ParserOutput
		// created (implicitly in ::prepareParser()/Parser::resetOutput() )
		// which we then have to manually merge.
		$out = $parser->getOutput();
		$out->collectMetadata( $metadata ); # merges $out into $metadata
		return $wikitext;
	}

	/** @inheritDoc */
	public function fetchTemplateSource(
		IPageConfig $pageConfig, $title
	): ?IPageContent {
		'@phan-var PageConfig $pageConfig'; // @var PageConfig $pageConfig
		if ( is_string( $title ) ) {
			$titleObj = Title::newFromTextThrow( $title );
		} else {
			$titleObj = Title::newFromLinkTarget( $title );
		}

		// Use the PageConfig to take advantage of custom template
		// fetch hooks like FlaggedRevisions, etc.
		$revRecord = $pageConfig->fetchRevisionRecordOfTemplate( $titleObj );

		return $revRecord ? new PageContent( $revRecord ) : null;
	}

	/** @inheritDoc */
	public function fetchTemplateData( IPageConfig $pageConfig, $title ): ?array {
		$ret = [];
		if ( !is_string( $title ) ) {
			$titleObj = Title::newFromLinkTarget( $title );
			$title = $titleObj->getPrefixedText();
		}
		// @todo: This hook needs some clean up: T304899
		$this->hookRunner->onParserFetchTemplateData(
			[ $title ],
			$ret # value returned by reference
		);

		// Cast value to array since the hook returns this as a stdclass
		$tplData = $ret[$title] ?? null;
		if ( $tplData ) {
			// Deep convert to associative array
			$tplData = json_decode( json_encode( $tplData ), true );
		}
		return $tplData;
	}

	/**
	 * Add a tracking category with the given key to the metadata for the page.
	 * @param IPageConfig $pageConfig the page on which the tracking category
	 *   is to be added
	 * @param ContentMetadataCollector $metadata The metadata for the page
	 * @param string $key Message key (not localized)
	 */
	public function addTrackingCategory(
		IPageConfig $pageConfig,
		ContentMetadataCollector $metadata,
		string $key
	): void {
		$page = Title::newFromLinkTarget( $pageConfig->getLinkTarget() );
		$this->trackingCategories->addTrackingCategory(
			$metadata, $key, $page
		);
	}

	/** @inheritDoc */
	public function logLinterData( IPageConfig $pageConfig, array $lints ): void {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return;
		}

		$revId = $pageConfig->getRevisionId();
		$title = Title::newFromLinkTarget(
			$pageConfig->getLinkTarget()
		)->getPrefixedText();
		$pageInfo = $this->getPageInfo( $pageConfig, [ $title ] );
		$latest = $pageInfo[$title]['revId'];

		// Only send the request if it the latest revision
		if ( $revId !== null && $revId === $latest ) {
			$this->hookRunner->onParserLogLinterData(
				$title, $revId, $lints
			);
		}
	}

}
