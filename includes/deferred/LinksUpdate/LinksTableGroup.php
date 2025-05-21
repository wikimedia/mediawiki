<?php

namespace MediaWiki\Deferred\LinksUpdate;

use InvalidArgumentException;
use MediaWiki\Collation\CollationFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\RevisionRecord;
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
	 *   - needCollation: If true, the following additional args will be added:
	 *     Collation, collation name and table name.
	 */
	private const CORE_LIST = [
		'categorylinks' => [
			'class' => CategoryLinksTable::class,
			'services' => [
				'LanguageConverterFactory',
				'NamespaceInfo',
				'WikiPageFactory',
				'DBLoadBalancer',
				'MainWANObjectCache',
				'MainConfig'
			],
			'needCollation' => true,
		],
		'externallinks' => [
			'class' => ExternalLinksTable::class,
		],
		'existencelinks' => [
			'class' => ExistenceLinksTable::class,
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
			'class' => PageLinksTable::class,
			'services' => [
				'MainConfig'
			],
		],
		'page_props' => [
			'class' => PagePropsTable::class,
			'services' => [
				'JobQueueGroup'
			],
			'serviceOptions' => PagePropsTable::CONSTRUCTOR_OPTIONS
		],
		'templatelinks' => [
			'class' => TemplateLinksTable::class,
		]
	];

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var LBFactory */
	private $lbFactory;

	/** @var CollationFactory */
	private $collationFactory;

	/** @var PageIdentity */
	private $page;

	/** @var PageReference|null */
	private $movedPage;

	/** @var ParserOutput|null */
	private $parserOutput;

	/** @var LinkTargetLookup */
	private $linkTargetLookup;

	/** @var int */
	private $batchSize;

	/** @var mixed */
	private $ticket;

	/** @var RevisionRecord|null */
	private $revision;

	/** @var LinksTable[] */
	private $tables = [];

	/** @var array */
	private $tempCollations;

	/**
	 * @param ObjectFactory $objectFactory
	 * @param LBFactory $lbFactory
	 * @param CollationFactory $collationFactory
	 * @param PageIdentity $page
	 * @param LinkTargetLookup $linkTargetLookup
	 * @param int $batchSize
	 * @param array $tempCollations
	 */
	public function __construct(
		ObjectFactory $objectFactory,
		LBFactory $lbFactory,
		CollationFactory $collationFactory,
		PageIdentity $page,
		LinkTargetLookup $linkTargetLookup,
		$batchSize,
		array $tempCollations
	) {
		$this->objectFactory = $objectFactory;
		$this->lbFactory = $lbFactory;
		$this->collationFactory = $collationFactory;
		$this->page = $page;
		$this->batchSize = $batchSize;
		$this->linkTargetLookup = $linkTargetLookup;
		$this->tempCollations = [];
		foreach ( $tempCollations as $info ) {
			$this->tempCollations[$info['table']] = $info;
		}
	}

	/**
	 * Set the ParserOutput object to be used in new and existing objects.
	 */
	public function setParserOutput( ParserOutput $parserOutput ) {
		$this->parserOutput = $parserOutput;
		foreach ( $this->tables as $table ) {
			$table->setParserOutput( $parserOutput );
		}
	}

	/**
	 * Set the original title in the case of a page move.
	 */
	public function setMoveDetails( PageReference $oldPage ) {
		$this->movedPage = $oldPage;
		foreach ( $this->tables as $table ) {
			$table->setMoveDetails( $oldPage );
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
		if ( isset( self::CORE_LIST[$tableName] ) ) {
			$spec = self::CORE_LIST[$tableName];
			return $this->addCollationArgs( $spec, $tableName, false );
		}
		if ( isset( $this->tempCollations[$tableName] ) ) {
			$info = $this->tempCollations[$tableName];
			$spec = self::CORE_LIST['categorylinks'];
			return $this->addCollationArgs( $spec, $tableName, true, $info );
		}
		throw new InvalidArgumentException(
			__CLASS__ . ": unknown table name \"$tableName\"" );
	}

	/**
	 * Add extra args to the spec of a table that needs collation information
	 *
	 * @param array $spec
	 * @param string $tableName
	 * @param bool $isTempTable
	 * @param array $info Temporary collation info
	 * @return array ObjectFactory spec
	 */
	private function addCollationArgs( $spec, $tableName, $isTempTable, $info = [] ) {
		if ( isset( $spec['needCollation'] ) ) {
			if ( isset( $info['collation'] ) ) {
				$collation = $this->collationFactory->makeCollation( $info['collation'] );
				$collationName = $info['fakeCollation'] ?? $info['collation'];
			} else {
				$collation = $this->collationFactory->getCategoryCollation();
				$collationName = $this->collationFactory->getDefaultCollationName();
			}
			$spec['args'] = [
				$collation,
				$info['fakeCollation'] ?? $collationName,
				$tableName,
				$isTempTable
			];
			unset( $spec['needCollation'] );
		}
		return $spec;
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
				$this->linkTargetLookup,
				$this->page,
				$this->batchSize
			);
			if ( $this->parserOutput ) {
				$table->setParserOutput( $this->parserOutput );
			}
			if ( $this->movedPage ) {
				$table->setMoveDetails( $this->movedPage );
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
		foreach ( $this->tempCollations as $tableName => $collation ) {
			yield $this->get( $tableName );
		}
	}
}
