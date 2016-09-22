<?php
/**
 * @defgroup Database Database
 *
 * This file deals with database interface functions
 * and query specifics/optimisations.
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
 * @ingroup Database
 */
use MediaWiki\MediaWikiServices;
use MediaWiki\Logger\LoggerFactory;

class MimeMagic extends MimeAnalyzer {
	/**
	 * Get an instance of this class
	 * @return MimeMagic
	 * @deprecated since 1.28
	 */
	public static function singleton() {
		return MediaWikiServices::getInstance()->getMIMEAnalyzer();
	}

	/**
	 * @param array $params
	 * @param Config $mainConfig
	 * @return array
	 */
	public static function applyDefaultParameters( array $params, Config $mainConfig ) {
		global $IP;

		$logger = LoggerFactory::getInstance( 'Mime' );
		$params += [
			'typeFile' => $mainConfig->get( 'MimeTypeFile' ),
			'infoFile' => $mainConfig->get( 'MimeInfoFile' ),
			'xmlTypes' => $mainConfig->get( 'XMLMimeTypes' ),
			'detectorCommand' => $mainConfig->get( 'MimeDetectorCommand' ),
			'dataCallback' =>
				function ( $mimeAnalyzer, $head, $tail, $file, &$mime ) use ( $logger ) {
					// Also test DjVu
					$deja = new DjVuImage( $file );
					if ( $deja->isValid() ) {
						$logger->info( __METHOD__ . ": detected $file as image/vnd.djvu\n" );

						$mime = 'image/vnd.djvu';
					}
					// Some strings by reference for performance - assuming well-behaved hooks
					Hooks::run(
						'MimeMagicGuessFromContent',
						[ $mimeAnalyzer, &$head, &$tail, $file, &$mime ]
					);
				},
			'extCallback' => function ( $mimeAnalyzer, $ext, &$mime ) {
				// Media handling extensions can improve the MIME detected
				Hooks::run( 'MimeMagicImproveFromExtension', [ $mimeAnalyzer, $ext, &$mime ] );
			},
			'initCallback' => function ( $mimeAnalyzer ) {
			 	// Allow media handling extensions adding MIME-types and MIME-info
				Hooks::run( 'MimeMagicInit', [ $mimeAnalyzer ] );
			 },
			'logger' => $logger
		];

		if ( $params['infoFile'] === 'includes/mime.info' ) {
			$params['infoFile'] = "$IP/" . $params['infoFile'];
		}

		if ( $params['typeFile'] === 'includes/types.info' ) {
			$params['typeFile'] = "$IP/" . $params['typeFile'];
		}

		return $params;
	}
}
