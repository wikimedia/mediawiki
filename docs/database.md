Database Access {#database}
=====

*Some information about database access in MediaWiki. By Tim Starling, January 2006.*

## Database Layout

For information about the MediaWiki database layout, such as a description of the tables and their contents, please see:

* [The manual](https://www.mediawiki.org/wiki/Manual:Database_layout)
* [Abstract schema of MediaWiki core](https://phabricator.wikimedia.org/diffusion/MW/browse/master/maintenance/tables.json)
* [MySQL schema (automatically generated)](https://phabricator.wikimedia.org/diffusion/MW/browse/master/maintenance/tables-generated.sql)


## API

To make a read query, something like this usually suffices:

```php
use MediaWiki\MediaWikiServices;
$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
...
$dbr = $lb->getConnectionRef( DB_REPLICA );
$res = $dbr->newSelectQueryBuilder()
  ->select( /* ... see docs... */ )
  // ...see docs for other methods...
  ->fetchResultSet();

foreach( $res as $row ) {
	...
}
```

For a write query, use something like:
```php
$dbw = $lb->getConnectionRef( DB_PRIMARY );
$dbw->insert( /* ...see docs... */ );
```
We use the convention `$dbr` for read and `$dbw` for write to help you keep track of whether the database object is a replica (read-only) or a primary (read/write). If you write to a replica, the world will explode. Or to be precise, a subsequent write query which succeeded on the primary may fail when propagated to the replica due to a unique key collision. Replication will then stop and it may take hours to repair the database and get it back online. Setting `read_only` in `my.cnf` on the replica will avoid this scenario, but given the dire consequences, we prefer to have as many checks as possible.

We provide a `query()` function for raw SQL, but the wrapper functions like `select()` and `insert()` are usually more convenient. They take care of things like table prefixes and escaping for you. If you really need to make your own SQL, please read the documentation for `tableName()` and `addQuotes()`. You will need both of them.


## Basic query optimisation

MediaWiki developers who need to write DB queries should have some understanding of databases and the performance issues associated with them. Patches containing unacceptably slow features will not be accepted. Unindexed queries are generally not welcome in MediaWiki, except in special pages derived from `QueryPage`. It's a common pitfall for new developers to submit code containing SQL queries which examine huge numbers of rows. Remember that `COUNT(*)` is **O(N)**, counting rows in a table is like counting beans in a bucket.

## Replication

The largest installation of MediaWiki, Wikimedia, uses a large set of replica MySQL servers replicating writes made to a primary MySQL server. It is important to understand the issues associated with this setup if you want to write code destined for Wikipedia.

It's often the case that the best algorithm to use for a given task depends on whether or not replication is in use. Due to our unabashed Wikipedia-centrism, we often just use the replication-friendly version, but if you like, you can use `LoadBalancer::getServerCount() > 1` to check to see if replication is in use.

## Lag

Lag primarily occurs when large write queries are sent to the primary. Writes on the primary are executed in parallel, but they are executed in serial when they are replicated to the replicas. The primary writes the query to the binlog when the transaction is committed. The replicas poll the binlog and start executing the query as soon as it appears. They can service reads while they are performing a write query, but will not read anything more from the binlog and thus will perform no more writes. This means that if the write query runs for a long time, the replicas will lag behind the primary for the time it takes for the write query to complete.

Lag can be exacerbated by high read load. MediaWiki's load balancer will stop sending reads to a replica when it is lagged by more than 30 seconds. If the load ratios are set incorrectly, or if there is too much load generally, this may lead to a replica permanently hovering around 30 seconds lag.

If all replicas are lagged by more than 30 seconds, MediaWiki will stop writing to the database. All edits and other write operations will be refused, with an error returned to the user. This gives the replicas a chance to catch up. Before we had this mechanism, the replicas would regularly lag by several minutes, making review of recent edits difficult.

In addition to this, MediaWiki attempts to ensure that the user sees events occurring on the wiki in chronological order. A few seconds of lag can be tolerated, as long as the user sees a consistent picture from subsequent requests. This is done by saving the primary binlog position in the session, and then at the start of each request, waiting for the replica to catch up to that position before doing any reads from it. If this wait times out, reads are allowed anyway, but the request is considered to be in "lagged replica mode". Lagged replica mode can be checked by calling `LoadBalancer::getLaggedReplicaMode()`. The only practical consequence at present is a warning displayed in the page footer.

## Lag avoidance

To avoid excessive lag, queries which write large numbers of rows should be split up, generally to write one row at a time. Multi-row `INSERT ... SELECT` queries are the worst offenders should be avoided altogether. Instead do the select first and then the insert.

## Working with lag

Despite our best efforts, it's not practical to guarantee a low-lag environment. Lag will usually be less than one second, but may occasionally be up to 30 seconds. For scalability, it's very important to keep load on the primary low, so simply sending all your queries to the primary is not the answer. So when you have a genuine need for up-to-date data, the following approach is advised:

1) Do a quick query to the primary for a sequence number or timestamp
2) Run the full query on the replica and check if it matches the data you got
from the primary
3) If it doesn't, run the full query on the primary

To avoid swamping the primary every time the replicas lag, use of this approach should be kept to a minimum. In most cases you should just read from the replica and let the user deal with the delay.

## Lock contention

Due to the high write rate on Wikipedia (and some other wikis), MediaWiki developers need to be very careful to structure their writes to avoid long-lasting locks. By default, MediaWiki opens a transaction at the first query, and commits it before the output is sent. Locks will be held from the time when the query is done until the commit. So you can reduce lock time by doing as much processing as possible before you do your write queries.

Often this approach is not good enough, and it becomes necessary to enclose small groups of queries in their own transaction. Use the following syntax:

```php
$dbw = wfGetDB( DB_PRIMARY );
$dbw->begin( __METHOD__ );
/* Do queries */
$dbw->commit( __METHOD__ );
```

Use of locking reads (e.g. the `FOR UPDATE` clause) is not advised. They are poorly implemented in InnoDB and will cause regular deadlock errors. It's also surprisingly easy to cripple the wiki with lock contention.

Instead of locking reads, combine your existence checks into your write queries, by using an appropriate condition in the `WHERE` clause of an `UPDATE`, or by using unique indexes in combination with `INSERT IGNORE`. Then use the affected row count to see if the query succeeded.

## Query groups

MediaWiki supports database query groups, a way to indicate a preferred group of database hosts to use for a given query. Query groups are only supported for connections to child (non-primary) databases, making them only viable for read operations. It should be noted that using query groups does not _guarantee_ a given group of hosts will be used, but rather that the query prefers such group. Making use of query groups can be beneficial in many cases.

One benefit is a reduction of cache misses. Directing reads for a category of queries (e.g. all logging queries) to a given host can result in more deterministic and faster performing queries.

Another benefit is that it allows high-traffic wikis to configure some of their database hosts to handle some types of queries more optimally than others. For example, optimizing with different table indices for faster performance.

Query groups are especially beneficial for queries expected to have a long execution time. Such queries can exhaust a database of its resources (e.g. cache space and I/O time), so targeting a specific group of hosts prevents more urgent queries from suffering a performance decrease.

Additionally, expensive queries can delay database maintenance operations which may increase latency for other queries.
For example, while a database read is executing, if other queries have performed updates to any tables those tables must retain all stale versions of its rows until the read is complete. Now, other potentially unrelated queries must now spend additional time scanning over obsolete rows that are waiting to be purged. Directing these long running queries to dedicated hosts helps prevent other queries in suffering a performance hit.

MediaWiki currently supports the following query groups:

* api
    * Only use for queries specific to api.php requests; the method ApiBase::getDB() is provided for this purpose.
* dump
    * Only use in MediaWiki dump maintenance scripts. In such scripts, all queries, even fast ones, should use this group.
* vslow
    * Only use for queries that are expected to have a long execution time. For example, when calculating per-wiki site statistics.

Use the below example syntax to connect to a database when your query falls into one of the above 3 categories:
```php
$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
$lb->getConnectionRef( DB_REPLICA, 'vslow' );
```

## Supported DBMSs

MediaWiki is written primarily for use with MySQL. Queries are optimized for it and its schema is considered the canonical version. However, MediaWiki does support the following other DBMSs to varying degrees:

* PostgreSQL
* SQLite

More information can be found about each of these databases (known issues, level of support, extra configuration) in the `databases` subdirectory in this folder.

## Use of `GROUP BY`

MySQL supports `GROUP BY` without checking anything in the `SELECT` clause.  Other DBMSs (especially Postgres) are stricter and require that all the  non-aggregate items in the `SELECT` clause appear in the `GROUP BY`. For this reason, it is highly discouraged to use `SELECT *` with `GROUP BY`  queries.
