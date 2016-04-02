<?php
namespace MediaWiki\Parser;

/**
 * Factory for Parser instances
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
 * @ingroup Parser
 */
use CoreParserFunctions;
use CoreTagHooks;
use Parser;

/**
 * @ingroup Parser
 */
class ParserFactory {

	/**
	 * @var PreprocessorFactory
	 */
	private $preprocessorFactory;

	/**
	 * @var string[]|null
	 */
	private $urlProtocols = null;

	/**
	 * @var string[]
	 */
	private $urlProtocolsWithoutProtRel = [];

	/**
	 * @var string
	 */
	private $limitReportPrefix = '';

	/**
	 * ParserFactory constructor.
	 *
	 * @param PreprocessorFactory $preprocessorFactory
	 */
	public function __construct( PreprocessorFactory $preprocessorFactory ) {
		$this->preprocessorFactory = $preprocessorFactory;
	}

	/**
	 * @param string $limitReportPrefix
	 */
	public function setLimitReportPrefix( $limitReportPrefix ) {
		$this->limitReportPrefix = $limitReportPrefix;
	}

	/**
	 * @param string[] $urlProtocols
	 * @param array $urlProtocolsWithoutProtRel
	 */
	public function setUrlProtocols( array $urlProtocols, array $urlProtocolsWithoutProtRel ) {
		$this->urlProtocols = $urlProtocols;
		$this->urlProtocolsWithoutProtRel = $urlProtocolsWithoutProtRel;
	}

	/**
	 * @return Parser
	 */
	public function newParser() {
		$parser = new Parser( $this->preprocessorFactory );

		if ( is_array( $this->urlProtocols ) ) {
			$parser->setUrlProtocols( $this->urlProtocols, $this->urlProtocolsWithoutProtRel );
		}

		$parser->setLimitReportPrefix( $this->limitReportPrefix );

		CoreParserFunctions::register( $parser ); //FIXME: move to factory??
		CoreTagHooks::register( $parser );

		return $parser;
	}
}
