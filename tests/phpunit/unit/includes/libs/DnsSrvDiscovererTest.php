<?php

namespace Wikimedia\Tests;

use DnsSrvDiscoverer;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DnsSrvDiscoverer
 */
class DnsSrvDiscovererTest extends TestCase {

	use MediaWikiCoversValidator;

	public function testGetSrvName() {
		$dsd = new DnsSrvDiscoverer( 'etcd', 'tcp', 'an.example' );

		$this->assertSame( '_etcd._tcp.an.example', $dsd->getSrvName() );
	}

	public function testGetSrvNameWithoutDomain() {
		$dsd = new DnsSrvDiscoverer( 'etcd', 'tcp' );

		$this->assertSame( '_etcd._tcp', $dsd->getSrvName() );
	}

	public function testGetRecords() {
		$resolver = $this->mockResolver();

		$dsd = new DnsSrvDiscoverer( 'etcd', 'tcp', 'an.example', $resolver );

		$resolver
			->expects( $this->once() )
			->method( '__invoke' )
			->with( '_etcd._tcp.an.example' )
			->willReturn( [
				[ 'target' => 'foo', 'port' => '123', 'pri' => '1', 'weight' => '1' ],
				[ 'target' => 'qux', 'port' => '322', 'pri' => '2', 'weight' => '2' ],
				[ 'target' => 'bar', 'port' => '124', 'pri' => '1', 'weight' => '2' ],
				[ 'target' => 'baz', 'port' => '321', 'pri' => '2', 'weight' => '1' ],
			] );

		$this->assertSame(
			[
				[ 'target' => 'foo', 'port' => 123, 'pri' => 1, 'weight' => 1 ],
				[ 'target' => 'qux', 'port' => 322, 'pri' => 2, 'weight' => 2 ],
				[ 'target' => 'bar', 'port' => 124, 'pri' => 1, 'weight' => 2 ],
				[ 'target' => 'baz', 'port' => 321, 'pri' => 2, 'weight' => 1 ],
			],
			$dsd->getRecords()
		);
	}

	public function testGetServers() {
		$resolver = $this->mockResolver();

		$dsd = new DnsSrvDiscoverer( 'etcd', 'tcp', 'an.example', $resolver );

		$resolver
			->expects( $this->once() )
			->method( '__invoke' )
			->with( '_etcd._tcp.an.example' )
			->willReturn( [
				[ 'target' => 'foo', 'port' => '123', 'pri' => '1', 'weight' => '1' ],
				[ 'target' => 'qux', 'port' => '322', 'pri' => '2', 'weight' => '2' ],
				[ 'target' => 'bar', 'port' => '124', 'pri' => '1', 'weight' => '2' ],
				[ 'target' => 'baz', 'port' => '321', 'pri' => '2', 'weight' => '1' ],
			] );

		$servers = $dsd->getServers();
		$prio1 = array_slice( $servers, 0, 2 );
		$prio2 = array_slice( $servers, 2, 2 );

		$this->assertContains(
			[ 'foo', 123 ],
			$prio1
		);

		$this->assertContains(
			[ 'bar', 124 ],
			$prio1
		);

		$this->assertContains(
			[ 'baz', 321 ],
			$prio2
		);

		$this->assertContains(
			[ 'qux', 322 ],
			$prio2
		);
	}

	public function testGetServersNoDiscoveryResultsRfc2782() {
		$resolver = $this->mockResolver();

		$dsd = new DnsSrvDiscoverer( 'etcd', 'tcp', 'an.example', $resolver );

		$resolver
			->expects( $this->once() )
			->method( '__invoke' )
			->with( '_etcd._tcp.an.example' )
			->willReturn( [
				[ 'target' => '.', 'port' => '1', 'pri' => '1', 'weight' => '1' ],
			] );

		$this->assertSame( [], $dsd->getServers() );
	}

	private function mockResolver() {
		return $this
			->getMockBuilder( stdClass::class )
			->addMethods( [ '__invoke' ] )
			->getMock();
	}
}
