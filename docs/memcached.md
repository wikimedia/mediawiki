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

(incomplete, out of date)

Date Formatter:

	key: $wgDBname:dateformatter
	ex: wikidb:dateformatter
	stores: a single instance of the DateFormatter class
	cleared by: nothing
	expiry: one hour

Difference Engine:

	key: $wgDBname:diff:version:{MW_DIFF_VERSION}:oldid:$old:newid:$new
	ex: wikidb:diff:version:1.11a:oldid:1:newid:2
	stores: body of a difference
	cleared by: nothing
	expiry: one week

Interwiki:

	key: $wgDBname:interwiki:$prefix
	ex: wikidb:interwiki:w
	stores: object from the interwiki table of the database
	expiry: $wgInterwikiExpiry
	cleared by: nothing

Lag time of the databases:

	key: $wgDBname:lag_times
	ex: wikidb:lag_times
	stores: array mapping the database id to its lag time
	expiry: 5 secondes
	cleared by: nothing

Localisation:

	key: $wgDBname:localisation:$lang
	ex: wikidb:localisation:de
	stores: array of localisation settings
	set in: Language::loadLocalisation()
	expiry: none
	cleared by: Language::loadLocalisation()

Message Cache:

	See MessageCache.php.

Newtalk:

	key: $wgDBname:newtalk:ip:$ip
	ex: wikidb:newtalk:ip:123.45.67.89
	stores: integer, 0 or 1
	set in: User::loadFromDatabase()
	cleared by: User::saveSettings() # ?
	expiry: 30 minutes

Parser Cache:

	access: ParserCache
	backend: $wgParserCacheType
	key: $wgDBname:pcache:idhash:$pageid-$renderkey!$hash
		$pageid: id of the page
		$renderkey: 1 if action=render, 0 otherwise
		$hash: hash of user options applied to the page, see ParserOptions::optionsHash()
	ex: wikidb:pcache:idhash:1-0!1!0!!en!2
	stores: ParserOutput object
	modified by: WikiPage::doEditUpdates() or PoolWorkArticleView::doWork()
	expiry: $wgParserCacheExpireTime or less if it contains short lived functions

	key: $wgDBname:pcache:idoptions:$pageid
	stores: CacheTime object with an additional list of used options for the hash,
    serves as ParserCache pointer.
	modified by: ParserCache::save()
	expiry: The same as the ParserCache entry it points to.

Ping limiter:

	controlled by: $wgRateLimits
	key: $wgDBname:limiter:action:$action:ip:$ip,
		$wgDBname:limiter:action:$action:user:$id,
		mediawiki:limiter:action:$action:ip:$ip and
		mediawiki:limiter:action:$action:subnet:$sub
	ex: wikidb:limiter:action:edit:ip:123.45.67.89,
		wikidb:limiter:action:edit:user:1012
		mediawiki:limiter:action:edit:ip:123.45.67.89 and
		mediawiki:limiter:action:$action:subnet:123.45.67
	stores: number of action made by user/ip/subnet
	cleared by: nothing
	expiry: expiry set for the action and group in $wgRateLimits


Proxy Check: (deprecated)

	key: $wgDBname:proxy:ip:$ip
	ex: wikidb:proxy:ip:123.45.67.89
	stores: 1 if the ip is a proxy
	cleared by: nothing
	expiry: $wgProxyMemcExpiry

Revision text:

	key: $wgDBname:revisiontext:textid:$id
	ex: wikidb:revisiontext:textid:1012
	stores: text of a revision
	cleared by: nothing
	expiry: $wgRevisionCacheExpiry

Sessions:

	controlled by: $wgSessionsInObjectCache
	key: $wgBDname:session:$id
	ex: wikidb:session:38d7c5b8d3bfc51egf40c69bc40f8be3
	stores: $SESSION, useful when using a multi-sever wiki
	expiry: one hour
	cleared by: session_destroy()

Sidebar:

	access: WANObjectCache
	controlled by: $wgEnableSidebarCache
	key: $wgDBname:sidebar
	ex: wikidb:sidebar
	stores: the html output of the sidebar
	expiry: $wgSidebarCacheExpiry
	cleared by: MessageCache::replace()

Special:Allpages:

	key: $wgDBname:allpages:ns:$ns
	ex: wikidb:allpages:ns:0
	stores: array of pages in a namespace
	expiry: one hour
	cleared by: nothing

Special:Recentchanges (feed):

	backend: $wgMessageCacheType
	key: $wgDBname:rcfeed:$format:$limit:$hideminor:$target and
		rcfeed:$format:timestamp
	ex: wikidb:rcfeed:rss:50:: and rcfeed:rss:timestamp
	stores: xml output of feed
	expiry: one day
	clear by: maintenance/rebuildrecentchanges.php script, or
	calling Special:Recentchanges?action=purge&feed=rss,
	Special:Recentchanges?action=purge&feed=atom,
	but note need $wgGroupPermissions[...]['purge'] permission.
