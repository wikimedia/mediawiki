<?php

namespace MediaWiki\Tidy;

use RemexHtml\Serializer\Serializer;
use RemexHtml\Serializer\SerializerWithTracer;
use RemexHtml\Tokenizer\Tokenizer;
use RemexHtml\TreeBuilder\Dispatcher;
use RemexHtml\TreeBuilder\TreeBuilder;
use RemexHtml\TreeBuilder\TreeMutationTracer;

class RemexDriver extends TidyDriverBase {
	private $treeMutationTrace;
	private $serializerTrace;
	private $mungerTrace;
	private $pwrap;

	public function __construct( array $config ) {
		$config += [
			'treeMutationTrace' => false,
			'serializerTrace' => false,
			'mungerTrace' => false,
			'pwrap' => true
		];
		$this->treeMutationTrace = $config['treeMutationTrace'];
		$this->serializerTrace = $config['serializerTrace'];
		$this->mungerTrace = $config['mungerTrace'];
		$this->pwrap = $config['pwrap'];
		parent::__construct( $config );
	}

	public function tidy( $text ) {
		$traceCallback = function ( $msg ) {
			wfDebug( "RemexHtml: $msg" );
		};

		$formatter = new RemexCompatFormatter;
		if ( $this->serializerTrace ) {
			$serializer = new SerializerWithTracer( $formatter, null, $traceCallback );
		} else {
			$serializer = new Serializer( $formatter );
		}
		if ( $this->pwrap ) {
			$munger = new RemexCompatMunger( $serializer, $this->mungerTrace );
		} else {
			$munger = $serializer;
		}
		if ( $this->treeMutationTrace ) {
			$tracer = new TreeMutationTracer( $munger, $traceCallback );
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
