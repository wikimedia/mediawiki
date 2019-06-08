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
use MediaWiki\Linker\LinkRendererFactory;

use MediaWiki\Special\SpecialPageFactory;

/**
 * @since 1.32
 */
class ParserFactory {
	/** @var array */
	private $parserConf;

	/** @var MagicWordFactory */
	private $magicWordFactory;

	/** @var Language */
	private $contLang;

	/** @var string */
	private $urlProtocols;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var Config */
	private $siteConfig;

	/** @var LinkRendererFactory */
	private $linkRendererFactory;

	/**
	 * @param array $parserConf See $wgParserConf documentation
	 * @param MagicWordFactory $magicWordFactory
	 * @param Language $contLang Content language
	 * @param string $urlProtocols As returned from wfUrlProtocols()
	 * @param SpecialPageFactory $spFactory
	 * @param Config $siteConfig
	 * @param LinkRendererFactory $linkRendererFactory
	 * @since 1.32
	 */
	public function __construct(
		array $parserConf, MagicWordFactory $magicWordFactory, Language $contLang, $urlProtocols,
		SpecialPageFactory $spFactory, Config $siteConfig, LinkRendererFactory $linkRendererFactory
	) {
		$this->parserConf = $parserConf;
		$this->magicWordFactory = $magicWordFactory;
		$this->contLang = $contLang;
		$this->urlProtocols = $urlProtocols;
		$this->specialPageFactory = $spFactory;
		$this->siteConfig = $siteConfig;
		$this->linkRendererFactory = $linkRendererFactory;
	}

	/**
	 * @return Parser
	 * @since 1.32
	 */
	public function create() : Parser {
		return new Parser( $this->parserConf, $this->magicWordFactory, $this->contLang, $this,
			$this->urlProtocols, $this->specialPageFactory, $this->siteConfig,
			$this->linkRendererFactory );
	}
}
