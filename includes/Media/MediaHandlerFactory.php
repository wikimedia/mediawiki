<?php
/**
 * Media-handling base classes and generic functionality.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

namespace MediaWiki\Media;

use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageFactory;
use Psr\Log\LoggerInterface;

/**
 * Class to construct MediaHandler objects
 *
 * @since 1.28
 */
class MediaHandlerFactory {

	/**
	 * Default, MediaWiki core media handlers
	 */
	private const CORE_HANDLERS = [
		'image/jpeg' => JpegHandler::class,
		'image/png' => PNGHandler::class,
		'image/gif' => GIFHandler::class,
		'image/tiff' => TiffHandler::class,
		'image/webp' => WebPHandler::class,
		'image/x-ms-bmp' => BmpHandler::class,
		'image/x-bmp' => BmpHandler::class,
		'image/x-xcf' => XCFHandler::class,
		'image/svg+xml' => SvgHandler::class, // official
		'image/svg' => SvgHandler::class, // compat
		'image/vnd.djvu' => DjVuHandler::class, // official
		'image/x.djvu' => DjVuHandler::class, // compat
		'image/x-djvu' => DjVuHandler::class, // compat
		'image/jp2' => Jpeg2000Handler::class,
		'image/jpx' => Jpeg2000Handler::class,
	];

	/** @var array */
	private $registry;

	public function __construct(
		private LanguageFactory $langFactory,
		private LoggerInterface $logger,
		array $registry
	) {
		$this->registry = $registry + self::CORE_HANDLERS;
	}

	/**
	 * @param string $type
	 * @return class-string<MediaHandler>|false
	 */
	protected function getHandlerClass( $type ) {
		return $this->registry[$type] ?? false;
	}

	/**
	 * @param string $type mimetype
	 * @param Language|string|null $lang Language object or language code
	 * @return MediaHandler|false
	 */
	public function getHandler(
		$type,
		Language|string|null $lang = null
	) {
		$class = $this->getHandlerClass( $type );
		if ( $class !== false ) {
			/** @var MediaHandler $handler */
			$handler = new $class;
			if ( !$handler->isEnabled() ) {
				$this->logger->debug(
					'{class} is not enabled.',
					[ 'class' => $class ]
				);
				$handler = false;
			} elseif ( $lang !== null ) {
				if ( !$lang instanceof Language ) {
					$lang = $this->langFactory->getLanguage( $lang );
				}
				$handler->setLanguage( $lang );
			}
		} else {
			$this->logger->debug(
				'no handler found for {type}.',
				[ 'type' => $type ]
			);
			$handler = false;
		}

		return $handler;
	}
}

/** @deprecated class alias since 1.46 */
class_alias( MediaHandlerFactory::class, 'MediaHandlerFactory' );
