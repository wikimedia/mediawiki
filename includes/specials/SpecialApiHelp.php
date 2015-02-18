<?php
/**
 * Implements Special:ApiHelp
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
 * @ingroup SpecialPage
 */

/**
 * Special page to redirect to API help pages, for situations where linking to
 * the api.php endpoint is not wanted.
 *
 * @ingroup SpecialPage
 */
class SpecialApiHelp extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'ApiHelp' );
	}

	public function execute( $par ) {
		if ( empty( $par ) ) {
			$par = 'main';
		}

		// These come from transclusions
		$request = $this->getRequest();
		// Initialize options to query string parameters.
		$options = $request->getQueryValues();
		$options = array_merge( $options, array(
			'action' => 'help',
			'nolead' => true,
			'submodules' => $request->getCheck( 'submodules' ),
			'recursivesubmodules' => $request->getCheck( 'recursivesubmodules' ),
			'title' => $request->getVal( 'title', $this->getPageTitle( '$1' )->getPrefixedText() ),
		) );

		// These are for linking from wikitext, since url parameters are a pain
		// to do.
		while ( true ) {
			if ( substr( $par, 0, 4 ) === 'sub/' ) {
				$par = substr( $par, 4 );
				$options['submodules'] = 1;
				continue;
			}

			if ( substr( $par, 0, 5 ) === 'rsub/' ) {
				$par = substr( $par, 5 );
				$options['recursivesubmodules'] = 1;
				continue;
			}

			break;
		}

		// If the "subpage" contains a '?', parse what comes after as a query string.
		$split = explode( '?', $par, 2 );
		if ( count( $split ) > 1 ) {
			$par = $split[0];
			parse_str( $split[1], $qsOpts );
			// Options from the "subpage" won't override those from setup and the actual query string.
			$options = array_merge( $qsOpts, $options );
		}

		$moduleName = $par;

		if ( !$this->including() ) {
			unset( $options['nolead'], $options['title'] );
			$options['modules'] = $moduleName;
			$link = wfAppendQuery( wfExpandUrl( wfScript( 'api' ), PROTO_CURRENT ), $options );
			$this->getOutput()->redirect( $link );
			return;
		}

		// From here on the Api help is transcluded.

		// Set some $options into the current context before getting help.
		// FIXME for now focus on language.
		if ( isset( $options['uselang'] ) ) {
			$this->getContext()->setLanguage( $options['uselang'] );
		}

		$main = new ApiMain( $this->getContext(), false );
		try {
			$module = $main->getModuleFromPath( $moduleName );
		} catch ( UsageException $ex ) {
			$this->getOutput()->addHTML( Html::rawElement( 'span', array( 'class' => 'error' ),
				$this->msg( 'apihelp-no-such-module', $moduleName )->inContentLanguage()->parse()
			) );
			return;
		}

		ApiHelp::getHelp( $this->getContext(), $module, $options );
	}

	public function isIncludable() {
		return true;
	}
}
