<?php

namespace Miraheze\ManageWiki\Jobs;

use Job;
use MediaWiki\Shell\Shell;
use Title;

class MWScriptJob extends Job {
	/**
	 * @param Title $title
	 * @param string[] $params
	 */
	public function __construct( Title $title, $params ) {
		parent::__construct( 'MWScriptJob', $params );
	}

	/**
	 * @return bool
	 */
	public function run() {
		$scriptParams = [
			'--wiki',
			$this->params['dbname']
		];

		foreach ( (array)$this->params['options'] as $name => $val ) {
			$scriptParams[] = "--{$name}";

			if ( !is_bool( $val ) ) {
				$scriptParams[] = $val;
			}
		}

		$result = Shell::makeScriptCommand(
			$this->params['script'],
			$scriptParams
		)->limits( [ 'memory' => 0, 'filesize' => 0 ] )->execute()->getExitCode();

		// An execute code higher then 0 indicates failure.
		if ( $result ) {
			wfDebugLog( 'ManageWiki', "MWScriptJob failure. Status {$result} running {$this->params['script']}" );
		}

		return true;
	}
}
