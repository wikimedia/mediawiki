<?php
/**
 * Resource loader module for skin stylesheets.
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
	 * @param $context ResourceLoaderContext
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		$logo = $this->getConfig()->get( 'Logo' );
		$logoSVG = $this->getConfig()->get( 'LogoSVG' );
		$styles = parent::getStyles( $context );
		if ( $logoSVG ) {
			$styles['all'][] = '.mw-wiki-logo { ' .
				'background-image: ' .
					CSSMin::buildUrlValue( $logo ) . '; ' .
				'background-image: -webkit-linear-gradient(transparent, transparent), ' .
					CSSMin::buildUrlValue( $logoSVG ) . '; ' .
				'background-image: linear-gradient( transparent, transparent), ' . 
					CSSMin::buildUrlValue( $logoSVG ) . '; }';
		} else {
			$styles['all'][] = '.mw-wiki-logo { ' .
				'background-image: ' .
					CSSMin::buildUrlValue( $logo ) . '; }';
		}
		return $styles;
	}


	/**
	 * @param $context ResourceLoaderContext
	 * @return bool
	 */
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		// Regardless of whether the files are specified, we always
		// provide mw-wiki-logo styles.
		return false;
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return int|mixed
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		$parentMTime = parent::getModifiedTime( $context );
		return max( $parentMTime, $this->getHashMtime( $context ) );
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return string: Hash
	 */
	public function getModifiedHash( ResourceLoaderContext $context ) {
		$logo = $this->getConfig()->get( 'Logo' );
		$logoSVG = $this->getConfig()->get( 'LogoSVG' );
		return md5( parent::getModifiedHash( $context ) . $logo . json_encode( $logoSVG ) );
	}
}
