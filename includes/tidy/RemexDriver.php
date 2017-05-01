<?php

namespace MediaWiki\Tidy;

use RemexHtml\Serializer\Serializer;
use RemexHtml\Tokenizer\Tokenizer;
use RemexHtml\TreeBuilder\Dispatcher;
use RemexHtml\TreeBuilder\TreeBuilder;
use RemexHtml\TreeBuilder\TreeMutationTracer;

class RemexDriver extends TidyDriverBase {
	private $trace;
	private $pwrap;

	public function __construct( array $config ) {
		$config += [
			'treeMutationTrace' => false,
			'pwrap' => true
		];
		$this->trace = $config['treeMutationTrace'];
		$this->pwrap = $config['pwrap'];
		parent::__construct( $config );
	}

	public function tidy( $text ) {
		$formatter = new RemexCompatFormatter;
		$serializer = new Serializer( $formatter );
		if ( $this->pwrap ) {
			$munger = new RemexCompatMunger( $serializer );
		} else {
			$munger = $serializer;
		}
		if ( $this->trace ) {
			$tracer = new TreeMutationTracer( $munger, function ( $msg ) {
				wfDebug( "RemexHtml: $msg" );
			} );
		} else {
			$tracer = $munger;
		}
		$treeBuilder = new TreeBuilder( $tracer, [
			'ignoreErrors' => true,
			'ignoreNulls' => true,
		] );
		$dispatcher = new Dispatcher( $treeBuilder );
		$tokenizer = new Tokenizer( $dispatcher, $text, [
			'ignoreErrors' => true,
			'ignoreCharRefs' => true,
			'ignoreNulls' => true,
			'skipPreprocess' => true,
		] );
		$tokenizer->execute( [
			'fragmentNamespace' => \RemexHtml\HTMLData::NS_HTML,
			'fragmentName' => 'body'
		] );
		return $serializer->getResult();
	}
}
