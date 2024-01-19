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
 */

namespace MediaWiki\Collation;

use Collation;
use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Common factory to construct collation classes.
 *
 * @since 1.37
 */
class CollationFactory {
	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CategoryCollation,
	];

	private const CORE_COLLATIONS = [
		'uppercase' => [
			'class' => \UppercaseCollation::class,
			'services' => [
				'LanguageFactory',
			]
		],
		'numeric' => [
			'class' => \NumericUppercaseCollation::class,
			'services' => [
				'LanguageFactory',
				'ContentLanguage',
			]
		],
		'identity' => [
			'class' => \IdentityCollation::class,
			'services' => [
				'ContentLanguage',
			]
		],
		'uca-default' => [
			'class' => \IcuCollation::class,
			'services' => [
				'LanguageFactory',
			],
			'args' => [
				'root',
			]
		],
		'uca-default-u-kn' => [
			'class' => \IcuCollation::class,
			'services' => [
				'LanguageFactory',
			],
			'args' => [
				'root-u-kn',
			]
		],
		'xx-uca-ckb' => [
			'class' => \CollationCkb::class,
			'services' => [
				'LanguageFactory',
			]
		],
		'uppercase-ab' => [
			'class' => \AbkhazUppercaseCollation::class,
			'services' => [
				'LanguageFactory',
			]
		],
		'uppercase-ba' => [
			'class' => \BashkirUppercaseCollation::class,
			'services' => [
				'LanguageFactory',
			]
		],
	];

	/** @var ServiceOptions */
	private $options;

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @param ServiceOptions $options
	 * @param ObjectFactory $objectFactory
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		ServiceOptions $options,
		ObjectFactory $objectFactory,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->objectFactory = $objectFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * @return Collation
	 */
	public function getCategoryCollation(): Collation {
		return $this->makeCollation( $this->getDefaultCollationName() );
	}

	public function getDefaultCollationName(): string {
		return $this->options->get( MainConfigNames::CategoryCollation );
	}

	/**
	 * @param string $collationName
	 * @return Collation
	 */
	public function makeCollation( string $collationName ): Collation {
		if ( isset( self::CORE_COLLATIONS[$collationName] ) ) {
			return $this->instantiateCollation( self::CORE_COLLATIONS[$collationName] );
		}

		if ( preg_match( '/^uca-([A-Za-z@=-]+)$/', $collationName, $match ) ) {
			return $this->instantiateCollation( [
				'class' => \IcuCollation::class,
				'services' => [
					'LanguageFactory',
				],
				'args' => [
					$match[1],
				]
			] );
		} elseif ( preg_match( '/^remote-uca-([A-Za-z@=-]+)$/', $collationName, $match ) ) {
			return $this->instantiateCollation( [
				'class' => \RemoteIcuCollation::class,
				'services' => [
					'ShellboxClientFactory'
				],
				'args' => [
					$match[1]
				]
			] );
		}

		// Provide a mechanism for extensions to hook in.
		$collationObject = null;
		$this->hookRunner->onCollation__factory( $collationName, $collationObject );

		if ( !$collationObject instanceof Collation ) {
			throw new InvalidArgumentException( __METHOD__ . ": unknown collation type \"$collationName\"" );
		}

		return $collationObject;
	}

	/**
	 * @param array $spec
	 * @return Collation
	 */
	private function instantiateCollation( $spec ): Collation {
		return $this->objectFactory->createObject(
			$spec,
			[
				'assertClass' => Collation::class
			]
		);
	}

}
