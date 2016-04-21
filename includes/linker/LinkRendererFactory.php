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
 * @license GPL-2.0+
 * @author Kunal Mehta <legoktm@member.fsf.org>
 */
namespace MediaWiki\Linker;

use TitleFormatter;

/**
 * Factory to create LinkRender objects
 * @since 1.28
 */
class LinkRendererFactory {

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @param TitleFormatter $titleFormatter
	 */
	public function __construct( TitleFormatter $titleFormatter ) {
		$this->titleFormatter = $titleFormatter;
	}

	/**
	 * @return LinkRenderer
	 */
	public function create() {
		global $wgUser;

		$linkRenderer = new LinkRenderer( $this->titleFormatter );
		$linkRenderer->setStubThreshold( $wgUser->getStubThreshold() );

		return $linkRenderer;
	}

	/**
	 * @param array $options
	 * @return LinkRenderer
	 */
	public function createFromLegacyOptions( array $options ) {
		$linkRenderer = new LinkRenderer( $this->titleFormatter );

		if ( in_array( 'noclasses', $options, true ) ) {
			$linkRenderer->setNoClasses( true );
		}

		if ( in_array( 'forcearticlepath', $options, true ) ) {
			$linkRenderer->setForceArticlePath( true );
		}

		if ( in_array( 'http', $options, true ) ) {
			$linkRenderer->setExpandURLs( PROTO_HTTP );
		} elseif ( in_array( 'https', $options, true ) ) {
			$linkRenderer->setExpandURLs( PROTO_HTTPS );
		}

		if ( isset( $options['stubThreshold'] ) ) {
			$linkRenderer->setStubThreshold(
				$options['stubThreshold']
			);
		}

		return $linkRenderer;
	}
}
