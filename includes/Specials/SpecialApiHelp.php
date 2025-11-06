<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use LogicException;
use MediaWiki\Api\ApiHelp;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Utils\UrlUtils;

/**
 * Redirect to help pages served by api.php.
 *
 * For situations where linking to full api.php URLs is not wanted
 * or not possible, e.g. in edit summaries.
 *
 * @ingroup SpecialPage
 */
class SpecialApiHelp extends UnlistedSpecialPage {

	private UrlUtils $urlUtils;

	public function __construct(
		UrlUtils $urlUtils
	) {
		parent::__construct( 'ApiHelp' );
		$this->urlUtils = $urlUtils;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->getOutput()->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
		if ( !$par ) {
			$par = 'main';
		}

		// These come from transclusions
		$request = $this->getRequest();
		$options = [
			'action' => 'help',
			'nolead' => true,
			'submodules' => $request->getCheck( 'submodules' ),
			'recursivesubmodules' => $request->getCheck( 'recursivesubmodules' ),
			'title' => $request->getVal( 'title', $this->getPageTitle( '$1' )->getPrefixedText() ),
		];

		// These are for linking from wikitext, since url parameters are a pain
		// to do.
		while ( true ) {
			if ( str_starts_with( $par, 'sub/' ) ) {
				$par = substr( $par, 4 );
				$options['submodules'] = 1;
				continue;
			}

			if ( str_starts_with( $par, 'rsub/' ) ) {
				$par = substr( $par, 5 );
				$options['recursivesubmodules'] = 1;
				continue;
			}

			$moduleName = $par;
			break;
		}
		if ( !isset( $moduleName ) ) {
			throw new LogicException( 'Module name should have been found' );
		}

		if ( !$this->including() ) {
			unset( $options['nolead'], $options['title'] );
			$options['modules'] = $moduleName;
			$link = wfAppendQuery( (string)$this->urlUtils->expand( wfScript( 'api' ), PROTO_CURRENT ), $options );
			$this->getOutput()->redirect( $link );
			return;
		}

		$main = new ApiMain( $this->getContext(), false );
		try {
			$module = $main->getModuleFromPath( $moduleName );
		} catch ( ApiUsageException ) {
			$this->getOutput()->addHTML( Html::errorBox(
				$this->msg( 'apihelp-no-such-module', $moduleName )->inContentLanguage()->parse()
			) );
			return;
		}

		ApiHelp::getHelp( $this->getContext(), $module, $options );
	}

	/** @inheritDoc */
	public function isIncludable() {
		return true;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialApiHelp::class, 'SpecialApiHelp' );
