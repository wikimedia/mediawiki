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
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		$logo = $this->getLogo( $this->getConfig() );
		$styles = parent::getStyles( $context );
		if ( is_array( $logo ) ) {
			if ( isset( $logo['png'] ) ) {
				$default = $logo['png'];
			} elseif ( isset( $logo['1x'] ) ) {
				$default = $logo['1x'];
			} else {
				$default = '';
			}
		} else {
			$default = !is_array( $logo ) ? $logo : '';
		}

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
			}
			if ( isset( $logo['1.5x'] ) && !isset( $logo['svg'] ) ) {
				$styles[
					'(-webkit-min-device-pixel-ratio: 1.5), ' .
					'(min--moz-device-pixel-ratio: 1.5), ' .
					'(min-resolution: 1.5dppx), ' .
					'(min-resolution: 144dpi)'
				][] = '.mw-wiki-logo { background-image: ' .
				CSSMin::buildUrlValue( $logo['1.5x'] ) . ';' .
				'background-size: 135px auto; }';
			}
			if ( isset( $logo['2x'] ) && !isset( $logo['svg'] ) ) {
				$styles[
					'(-webkit-min-device-pixel-ratio: 2), ' .
					'(min--moz-device-pixel-ratio: 2),' .
					'(min-resolution: 2dppx), ' .
					'(min-resolution: 192dpi)'
				][] = '.mw-wiki-logo { background-image: ' .
				CSSMin::buildUrlValue( $logo['2x'] ) . ';' .
				'background-size: 135px auto; }';
			}
		}

		return $styles;
	}

	/**
	 * @param Config $conf
	 * @return string|array Single url if no variants are defined
	 *  or array of logo urls keyed by dppx in form "<float>x".
	 *  Key "1x" is always defined.
	 */
	public static function getLogo( Config $conf ) {
		$logo = $conf->get( 'Logo' );
		$logoHD = $conf->get( 'LogoHD' );

		$logoUrls = [];

		if ( is_array( $logo ) ) {
			if ( isset( $logo['png'] ) ) {
				$logoUrls['png'] = OutputPage::transformResourcePath(
					$conf,
					$logo['png']
				);
			}
			if ( isset( $logo['svg'] ) ) {
				$logoUrls['svg'] = OutputPage::transformResourcePath(
					$conf,
					$logo['svg']
				);
			}
			return $logoUrls;
		} else {
			$logo1Url = OutputPage::transformResourcePath( $conf, $logo );
		}

		if ( !$logoHD && isset( $logo1Url ) ) {
			return $logo1Url;
		}

		$logoUrls = [
			'1x' => $logo1Url,
		];

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
