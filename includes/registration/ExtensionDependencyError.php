<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Registration;

use Exception;

/**
 * @newable
 * @since 1.31
 * @ingroup ExtensionRegistry
 * @author Kunal Mehta <legoktm@debian.org>
 */
class ExtensionDependencyError extends Exception {

	/**
	 * @var string[]
	 */
	public $missingExtensions = [];

	/**
	 * @var string[]
	 */
	public $missingSkins = [];

	/**
	 * @var string[]
	 */
	public $incompatibleExtensions = [];

	/**
	 * @var string[]
	 */
	public $incompatibleSkins = [];

	/**
	 * @var bool
	 */
	public $incompatibleCore = false;

	/**
	 * @var bool
	 */
	public $incompatiblePhp = false;

	/**
	 * @var string[]
	 */
	public $missingPhpExtensions = [];

	/**
	 * @var string[]
	 */
	public $missingAbilities = [];

	/**
	 * @param array[] $errors Each error has a 'msg' and 'type' key at minimum
	 */
	public function __construct( array $errors ) {
		$msg = '';
		foreach ( $errors as $info ) {
			$msg .= $info['msg'] . "\n";
			switch ( $info['type'] ) {
				case 'incompatible-core':
					$this->incompatibleCore = true;
					break;
				case 'incompatible-php':
					$this->incompatiblePhp = true;
					break;
				case 'missing-phpExtension':
					$this->missingPhpExtensions[] = $info['missing'];
					break;
				case 'missing-ability':
					$this->missingAbilities[] = $info['missing'];
					break;
				case 'missing-skins':
					$this->missingSkins[] = $info['missing'];
					break;
				case 'missing-extensions':
					$this->missingExtensions[] = $info['missing'];
					break;
				case 'incompatible-skins':
					$this->incompatibleSkins[] = $info['incompatible'];
					break;
				case 'incompatible-extensions':
					$this->incompatibleExtensions[] = $info['incompatible'];
					break;
				// default: continue
			}
		}

		parent::__construct( $msg );
	}

}

/** @deprecated class alias since 1.43 */
class_alias( ExtensionDependencyError::class, 'ExtensionDependencyError' );
