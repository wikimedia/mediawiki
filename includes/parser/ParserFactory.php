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
 * @ingroup Parser
 */

use MediaWiki\BadFileLookup;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Special\SpecialPageFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @since 1.32
 */
class ParserFactory {
	/** @var ServiceOptions */
	private $svcOptions;

	/** @var MagicWordFactory */
	private $magicWordFactory;

	/** @var Language */
	private $contLang;

	/** @var string */
	private $urlProtocols;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var LinkRendererFactory */
	private $linkRendererFactory;

	/** @var NamespaceInfo */
	private $nsInfo;

	/** @var LoggerInterface */
	private $logger;

	/** @var BadFileLookup */
	private $badFileLookup;

	/**
	 * Old parameter list, which we support for backwards compatibility, were:
	 *   array $parserConf See $wgParserConf documentation
	 *   MagicWordFactory $magicWordFactory
	 *   Language $contLang Content language
	 *   string $urlProtocols As returned from wfUrlProtocols()
	 *   SpecialPageFactory $spFactory
	 *   Config $siteConfig
	 *   LinkRendererFactory $linkRendererFactory
	 *   NamespaceInfo|null $nsInfo
	 *
	 * Some type declarations were intentionally omitted so that the backwards compatibility code
	 * would work. When backwards compatibility is no longer required, we should remove it, and
	 * and add the omitted type declarations.
	 *
	 * @param ServiceOptions|array $svcOptions
	 * @param MagicWordFactory $magicWordFactory
	 * @param Language $contLang Content language
	 * @param string $urlProtocols As returned from wfUrlProtocols()
	 * @param SpecialPageFactory $spFactory
	 * @param LinkRendererFactory $linkRendererFactory
	 * @param NamespaceInfo|LinkRendererFactory|null $nsInfo
	 * @param LoggerInterface|null $logger
	 * @param BadFileLookup|null $badFileLookup
	 * @since 1.32
	 */
	public function __construct(
		$svcOptions,
		MagicWordFactory $magicWordFactory,
		Language $contLang,
		$urlProtocols,
		SpecialPageFactory $spFactory,
		$linkRendererFactory,
		$nsInfo = null,
		$logger = null,
		BadFileLookup $badFileLookup = null
	) {
		// @todo Do we need to retain compat for constructing this class directly?
		if ( !$nsInfo ) {
			wfDeprecated( __METHOD__ . ' with no NamespaceInfo argument', '1.34' );
			$nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
		}
		if ( $linkRendererFactory instanceof Config ) {
			// Old calling convention had an array in the format of $wgParserConf as the first
			// parameter, and a Config as the sixth, with LinkRendererFactory as the seventh.
			wfDeprecated( __METHOD__ . ' with Config parameter', '1.34' );
			$svcOptions = new ServiceOptions( Parser::$constructorOptions,
				$svcOptions,
				[ 'class' => Parser::class,
					'preprocessorClass' => Parser::getDefaultPreprocessorClass() ],
				func_get_arg( 5 )
			);
			$linkRendererFactory = func_get_arg( 6 );
			$nsInfo = func_num_args() > 7 ? func_get_arg( 7 ) : null;
		}
		$svcOptions->assertRequiredOptions( Parser::$constructorOptions );

		wfDebug( __CLASS__ . ": using preprocessor: {$svcOptions->get( 'preprocessorClass' )}\n" );

		$this->svcOptions = $svcOptions;
		$this->magicWordFactory = $magicWordFactory;
		$this->contLang = $contLang;
		$this->urlProtocols = $urlProtocols;
		$this->specialPageFactory = $spFactory;
		$this->linkRendererFactory = $linkRendererFactory;
		$this->nsInfo = $nsInfo;
		$this->logger = $logger ?: new NullLogger();
		$this->badFileLookup = $badFileLookup ??
			MediaWikiServices::getInstance()->getBadFileLookup();
	}

	/**
	 * @return Parser
	 * @since 1.32
	 */
	public function create() : Parser {
		return new Parser(
			$this->svcOptions,
			$this->magicWordFactory,
			$this->contLang,
			$this,
			$this->urlProtocols,
			$this->specialPageFactory,
			$this->linkRendererFactory,
			$this->nsInfo,
			$this->logger,
			$this->badFileLookup
		);
	}
}
