LinkCache {#linkcache}
========

The **LinkCache** maintains a list of page titles and information about their
existence and metadata (such as `page_id`, `page_latest`, and `page_is_redirect`).

This exists primarily to reduce heavy database load from the Parser, because articles tend to contain many outgoing page links and template transclusions, which would otherwise require a database query to resolve each one. These database queries are fast, but their cumulative load would be prohibitively expensive.

## Overview

* Consumers that represent wiki pages or page links such as `Title`, `PageStore`,
  and `Parser::replaceLinkHolders()`.
  These automatically read from the LinkCache as needed.

* `LinkBatchFactory` service and its LinkBatch objects,
  which you call to warm up the LinkCache service before you interact
  with the Title class and other consumers.

* `LinkCache` service, which stores and retrieves page metadata,
  which you use if developing your own page-representing classes.

## LinkBatch

Whenever you find yourself calling methods on multiple Title objects, or performing multiple PageStore lookups, then you should first call `LinkBatchFactory->newLinkBatch( $titles )->execute();`, so that the data is preloaded in a single batch query.

If you already have your titles in an array:

```php
$titles = [];
foreach ( [ 'Main Page', 'Project:Help', /* ... */ ] as $page ) {
	$titles[] = Title::newFromText( $page );
}

$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
$linkBatchFactory->newLinkBatch( $titles )->setCaller( __METHOD__ )->execute(); // 1 batch query

foreach ( $titles as $title ) {
	var_dump( $title->exists() ); // no database queries
}
```

If you discover titles piecemeal, you don't need to create your own array. Instead, you can collect them gradually in the `LinkBatch` object and execute once ready.

```php
$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
$linkBatch = $linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );

$linkBatch->addObj( $titleA );
// ...
$linkBatch->addObj( $titleB );
// ...
$linkBatch->addObj( $titleC );
// ...

$linkBatch->execute(); // 1 batch query

var_dump( $titleA->exists() ); // no database queries
```

When writing new code, **always verify** that there are no separate database queries left for your iterated titles. This can happen if you call a service class that accidentally bypasses or clears the LinkCache, or if you call a service that doesn't yet support LinkCache internally.

To verify this, enable debug logs and then interact with your feature in the browser (e.g. `$wgDebugToolbar` or `$wgDebugFile`). Then, review the "Queries" tab or "rdbms" channel, and confirm that there are no queries to the `page` table for your titles, except a single batch query attributed to `LinkBatch::doQuery`.

## Architecture

The LinkCache consists of three key parts:

* Batch interface
* In-process cache
* Persistent cache

The **Batch interface**, via LinkBatch, ensures quick response times for end-users by performing all queries in a single roundtrip to the database. An article can easily contain hundreds or thousands of distinct outgoing links and template calls, such that even a fast ~1ms database query would be too much to repeat that many times during one HTTP response.

When two different code paths look up the same title more than once during a request, we only load it once. This is thanks to an **in-process cache**, which works even if you don't use the batch interface. (The Title class reads from, but also writes to, this in-process cache, for any titles it encounters.). Pages that are closely related to the current page or current user (e.g. article talk page, user talk page) are often linked to from multiple places across the skin and page content.

There is also a **persistent cache** (via WANObjectCache, usually Memcached). However, this is automatically skipped for most titles, because applying Memcached here would likely make performance worse instead of better. On a large wiki like English Wikipedia, there are millions of articles. Most of them will not be popular enough to reside in Memcached. Thus there is only a small chance, when parsing a large article, that all or even most of its links will be found in Memcached. This means we still have to load some titles from the database, and we may as well load all of them, because with LinkBatch there is not much difference in cost to load a few more, given that the page table is well-indexed on database servers, which in turn has its own memory buffers for popular rows.

Instead, the persistent cache is only for use cases where we meet these criteria:
1. look up a single title, or a small set of titles, or titles you can't know in advance (for example, if resolving one title will then lead to other titles).
2. and, the titles are fixed, or have a highly popular subset,
3. and, a cache hit would eliminate the entire database query from your code (i.e. not merely shrink a batch).

The above holds for three important use cases, which therefore benefit from the persistent cache:

* Lookups by the Parser for single templates ([change 308172](https://gerrit.wikimedia.org/r/308172), [change 496388](https://gerrit.wikimedia.org/r/496388)).

  Each template may recursively lead to other templates that are not known in advance, thus not possible to batch. There is a small set of very popular templates on most wikis. This means caching these in Memcached significantly reduces database load. This is why the "Template" namespace, and "Module" namespace (Scribunto extension) qualify for the persistent cache.

* Lookups by ResourceLoader for interface messages and gadget pages ([change 521976](https://gerrit.wikimedia.org/r/c/mediawiki/core/+/521976)).

  The ResourceLoader startup module has to access the message blobs of all registered modules when computing the version hash. This is why the "MediaWiki" namespace, which holds interface messages, qualifies for the persistent cache.

## History

The LinkCache has been part of MediaWiki since the first release in 2003, and was originally used to track links used on a given page, for the purposes of updating the link tables. This application was deprecated by [r12301](https://www.mediawiki.org/wiki/Special:Code/MediaWiki/12301) in MediaWiki 1.6, in favor of ParserOutput holding this instead.
