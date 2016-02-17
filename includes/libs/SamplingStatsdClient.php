<?php
/**
 * Copyright 2015
 *
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

use Liuggio\StatsdClient\StatsdClient;
use Liuggio\StatsdClient\Entity\StatsdData;
use Liuggio\StatsdClient\Entity\StatsdDataInterface;

/**
 * A statsd client that applies the sampling rate to the data items before sending them.
 *
 * @since 1.26
 */
class SamplingStatsdClient extends StatsdClient {
	/**
	 * Sets sampling rate for all items in $data.
	 * The sample rate specified in a StatsdData entity overrides the sample rate specified here.
	 *
	 * {@inheritDoc}
	 */
	public function appendSampleRate( $data, $sampleRate = 1 ) {
		if ( $sampleRate < 1 ) {
			array_walk( $data, function( $item ) use ( $sampleRate ) {
				/** @var $item StatsdData */
				if ( $item->getSampleRate() === 1 ) {
					$item->setSampleRate( $sampleRate );
				}
			} );
		}

		return $data;
	}

	/*
	 * Send the metrics over UDP
	 * Sample the metrics according to their sample rate and send the remaining ones.
	 *
	 * @param StatsdDataInterface|StatsdDataInterface[] $data message(s) to sent
	 *        strings are not allowed here as sampleData requires a StatsdDataInterface
	 * @param int $sampleRate
	 *
	 * @return integer the data sent in bytes
	 */
	public function send( $data, $sampleRate = 1 ) {
		if ( !is_array( $data ) ) {
			$data = [ $data ];
		}
		if ( !$data ) {
			return;
		}
		foreach ( $data as $item ) {
			if ( !( $item instanceof StatsdDataInterface ) ) {
				throw new InvalidArgumentException(
					'SamplingStatsdClient does not accept stringified messages' );
			}
		}

		// add sampling
		if ( $sampleRate < 1 ) {
			$data = $this->appendSampleRate( $data, $sampleRate );
		}
		$data = $this->sampleData( $data );

		$data = array_map( 'strval', $data );

		// reduce number of packets
		if ( $this->getReducePacket() ) {
			$data = $this->reduceCount( $data );
		}

		// failures in any of this should be silently ignored if ..
		$written = 0;
		try {
			$fp = $this->getSender()->open();
			if ( !$fp ) {
				return;
			}
			foreach ( $data as $message ) {
				$written += $this->getSender()->write( $fp, $message );
			}
			$this->getSender()->close( $fp );
		} catch ( Exception $e ) {
			$this->throwException( $e );
		}

		return $written;
	}

	/**
	 * Throw away some of the data according to the sample rate.
	 * @param StatsdDataInterface[] $data
	 * @return StatsdDataInterface[]
	 * @throws LogicException
	 */
	protected function sampleData( $data ) {
		$newData = [];
		$mt_rand_max = mt_getrandmax();
		foreach ( $data as $item ) {
			$samplingRate = $item->getSampleRate();
			if ( $samplingRate <= 0.0 || $samplingRate > 1.0 ) {
				throw new LogicException( 'Sampling rate shall be within ]0, 1]' );
			}
			if (
				$samplingRate === 1 ||
				( mt_rand() / $mt_rand_max <= $samplingRate )
			) {
				$newData[] = $item;
			}
		}
		return $newData;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function throwException( Exception $exception ) {
		if ( !$this->getFailSilently() ) {
			throw $exception;
		}
	}
}
