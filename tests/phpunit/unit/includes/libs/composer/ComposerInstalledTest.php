<?php

class ComposerInstalledTest extends PHPUnit\Framework\TestCase {
	/**
	 * @covers ComposerInstalled::__construct
	 * @covers ComposerInstalled::getInstalledDependencies
	 *
	 * @dataProvider provideInstalled
	 */
	public function testGetInstalledDependencies( string $location ) {
		$installed = new ComposerInstalled( $location );
		$this->assertEquals( [
		'leafo/lessphp' => [
			'version' => '0.5.0',
			'type' => 'library',
			'licenses' => [ 'MIT', 'GPL-3.0-only' ],
			'authors' => [
				[
					'name' => 'Leaf Corcoran',
					'email' => 'leafot@gmail.com',
					'homepage' => 'http://leafo.net',
				],
			],
			'description' => 'lessphp is a compiler for LESS written in PHP.',
		],
		'psr/log' => [
			'version' => '1.0.0',
			'type' => 'library',
			'licenses' => [ 'MIT' ],
			'authors' => [
				[
					'name' => 'PHP-FIG',
					'homepage' => 'http://www.php-fig.org/',
				],
			],
			'description' => 'Common interface for logging libraries',
		],
		'cssjanus/cssjanus' => [
			'version' => '1.1.1',
			'type' => 'library',
			'licenses' => [ 'Apache-2.0' ],
			'authors' => [
			],
			'description' => 'Convert CSS stylesheets between left-to-right ' .
				'and right-to-left.',
		],
		'cdb/cdb' => [
			'version' => '1.0.0',
			'type' => 'library',
			'licenses' => [ 'GPLv2' ],
			'authors' => [
				[
					'name' => 'Tim Starling',
					'email' => 'tstarling@wikimedia.org',
				],
				[
					'name' => 'Chad Horohoe',
					'email' => 'chad@wikimedia.org',
				],
			],
			'description' => 'Constant Database (CDB) wrapper library for PHP. ' .
				'Provides pure-PHP fallback when dba_* functions are absent.',
		],
		'sebastian/version' => [
			'version' => '2.0.1',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
					'role' => 'lead',
				],
			],
			'description' => 'Library that helps with managing the version ' .
				'number of Git-hosted PHP projects',
		],
		'sebastian/resource-operations' => [
			'version' => '1.0.0',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
			],
			'description' => 'Provides a list of PHP built-in functions that ' .
				'operate on resources',
		],
		'sebastian/recursion-context' => [
			'version' => '3.0.0',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Jeff Welch',
					'email' => 'whatthejeff@gmail.com',
				],
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
				[
					'name' => 'Adam Harvey',
					'email' => 'aharvey@php.net',
				],
			],
			'description' => 'Provides functionality to recursively process PHP ' .
				'variables',
		],
		'sebastian/object-reflector' => [
			'version' => '1.1.1',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
			],
			'description' => 'Allows reflection of object attributes, including ' .
				'inherited and non-public ones',
		],
		'sebastian/object-enumerator' => [
			'version' => '3.0.3',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
			],
			'description' => 'Traverses array structures and object graphs ' .
				'to enumerate all referenced objects',
		],
		'sebastian/global-state' => [
			'version' => '2.0.0',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
			],
			'description' => 'Snapshotting of global state',
		],
		'sebastian/exporter' => [
			'version' => '3.1.0',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Jeff Welch',
					'email' => 'whatthejeff@gmail.com',
				],
				[
					'name' => 'Volker Dusch',
					'email' => 'github@wallbash.com',
				],
				[
					'name' => 'Bernhard Schussek',
					'email' => 'bschussek@2bepublished.at',
				],
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
				[
					'name' => 'Adam Harvey',
					'email' => 'aharvey@php.net',
				],
			],
			'description' => 'Provides the functionality to export PHP ' .
				'variables for visualization',
		],
		'sebastian/environment' => [
			'version' => '3.1.0',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
			],
			'description' => 'Provides functionality to handle PHP ' .
				'environments',
		],
		'sebastian/diff' => [
			'version' => '2.0.1',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Kore Nordmann',
					'email' => 'mail@kore-nordmann.de',
				],
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
			],
			'description' => 'Diff implementation',
		],
		'sebastian/comparator' => [
			'version' => '2.1.1',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Jeff Welch',
					'email' => 'whatthejeff@gmail.com',
				],
				[
					'name' => 'Volker Dusch',
					'email' => 'github@wallbash.com',
				],
				[
					'name' => 'Bernhard Schussek',
					'email' => 'bschussek@2bepublished.at',
				],
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
			],
			'description' => 'Provides the functionality to compare PHP ' .
				'values for equality',
		],
		'doctrine/instantiator' => [
			'version' => '1.1.0',
			'type' => 'library',
			'licenses' => [ 'MIT' ],
			'authors' => [
				[
					'name' => 'Marco Pivetta',
					'email' => 'ocramius@gmail.com',
					'homepage' => 'http://ocramius.github.com/',
				],
			],
			'description' => 'A small, lightweight utility to instantiate ' .
				'objects in PHP without invoking their constructors',
		],
		'phpunit/php-text-template' => [
			'version' => '1.2.1',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
					'role' => 'lead',
				],
			],
			'description' => 'Simple template engine.',
		],
		'phpunit/php-timer' => [
			'version' => '1.0.9',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sb@sebastian-bergmann.de',
					'role' => 'lead',
				],
			],
			'description' => 'Utility class for timing',
		],
		'phpunit/php-file-iterator' => [
			'version' => '1.4.5',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sb@sebastian-bergmann.de',
					'role' => 'lead',
				],
			],
			'description' => 'FilterIterator implementation that filters ' .
				'files based on a list of suffixes.',
		],
		'theseer/tokenizer' => [
			'version' => '1.1.0',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Arne Blankerts',
					'email' => 'arne@blankerts.de',
					'role' => 'Developer',
				],
			],
			'description' => 'A small library for converting tokenized PHP ' .
				'source code into XML and potentially other formats',
		],
		'sebastian/code-unit-reverse-lookup' => [
			'version' => '1.0.1',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
			],
			'description' => 'Looks up which function or method a line of ' .
				'code belongs to',
		],
		'phpunit/php-token-stream' => [
			'version' => '2.0.2',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
				],
			],
			'description' => 'Wrapper around PHP\'s tokenizer extension.',
		],
		'phpunit/php-code-coverage' => [
			'version' => '5.3.0',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
					'role' => 'lead',
				],
			],
			'description' => 'Library that provides collection, processing, ' .
				'and rendering functionality for PHP code coverage information.',
		],
		'webmozart/assert' => [
			'version' => '1.2.0',
			'type' => 'library',
			'licenses' => [ 'MIT' ],
			'authors' => [
				[
					'name' => 'Bernhard Schussek',
					'email' => 'bschussek@gmail.com',
				],
			],
			'description' => 'Assertions to validate method input/output with ' .
				'nice error messages.',
		],
		'phpdocumentor/reflection-common' => [
			'version' => '1.0.1',
			'type' => 'library',
			'licenses' => [ 'MIT' ],
			'authors' => [
				[
					'name' => 'Jaap van Otterdijk',
					'email' => 'opensource@ijaap.nl',
				],
			],
			'description' => 'Common reflection classes used by phpdocumentor to ' .
				'reflect the code structure',
		],
		'phpdocumentor/type-resolver' => [
			'version' => '0.4.0',
			'type' => 'library',
			'licenses' => [ 'MIT' ],
			'authors' => [
				[
					'name' => 'Mike van Riel',
					'email' => 'me@mikevanriel.com',
				],
			],
			'description' => '',
		],
		'phpdocumentor/reflection-docblock' => [
			'version' => '4.2.0',
			'type' => 'library',
			'licenses' => [ 'MIT' ],
			'authors' => [
				[
					'name' => 'Mike van Riel',
					'email' => 'me@mikevanriel.com',
				],
			],
			'description' => 'With this component, a library can provide support for ' .
				'annotations via DocBlocks or otherwise retrieve information that ' .
				'is embedded in a DocBlock.',
		],
		'phpspec/prophecy' => [
			'version' => '1.7.3',
			'type' => 'library',
			'licenses' => [ 'MIT' ],
			'authors' => [
				[
					'name' => 'Konstantin Kudryashov',
					'email' => 'ever.zet@gmail.com',
					'homepage' => 'http://everzet.com',
				],
				[
					'name' => 'Marcello Duarte',
					'email' => 'marcello.duarte@gmail.com',
				],
			],
			'description' => 'Highly opinionated mocking framework for PHP 5.3+',
		],
		'phar-io/version' => [
			'version' => '1.0.1',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Arne Blankerts',
					'email' => 'arne@blankerts.de',
					'role' => 'Developer',
				],
				[
					'name' => 'Sebastian Heuer',
					'email' => 'sebastian@phpeople.de',
					'role' => 'Developer',
				],
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
					'role' => 'Developer',
				],
			],
			'description' => 'Library for handling version information and constraints',
		],
		'phar-io/manifest' => [
			'version' => '1.0.1',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Arne Blankerts',
					'email' => 'arne@blankerts.de',
					'role' => 'Developer',
				],
				[
					'name' => 'Sebastian Heuer',
					'email' => 'sebastian@phpeople.de',
					'role' => 'Developer',
				],
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
					'role' => 'Developer',
				],
			],
			'description' => 'Component for reading phar.io manifest ' .
				'information from a PHP Archive (PHAR)',
		],
		'myclabs/deep-copy' => [
			'version' => '1.7.0',
			'type' => 'library',
			'licenses' => [ 'MIT' ],
			'authors' => [
			],
			'description' => 'Create deep copies (clones) of your objects',
		],
		'phpunit/phpunit' => [
			'version' => '6.5.5',
			'type' => 'library',
			'licenses' => [ 'BSD-3-Clause' ],
			'authors' => [
				[
					'name' => 'Sebastian Bergmann',
					'email' => 'sebastian@phpunit.de',
					'role' => 'lead',
				],
			],
			'description' => 'The PHP Unit Testing framework.',
		],
		], $installed->getInstalledDependencies() );
	}

	public function provideInstalled() : array {
		$root = __DIR__ . '/../../../../data/composer/';

		return [
			'Composer v1' => [ $root . '/installed.json' ],
			'Composer v2' => [ $root . '/installed-v2.json' ]
		];
	}
}
