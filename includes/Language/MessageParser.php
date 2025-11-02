<?php

namespace MediaWiki\Language;

use LogicException;
use MediaWiki\Context\RequestContext;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\OutputTransform\OutputTransformPipeline;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\StubObject\StubObject;
use MediaWiki\StubObject\StubUserLang;
use Psr\Log\LoggerInterface;

/**
 * Service for transformation of interface message text.
 *
 * @since 1.44
 */
class MessageParser {
	private const DEPTH_EXCEEDED_MESSAGE =
		'<span class="error">Message parse depth limit exceeded</span>';

	private ParserFactory $parserFactory;
	private OutputTransformPipeline $outputPipeline;
	private LanguageFactory $langFactory;
	private LoggerInterface $logger;

	/** @var Parser[] Cached Parser objects */
	private array $parsers = [];
	/** @var ParserOptions[] Parser options associated with each Parser */
	private array $parserOptions = [];
	/** @var int Index into $this->parsers for the active Parser */
	private int $curParser = -1;

	/**
	 * Parsing some messages may require parsing another message first, due to special page
	 * transclusion and some hooks (T372891). This constant is the limit of nesting depth where
	 * we'll display an error instead of the other message.
	 */
	private const MAX_PARSER_DEPTH = 5;

	public function __construct(
		ParserFactory $parserFactory,
		OutputTransformPipeline $outputPipeline,
		LanguageFactory $languageFactory,
		LoggerInterface $logger
	) {
		$this->parserFactory = $parserFactory;
		$this->outputPipeline = $outputPipeline;
		$this->langFactory = $languageFactory;
		$this->logger = $logger;
	}

	/**
	 * Run message text through the preprocessor, expanding parser functions
	 *
	 * @param string $message
	 * @param bool $interface
	 * @param Language|string|null $language Language for {{PLURAL:}} etc., or
	 *   null for the user or content language depending on $interface
	 * @param PageReference|null $page
	 * @return string
	 */
	public function transform(
		$message,
		$interface = false,
		$language = null,
		?PageReference $page = null
	) {
		// Avoid creating parser if nothing to transform
		if ( !str_contains( $message, '{{' ) ) {
			return $message;
		}

		$parser = $this->acquireParser();
		if ( !$parser ) {
			return self::DEPTH_EXCEEDED_MESSAGE;
		}

		$popts = $this->getOptions( $interface, $language );
		$page ??= $this->getPlaceholderTitle();

		try {
			return $parser->transformMsg( $message, $popts, $page );
		} finally {
			$this->releaseParser( $parser );
		}
	}

	/**
	 * @param string $text
	 * @param ?PageReference $contextPage The context page, or null to use a placeholder
	 * @param bool $lineStart Whether this should be parsed in start-of-line context
	 * @param bool $interface Whether this is an interface message
	 * @param Language|StubUserLang|string|null $language Language for {{PLURAL:}} etc., or
	 *   null for the user or content language depending on $interface
	 * @return ParserOutput
	 */
	public function parse(
		string $text,
		?PageReference $contextPage = null,
		bool $lineStart = true,
		bool $interface = false,
		$language = null
	): ParserOutput {
		$options = [
			'allowTOC' => false,
			'enableSectionEditLinks' => false,
			// Wrapping messages in an extra <div> is probably not expected. If
			// they're outside the content area they probably shouldn't be
			// targeted by CSS that's targeting the parser output, and if
			// they're inside they already are from the outer div.
			'unwrap' => true,
			'userLang' => $language,
		];
		$parser = $this->acquireParser();
		if ( !$parser ) {
			return new ParserOutput( self::DEPTH_EXCEEDED_MESSAGE );
		}
		$popts = $this->getOptions( $interface, $language );
		$contextPage ??= $this->getPlaceholderTitle();

		try {
			$po = $parser->parse( $text, $contextPage, $popts, $lineStart );
			// Run the post-processing pipeline
			return $this->outputPipeline->run( $po, $popts, $options );
		} finally {
			$this->releaseParser( $parser );
		}
	}

	/**
	 * @param string $text
	 * @param ?PageReference $page The context title, or null to use a placeholder
	 * @param bool $lineStart Whether this is at the start of a line
	 * @param bool $interface Whether this is an interface message
	 * @param Language|StubUserLang|string|null $language Target language
	 * @return ParserOutput
	 */
	public function parseWithoutPostprocessing(
		$text,
		?PageReference $page = null,
		$lineStart = true,
		$interface = false,
		$language = null
	): ParserOutput {
		$parser = $this->acquireParser();
		if ( !$parser ) {
			return new ParserOutput( self::DEPTH_EXCEEDED_MESSAGE );
		}
		$popts = $this->getOptions( $interface, $language );
		$page ??= $this->getPlaceholderTitle();

		try {
			return $parser->parse( $text, $page, $popts, $lineStart );
		} finally {
			$this->releaseParser( $parser );
		}
	}

	/**
	 * Configure parser options
	 *
	 * @param bool $interface
	 * @param Language|StubUserLang|string|null $targetLanguage
	 * @return ParserOptions
	 */
	private function getOptions( $interface, $targetLanguage ): ParserOptions {
		if ( $this->curParser < 0 ) {
			throw new LogicException( 'getOptions must be called after acquireParser' );
		}
		if ( isset( $this->parserOptions[$this->curParser] ) ) {
			$popts = $this->parserOptions[$this->curParser];
		} else {
			$popts = $this->createOptions( $this->curParser );
		}
		$popts->setInterfaceMessage( $interface );
		$popts->setTargetLanguage( $this->normalizeTargetLanguage( $targetLanguage ) );
		return $popts;
	}

	/**
	 * Create and maybe cache a ParserOptions object
	 *
	 * @param int $cacheIndex
	 * @return ParserOptions
	 */
	private function createOptions( $cacheIndex ): ParserOptions {
		$context = RequestContext::getMain();
		$user = $context->getUser();
		if ( !$user->isSafeToLoad() ) {
			// It isn't safe to use the context user yet, so don't try to get a
			// ParserOptions for it. And don't cache this ParserOptions
			// either.
			$po = ParserOptions::newFromAnon();
			$po->setAllowUnsafeRawHtml( false );
			return $po;
		}

		$po = ParserOptions::newFromContext( $context );
		$po->setIsMessage( true );
		// Messages may take parameters that could come
		// from malicious sources. As a precaution, disable
		// the <html> parser tag when parsing messages.
		$po->setAllowUnsafeRawHtml( false );
		$this->parserOptions[$cacheIndex] = $po;
		return $po;
	}

	/**
	 * @param Language|string|null $lang
	 * @return Language|null
	 */
	private function normalizeTargetLanguage( $lang ): ?Language {
		if ( is_string( $lang ) ) {
			return $this->langFactory->getLanguage( $lang );
		} elseif ( $lang !== null ) {
			StubObject::unstub( $lang );
		}
		return $lang;
	}

	private function getPlaceholderTitle(): PageReference {
		return PageReferenceValue::localReference( NS_SPECIAL, 'Badtitle/MessageParser' );
	}

	/**
	 * Attempt to get a free parser from the cache. If none exists, create one,
	 * up to a limit of MAX_PARSER_DEPTH. If the limit is exceeded, return null.
	 *
	 * If a parser is returned, it must be released with releaseParser().
	 *
	 * @return Parser|null
	 */
	private function acquireParser(): ?Parser {
		$index = $this->curParser + 1;
		if ( $index >= self::MAX_PARSER_DEPTH ) {
			$this->logger->debug( __METHOD__ . ": Refusing to create a new parser with index {$index}" );
			return null;
		}
		$parser = $this->parsers[ $index ] ?? null;
		if ( !$parser ) {
			$this->logger->debug( __METHOD__ . ": Creating a new parser with index {$index}" );
			$parser = $this->parserFactory->create();
		}
		$this->parsers[ $index ] = $parser;
		$this->curParser = $index;
		return $parser;
	}

	/**
	 * Release a parser previously acquired by acquireParser().
	 *
	 * @param Parser $parser
	 */
	private function releaseParser( Parser $parser ) {
		if ( $this->parsers[$this->curParser] !== $parser ) {
			throw new LogicException( 'releaseParser called with the wrong ' .
				"parser instance: #{$this->curParser} = " .
				gettype( $this->parsers[$this->curParser] ) );
		}
		$this->curParser--;
	}

}
