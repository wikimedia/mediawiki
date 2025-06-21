<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */
namespace Wikimedia\Rdbms\Database;

use Wikimedia\Rdbms\DBLanguageError;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * @ingroup Database
 * @internal
 * @since 1.39
 */
class DatabaseFlags implements IDatabaseFlags {
	/** @var int Current bit field of class DBO_* constants */
	protected $flags;
	/** @var int[] Prior flags member variable values */
	private $priorFlags = [];

	/** @var string[] List of DBO_* flags that can be changed after connection */
	protected const MUTABLE_FLAGS = [
		'DBO_DEBUG',
		'DBO_NOBUFFER',
		'DBO_TRX',
		'DBO_DDLMODE',
	];
	/** @var int Bit field of all DBO_* flags that can be changed after connection */
	protected const DBO_MUTABLE = (
		self::DBO_DEBUG | self::DBO_NOBUFFER | self::DBO_TRX | self::DBO_DDLMODE
	);

	public function __construct( int $flags ) {
		$this->flags = $flags;
	}

	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		if ( $flag & ~static::DBO_MUTABLE ) {
			throw new DBLanguageError(
				"Got $flag (allowed: " . implode( ', ', static::MUTABLE_FLAGS ) . ')'
			);
		}

		if ( $remember === self::REMEMBER_PRIOR ) {
			$this->priorFlags[] = $this->flags;
		}

		$this->flags |= $flag;
	}

	public function clearFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		if ( $flag & ~static::DBO_MUTABLE ) {
			throw new DBLanguageError(
				"Got $flag (allowed: " . implode( ', ', static::MUTABLE_FLAGS ) . ')'
			);
		}

		if ( $remember === self::REMEMBER_PRIOR ) {
			$this->priorFlags[] = $this->flags;
		}

		$this->flags &= ~$flag;
	}

	public function restoreFlags( $state = self::RESTORE_PRIOR ) {
		if ( !$this->priorFlags ) {
			return;
		}

		if ( $state === self::RESTORE_INITIAL ) {
			$this->flags = reset( $this->priorFlags );
			$this->priorFlags = [];
		} else {
			$this->flags = array_pop( $this->priorFlags );
		}
	}

	public function getFlag( $flag ) {
		return ( ( $this->flags & $flag ) === $flag );
	}

	/**
	 * @param int $flags A bit field of flags
	 * @param int $bit Bit flag constant
	 * @return bool Whether the bit field has the specified bit flag set
	 */
	public static function contains( int $flags, int $bit ) {
		return ( ( $flags & $bit ) === $bit );
	}

	/**
	 * @param int $queryFlags A bit field of ISQLPlatform::QUERY_* constants
	 * @return bool Whether the implicit transaction flag is set and applies to the query flags
	 */
	public function hasApplicableImplicitTrxFlag( int $queryFlags ) {
		return $this->hasImplicitTrxFlag() && !(
			self::contains( $queryFlags, ISQLPlatform::QUERY_CHANGE_TRX ) ||
			self::contains( $queryFlags, ISQLPlatform::QUERY_CHANGE_SCHEMA ) ||
			self::contains( $queryFlags, ISQLPlatform::QUERY_CHANGE_LOCKS ) ||
			self::contains( $queryFlags, ISQLPlatform::QUERY_IGNORE_DBO_TRX )
		);
	}

	/**
	 * @return bool Whether the implicit transaction flag is set
	 */
	public function hasImplicitTrxFlag() {
		return $this->getFlag( self::DBO_TRX );
	}
}
