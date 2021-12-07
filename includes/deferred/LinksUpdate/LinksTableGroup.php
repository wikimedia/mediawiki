<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;
use ParserOutput;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Rdbms\LBFactory;

/**
 * @since 1.38
 */
class LinksTableGroup {
	/**
	 * ObjectFactory specifications for the subclasses. The following
	 * additional keys are defined:
	 *
	 *   - serviceOptions: An array of configuration variable names. If this is
	 *     set, the specified configuration will be sent to the subclass
	 *     constructor as a ServiceOptions object.
	 */
	private const CORE_LIST = [
		'categorylinks' => [
			'class' => CategoryLinksTable::class,
			'services' => [
				'LanguageConverterFactory',
				'CollationFactory',
				'NamespaceInfo',
				'WikiPageFactory'
			]
		],
		'externallinks' => [
			'class' => ExternalLinksTable::class
		],
		'imagelinks' => [
			'class' => ImageLinksTable::class
		],
		'iwlinks' => [
			'class' => InterwikiLinksTable::class
		],
		'langlinks' => [
			'class' => LangLinksTable::class
		],
		'pagelinks' => [
			'class' => PageLinksTable::class
		],
		'page_props' => [
			'class' => PagePropsTable::class,
			'services' => [
				'JobQueueGroup'
			],
			'serviceOptions' => PagePropsTable::CONSTRUCTOR_OPTIONS
		],
		'templatelinks' => [
			'class' => TemplateLinksTable::class
		]
	];

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var LBFactory */
	private $lbFactory;

	/** @var PageIdentity */
	private $page;

	/** @var ParserOutput|null */
	private $parserOutput;

	/** @var int */
	private $batchSize;

	/** @var callable|null */
	private $afterUpdateHook;

	/** @var mixed */
	private $ticket;

	/** @var RevisionRecord|null */
	private $revision;

	/** @var LinksTable[] */
	private $tables = [];

	/**
	 * @param ObjectFactory $objectFactory
	 * @param LBFactory $lbFactory
	 * @param PageIdentity $page
	 * @param int $batchSize
	 * @param callable|null $afterUpdateHook
	 */
	public function __construct(
		ObjectFactory $objectFactory,
		LBFactory $lbFactory,
		PageIdentity $page,
		$batchSize,
		$afterUpdateHook
	) {
		$this->objectFactory = $objectFactory;
		$this->lbFactory = $lbFactory;
		$this->page = $page;
		$this->batchSize = $batchSize;
		$this->afterUpdateHook = $afterUpdateHook;
	}

	/**
	 * Set the ParserOutput object to be used in new and existing objects.
	 *
	 * @param ParserOutput $parserOutput
	 */
	public function setParserOutput( ParserOutput $parserOutput ) {
		$this->parserOutput = $parserOutput;
		foreach ( $this->tables as $table ) {
			$table->setParserOutput( $parserOutput );
		}
	}

	/**
	 * Set the transaction ticket to be used in new and existing objects.
	 *
	 * @param mixed $ticket
	 */
	public function setTransactionTicket( $ticket ) {
		$this->ticket = $ticket;
		foreach ( $this->tables as $table ) {
			$table->setTransactionTicket( $ticket );
		}
	}

	/**
	 * Set the revision to be used in new and existing objects.
	 *
	 * @param RevisionRecord $revision
	 */
	public function setRevision( RevisionRecord $revision ) {
		$this->revision = $revision;
		foreach ( $this->tables as $table ) {
			$table->setRevision( $revision );
		}
	}

	/**
	 * Set the strict test mode
	 *
	 * @param bool $mode
	 */
	public function setStrictTestMode( $mode = true ) {
		foreach ( $this->getAll() as $table ) {
			$table->setStrictTestMode( $mode );
		}
	}

	/**
	 * Get the spec array for a given table.
	 *
	 * @param string $tableName
	 * @return array
	 */
	private function getSpec( $tableName ) {
		if ( !isset( self::CORE_LIST[$tableName] ) ) {
			throw new \InvalidArgumentException(
				__CLASS__ . ": unknown table name \"$tableName\"" );
		}
		return self::CORE_LIST[$tableName];
	}

	/**
	 * Get a LinksTable for a given table.
	 *
	 * @param string $tableName
	 * @return LinksTable
	 */
	public function get( $tableName ) {
		if ( !isset( $this->tables[$tableName] ) ) {
			$spec = $this->getSpec( $tableName );
			if ( isset( $spec['serviceOptions'] ) ) {
				$config = MediaWikiServices::getInstance()->getMainConfig();
				$extraArgs = [ new ServiceOptions( $spec['serviceOptions'], $config ) ];
				unset( $spec['serviceOptions'] );
			} else {
				$extraArgs = [];
			}
			/** @var LinksTable $table */
			$table = $this->objectFactory->createObject( $spec, [ 'extraArgs' => $extraArgs ] );
			$table->injectBaseDependencies(
				$this->lbFactory,
				$this->page,
				$this->batchSize,
				$this->afterUpdateHook
			);
			if ( $this->parserOutput ) {
				$table->setParserOutput( $this->parserOutput );
			}
			if ( $this->ticket ) {
				$table->setTransactionTicket( $this->ticket );
			}
			if ( $this->revision ) {
				$table->setRevision( $this->revision );
			}
			$this->tables[$tableName] = $table;
		}
		return $this->tables[$tableName];
	}

	/**
	 * Get LinksTable objects for all known links tables.
	 * @return iterable<LinksTable>
	 */
	public function getAll() {
		foreach ( self::CORE_LIST as $tableName => $spec ) {
			yield $this->get( $tableName );
		}
	}
}
