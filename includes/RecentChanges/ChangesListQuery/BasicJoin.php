<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * A simple join with a fixed join condition
 *
 * @since 1.45
 */
class BasicJoin implements ChangesListJoinModule {
	/** @var ChangesListJoinBuilder[] */
	protected $instances = [];

	private string $tableName;
	private string $defaultAlias;
	private array $conds;
	private array $dependencies;

	/**
	 * @param string $tableName The table name, and the module name.
	 * @param string|null $defaultAlias The alias to use if none was specified,
	 *   or an empty string or null for no alias.
	 * @param string|(IExpression|string)[] $conds The conditions for the ON clause
	 * @param-taint $conds exec_sql_numkey
	 * @param string|string[] $dependencies Table names which must also be
	 *   included in the query if this table is included. Each table must also
	 *   be the name of a registered join module.
	 */
	public function __construct(
		string $tableName,
		?string $defaultAlias,
		$conds,
		$dependencies = [],
	) {
		$this->tableName = $tableName;
		$this->defaultAlias = $defaultAlias ?? '';
		$this->conds = is_array( $conds ) ? $conds : [ $conds ];
		$this->dependencies = is_array( $dependencies ) ? $dependencies : [ $dependencies ];
	}

	/** @inheritDoc */
	public function forFields( JoinDependencyProvider $provider ): ChangesListJoinBuilder {
		$join = $this->activate()->forFields();
		foreach ( $this->dependencies as $tableName ) {
			$provider->joinForFields( $tableName );
		}
		return $join;
	}

	/** @inheritDoc */
	public function forConds( JoinDependencyProvider $provider ): ChangesListJoinBuilder {
		$join = $this->activate()->forConds();
		foreach ( $this->dependencies as $tableName ) {
			$provider->joinForConds( $tableName );
		}
		return $join;
	}

	/** @inheritDoc */
	public function alias( JoinDependencyProvider $provider, string $alias
	): ChangesListJoinBuilder {
		return $this->activate( $alias );
	}

	/** @inheritDoc */
	public function prepare( SelectQueryBuilder $sqb ): void {
		foreach ( $this->instances as $instance ) {
			$instance->prepare( $sqb );
		}
	}

	/**
	 * Activate an instance of the join, with undefined properties
	 *
	 * @param string|null $alias
	 * @return ChangesListJoinBuilder
	 */
	private function activate( $alias = null ) {
		$alias ??= $this->defaultAlias;
		if ( !isset( $this->instances[$alias] ) ) {
			$this->instances[$alias] = new ChangesListJoinBuilder(
				$this->tableName,
				$alias,
				[ ...$this->conds, ...$this->getExtraConds( $alias ) ]
			);
		}
		return $this->instances[$alias];
	}

	/**
	 * Subclasses may override this to add to the ON clause
	 *
	 * @param ?string $alias
	 * @return array
	 */
	protected function getExtraConds( ?string $alias ) {
		return [];
	}
}
