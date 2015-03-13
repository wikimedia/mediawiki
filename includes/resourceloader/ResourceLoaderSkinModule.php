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

	/* Methods */

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		$conf = $this->getConfig();
		$logo = $conf->get( 'Logo' );
		$logoHD = $conf->get( 'LogoHD' );

		$styles = parent::getStyles( $context );
		if ( is_array( $logo ) ) {
			if ( isset( $logo['png'] ) ) {
				$logopng = OutputPage::transformResourcePath( $conf, $logo['png'] );

				$styles['all'][] = '.mw-wiki-logo { background-image: ' .
					CSSMin::buildUrlValue( $logopng ) . ';' .
					'background-size: 135px auto; }';
			}
			if ( isset( $logo['svg'] ) ) {
				$logosvg = OutputPage::transformResourcePath( $conf, $logo['svg'] );

				$styles['all'][] = '.mw-wiki-logo { ' .
					'background-image: -webkit-linear-gradient(transparent, transparent), ' .
						CSSMin::buildUrlValue( $logosvg ) . '; ' .
					'background-image: linear-gradient( transparent, transparent), ' .
						CSSMin::buildUrlValue( $logosvg ) . ';' .
					'background-size: 135px auto; }';

			}
		} else {
			$logo1 = OutputPage::transformResourcePath( $conf, $logo );

			$styles['all'][] = '.mw-wiki-logo { background-image: ' .
				CSSMin::buildUrlValue( $logo1 ) .
				'; }';
		}

		if ( $logoHD && !isset( $logo['svg'] ) ) {
			if ( isset( $logoHD['1.5x'] ) ) {
				$logo15 = OutputPage::transformResourcePath( $conf, $logoHD['1.5x'] );
				$styles[
					'(-webkit-min-device-pixel-ratio: 1.5), ' .
					'(min--moz-device-pixel-ratio: 1.5), ' .
					'(min-resolution: 1.5dppx), ' .
					'(min-resolution: 144dpi)'
				][] = '.mw-wiki-logo { background-image: ' .
				CSSMin::buildUrlValue( $logo15 ) . ';' .
				'background-size: 135px auto; }';
			}
			if ( isset( $logoHD['2x'] ) ) {
				$logo2 = OutputPage::transformResourcePath( $conf, $logoHD['2x'] );
				$styles[
					'(-webkit-min-device-pixel-ratio: 2), ' .
					'(min--moz-device-pixel-ratio: 2),' .
					'(min-resolution: 2dppx), ' .
					'(min-resolution: 192dpi)'
				][] = '.mw-wiki-logo { background-image: ' .
				CSSMin::buildUrlValue( $logo2 ) . ';' .
				'background-size: 135px auto; }';
			}
		}
		return $styles;
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

	/**
	 * @param ResourceLoaderContext $context
	 * @return string: Hash
	 */
	public function getModifiedHash( ResourceLoaderContext $context ) {
		$logo = $this->getConfig()->get( 'Logo' );
		$logoHD = $this->getConfig()->get( 'LogoHD' );
		return md5( parent::getModifiedHash( $context ) . json_encode( $logo ) . json_encode( $logoHD ) );
	}
}
