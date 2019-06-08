<?php
/**
 * ResourceLoader module for skin stylesheets.
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
 * @author Timo Tijhof
 */

class ResourceLoaderSkinModule extends ResourceLoaderFileModule {
	/**
	 * All skins are assumed to be compatible with mobile
	 */
	public $targets = [ 'desktop', 'mobile' ];

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		$logo = $this->getLogoData( $this->getConfig() );
		$styles = parent::getStyles( $context );
		$this->normalizeStyles( $styles );

		$default = !is_array( $logo ) ? $logo : $logo['1x'];
		$styles['all'][] = '.mw-wiki-logo { background-image: ' .
				CSSMin::buildUrlValue( $default ) .
				'; }';

		if ( is_array( $logo ) ) {
			if ( isset( $logo['svg'] ) ) {
				$styles['all'][] = '.mw-wiki-logo { ' .
					'background-image: -webkit-linear-gradient(transparent, transparent), ' .
						CSSMin::buildUrlValue( $logo['svg'] ) . '; ' .
					'background-image: linear-gradient(transparent, transparent), ' .
						CSSMin::buildUrlValue( $logo['svg'] ) . ';' .
					'background-size: 135px auto; }';
			} else {
				if ( isset( $logo['1.5x'] ) ) {
					$styles[
						'(-webkit-min-device-pixel-ratio: 1.5), ' .
						'(min--moz-device-pixel-ratio: 1.5), ' .
					'(min-resolution: 1.5dppx), ' .
						'(min-resolution: 144dpi)'
					][] = '.mw-wiki-logo { background-image: ' .
					CSSMin::buildUrlValue( $logo['1.5x'] ) . ';' .
					'background-size: 135px auto; }';
				}
				if ( isset( $logo['2x'] ) ) {
					$styles[
						'(-webkit-min-device-pixel-ratio: 2), ' .
						'(min--moz-device-pixel-ratio: 2), ' .
						'(min-resolution: 2dppx), ' .
						'(min-resolution: 192dpi)'
					][] = '.mw-wiki-logo { background-image: ' .
					CSSMin::buildUrlValue( $logo['2x'] ) . ';' .
					'background-size: 135px auto; }';
				}
			}
		}

		return $styles;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getPreloadLinks( ResourceLoaderContext $context ) {
		return $this->getLogoPreloadlinks();
	}

	/**
	 * Helper method for getPreloadLinks()
	 * @return array
	 */
	private function getLogoPreloadlinks() {
		$logo = $this->getLogoData( $this->getConfig() );

		$logosPerDppx = [];
		$logos = [];

		$preloadLinks = [];

		if ( !is_array( $logo ) ) {
			// No media queries required if we only have one variant
			$preloadLinks[$logo] = [ 'as' => 'image' ];
			return $preloadLinks;
		}

		if ( isset( $logo['svg'] ) ) {
			// No media queries required if we only have a 1x and svg variant
			// because all preload-capable browsers support SVGs
			$preloadLinks[$logo['svg']] = [ 'as' => 'image' ];
			return $preloadLinks;
		}

		foreach ( $logo as $dppx => $src ) {
			// Keys are in this format: "1.5x"
			$dppx = substr( $dppx, 0, -1 );
			$logosPerDppx[$dppx] = $src;
		}

		// Because PHP can't have floats as array keys
		uksort( $logosPerDppx, function ( $a, $b ) {
			$a = floatval( $a );
			$b = floatval( $b );
			// Sort from smallest to largest (e.g. 1x, 1.5x, 2x)
			return $a <=> $b;
		} );

		foreach ( $logosPerDppx as $dppx => $src ) {
			$logos[] = [
				'dppx' => $dppx,
				'src' => $src
			];
		}

		$logosCount = count( $logos );
		// Logic must match ResourceLoaderSkinModule:
		// - 1x applies to resolution < 1.5dppx
		// - 1.5x applies to resolution >= 1.5dppx && < 2dppx
		// - 2x applies to resolution >= 2dppx
		// Note that min-resolution and max-resolution are both inclusive.
		for ( $i = 0; $i < $logosCount; $i++ ) {
			if ( $i === 0 ) {
				// Smallest dppx
				// min-resolution is ">=" (larger than or equal to)
				// "not min-resolution" is essentially "<"
				$media_query = 'not all and (min-resolution: ' . $logos[1]['dppx'] . 'dppx)';
			} elseif ( $i !== $logosCount - 1 ) {
				// In between
				// Media query expressions can only apply "not" to the entire expression
				// (e.g. can't express ">= 1.5 and not >= 2).
				// Workaround: Use <= 1.9999 in place of < 2.
				$upper_bound = floatval( $logos[$i + 1]['dppx'] ) - 0.000001;
				$media_query = '(min-resolution: ' . $logos[$i]['dppx'] .
					'dppx) and (max-resolution: ' . $upper_bound . 'dppx)';
			} else {
				// Largest dppx
				$media_query = '(min-resolution: ' . $logos[$i]['dppx'] . 'dppx)';
			}

			$preloadLinks[$logos[$i]['src']] = [
				'as' => 'image',
				'media' => $media_query
			];
		}

		return $preloadLinks;
	}

	/**
	 * Ensure all media keys use array values.
	 *
	 * Normalises arrays returned by the ResourceLoaderFileModule::getStyles() method.
	 *
	 * @param array &$styles Associative array, keys are strings (media queries),
	 *   values are strings or arrays
	 */
	private function normalizeStyles( &$styles ) {
		foreach ( $styles as $key => $val ) {
			if ( !is_array( $val ) ) {
				$styles[$key] = [ $val ];
			}
		}
	}

	/**
	 * @since 1.31
	 * @param Config $conf
	 * @return string|array Single url if no variants are defined,
	 *  or an array of logo urls keyed by dppx in form "<float>x".
	 *  Key "1x" is always defined. Key "svg" may also be defined,
	 *  in which case variants other than "1x" are omitted.
	 */
	protected function getLogoData( Config $conf ) {
		$logo = $conf->get( 'Logo' );
		$logoHD = $conf->get( 'LogoHD' );

		$logo1Url = OutputPage::transformResourcePath( $conf, $logo );

		if ( !$logoHD ) {
			return $logo1Url;
		}

		$logoUrls = [
			'1x' => $logo1Url,
		];

		if ( isset( $logoHD['svg'] ) ) {
			$logoUrls['svg'] = OutputPage::transformResourcePath(
				$conf,
				$logoHD['svg']
			);
		} else {
			// Only 1.5x and 2x are supported
			if ( isset( $logoHD['1.5x'] ) ) {
				$logoUrls['1.5x'] = OutputPage::transformResourcePath(
					$conf,
					$logoHD['1.5x']
				);
			}
			if ( isset( $logoHD['2x'] ) ) {
				$logoUrls['2x'] = OutputPage::transformResourcePath(
					$conf,
					$logoHD['2x']
				);
			}
		}

		return $logoUrls;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return bool
	 */
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		// Regardless of whether the files are specified, we always
		// provide mw-wiki-logo styles.
		return false;
	}

	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		$summary = parent::getDefinitionSummary( $context );
		$summary[] = [
			'logo' => $this->getConfig()->get( 'Logo' ),
			'logoHD' => $this->getConfig()->get( 'LogoHD' ),
		];
		return $summary;
	}
}
