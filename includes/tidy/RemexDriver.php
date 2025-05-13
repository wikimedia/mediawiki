<?php

namespace MediaWiki\Tidy;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use Wikimedia\RemexHtml\HTMLData;
use Wikimedia\RemexHtml\Serializer\Serializer;
use Wikimedia\RemexHtml\Serializer\SerializerWithTracer;
use Wikimedia\RemexHtml\Tokenizer\Tokenizer;
use Wikimedia\RemexHtml\TreeBuilder\Dispatcher;
use Wikimedia\RemexHtml\TreeBuilder\TreeMutationTracer;

class RemexDriver extends TidyDriverBase {
	/** @var bool */
	private $treeMutationTrace;
	/** @var bool */
	private $serializerTrace;
	/** @var bool */
	private $mungerTrace;
	/** @var bool */
	private $pwrap;

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::TidyConfig,
	];

	public function __construct( ServiceOptions $options ) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$config = $options->get( MainConfigNames::TidyConfig );
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

	/** @inheritDoc */
	public function tidy( $text, ?callable $textProcessor = null ) {
		$traceCallback = static function ( $msg ) {
			wfDebug( "RemexHtml: $msg" );
		};
		$formatter = new RemexCompatFormatter( [ 'textProcessor' => $textProcessor ] );
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
		$treeBuilder = new RemexCompatBuilder( $tracer, [
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
			'fragmentNamespace' => HTMLData::NS_HTML,
			'fragmentName' => 'body'
		] );
		return $serializer->getResult();
	}
}
