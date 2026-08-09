Memcached {#memcached}
====================================

MediaWiki has optional support for memcached, a "high-performance,
distributed memory object caching system". For general information
on it, see: <http://www.danga.com/memcached/>

Memcached is likely more trouble than a small site will need, but
for a larger site with heavy load, like Wikipedia, it should help
lighten the load on the database servers by caching data and objects
in memory.

Installation
--------------------------------

Packages are available for Fedora, Debian, Ubuntu and probably other
Linux distributions. If there's no package available for your
distribution, you can compile it from source.

Compilation
--------------------------------

* PHP must be compiled with --enable-sockets
* libevent: <http://www.monkey.org/~provos/libevent/>
  (as of 2003-08-11, 0.7a is current)
* optionally, epoll-rt patch for Linux kernel:
  <http://www.xmailserver.org/linux-patches/nio-improve.html>
* memcached: <http://www.danga.com/memcached/download.bml>
  (as of this writing, 1.1.9 is current)

Memcached and libevent are under BSD-style licenses.

The server should run on Linux and other Unix-like systems... you
can run multiple servers on one machine or on multiple machines on
a network; storage can be distributed across multiple servers, and
multiple web servers can use the same cache cluster.

**W A R N I N G ! ! ! ! !**

Memcached has no security or authentication. Please ensure that your
server is appropriately firewalled, and that the port(s) used for
memcached servers are not publicly accessible. Otherwise, anyone on
the internet can put data into and read data from your cache.

An attacker familiar with MediaWiki internals could use this to steal
passwords and email addresses, or to make themselves a sysop and
install malicious javascript on the site. There may be other types
of vulnerability, no audit has been done -- so be safe and keep it
behind a firewall.

**W A R N I N G ! ! ! ! !**

Setup
--------------------------------

If you installed memcached using a distro, the daemon should be started
automatically using

	/etc/init.d/memcached

To start the daemon manually, use something like:

	memcached -d -l 127.0.0.1 -p 11211 -m 64

(to run in daemon mode, accessible only via loopback interface,
on port 11211, using up to 64 MiB of memory)

In your LocalSettings.php file, set:

```php
$wgMainCacheType = CACHE_MEMCACHED;
$wgMemCachedServers = [ "127.0.0.1:11211" ];
```

The wiki should then use memcached to cache various data. To use
multiple servers (physically separate boxes or multiple caches
on one machine on a large-memory x86 box), just add more items
to the array. To increase the weight of a server (say, because
it has twice the memory of the others and you want to spread
usage evenly), make its entry a subarray:

```php
$wgMemCachedServers = [
	"127.0.0.1:11211", # one gig on this box
	[ "192.168.0.1:11211", 2 ] # two gigs on the other box
];
```

PHP client for memcached
--------------------------------

MediaWiki uses a fork of Ryan T. Dean's pure-PHP memcached client.
It also supports the PECL PHP extension for memcached.

MediaWiki uses the ObjectCache class to retrieve instances of
BagOStuff by purpose, controlled by the following variables:
* $wgMainCacheType
* $wgParserCacheType
* $wgMessageCacheType

If you set one of these to CACHE_NONE, MediaWiki still creates a
BagOStuff object, but calls it to it are no-ops. If the cache daemon
can't be contacted, it should also disable itself fairly smoothly.

Keys used
--------------------------------

For an overview of notable uses of object cache in MediaWiki,
refer to <https://www.mediawiki.org/wiki/Object_cache>.

To see an example of which key groups receive the most calls on
on high-traffic installation, check Wikimedia Foundation's dashboard
at <https://grafana.wikimedia.org/d/2Zx07tGZz/wanobjectcache>.
