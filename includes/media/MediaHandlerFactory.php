<?php
/**
 * Media-handling base classes and generic functionality.
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
 * @ingroup Media
 */

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

	/** @var LoggerInterface */
	private $logger;

	/** @var array */
	private $registry;

	/**
	 * Instance cache of MediaHandler objects by mimetype
	 *
	 * @var MediaHandler[]
	 */
	private $handlers;

	public function __construct(
		LoggerInterface $logger,
		array $registry
	) {
		$this->logger = $logger;
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
	 * @return MediaHandler|false
	 */
	public function getHandler( $type ) {
		if ( isset( $this->handlers[$type] ) ) {
			return $this->handlers[$type];
		}

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
			}
		} else {
			$this->logger->debug(
				'no handler found for {type}.',
				[ 'type' => $type ]
			);
			$handler = false;
		}

		$this->handlers[$type] = $handler;
		return $handler;
	}
}
