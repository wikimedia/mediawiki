<?php
/**
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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

/**
 * Module for user preference customizations
 */
class ResourceLoaderUserOptionsModule extends ResourceLoaderModule {

	/* Protected Members */

	protected $modifiedTime = array();

	/* Methods */

	public function getModifiedTime( ResourceLoaderContext $context ) {
		$hash = $context->getHash();
		if ( isset( $this->modifiedTime[$hash] ) ) {
			return $this->modifiedTime[$hash];
		}

		global $wgUser;

		return $this->modifiedTime[$hash] = wfTimestamp( TS_UNIX, $wgUser->getTouched() );
	}

	public function getScript( ResourceLoaderContext $context ) {
		global $wgUser;
		return Xml::encodeJsCall( 'mediaWiki.user.options.set', 
			array( $wgUser->getOptions() ) );
	}

	public function getStyles( ResourceLoaderContext $context ) {
		global $wgAllowUserCssPrefs, $wgUser;

		if ( $wgAllowUserCssPrefs ) {
			$options = $wgUser->getOptions();

			// Build CSS rules
			$rules = array();
			if ( $options['underline'] < 2 ) {
				$rules[] = "a { text-decoration: " . 
					( $options['underline'] ? 'underline' : 'none' ) . "; }";
			}
			if ( $options['highlightbroken'] ) {
				$rules[] = "a.new, #quickbar a.new { color: #ba0000; }\n";
			} else {
				$rules[] = "a.new, #quickbar a.new, a.stub, #quickbar a.stub { color: inherit; }";
				$rules[] = "a.new:after, #quickbar a.new:after { content: '?'; color: #ba0000; }";
				$rules[] = "a.stub:after, #quickbar a.stub:after { content: '!'; color: #772233; }";
			}
			if ( $options['justify'] ) {
				$rules[] = "#article, #bodyContent, #mw_content { text-align: justify; }\n";
			}
			if ( !$options['showtoc'] ) {
				$rules[] = "#toc { display: none; }\n";
			}
			if ( !$options['editsection'] ) {
				$rules[] = ".editsection { display: none; }\n";
			}
			if ( $options['editfont'] !== 'default' ) {
				$rules[] = "textarea { font-family: {$options['editfont']}; }\n";
			}
			$style = implode( "\n", $rules );
			if ( $this->getFlip( $context ) ) {
				$style = CSSJanus::transform( $style, true, false );
			}
			return array( 'all' => $style );
		}
		return array();
	}

	public function getFlip( $context ) {
		global $wgContLang;

		return $wgContLang->getDir() !== $context->getDirection();
	}

	public function getGroup() {
		return 'private';
	}
}
