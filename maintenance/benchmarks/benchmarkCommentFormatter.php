<?php

require_once __DIR__ . '/../includes/Benchmarker.php';

class BenchmarkCommentFormatter extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark Linker::formatComment()' );
		$this->addOption( 'file', 'A JSON API result from list=recentchanges',
			false, true );
	}

	public function execute() {
		$file = $this->getOption( 'file',
			__DIR__ . '/data/CommentFormatter/rc100-2021-07-29.json' );
		$json = file_get_contents( $file );
		if ( !$json ) {
			$this->fatalError( "Unable to read input file \"$file\"" );
		}
		$result = json_decode( $json, true );
		if ( !isset( $result['query']['recentchanges'] ) ) {
			$this->fatalError( "Invalid JSON data" );
		}
		$entries = $result['query']['recentchanges'];
		$inputs = [];
		foreach ( $entries as $entry ) {
			$inputs[] = [
				'comment' => $entry['comment'],
				'title' => Title::newFromText( $entry['title'] )
			];
		}
		$this->bench( [
			'Linker::formatComment' => [
				'function' => static function () use ( $inputs ) {
					Title::clearCaches();
					foreach ( $inputs as $input ) {
						Linker::formatComment(
							$input['comment'],
							$input['title']
						);
					}
				},
			],
		] );
	}
}

$maintClass = BenchmarkCommentFormatter::class;
require_once RUN_MAINTENANCE_IF_MAIN;
