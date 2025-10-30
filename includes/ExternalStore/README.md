%ExternalStore {#externalstorearch}
========================

%ExternalStore is an optional feature that enables persistent object storage
outside the main database, primarily for revision text (also known as a "blob").

The main public interface for interacting with %ExternalStore is ExternalStoreAccess.
Though note that higher-level concepts like {@link MediaWiki\Revision\RevisionRecord} and
text blobs have their own dedicated interface: {@link MediaWiki\Revision\RevisionStore}, and
{@link MediaWiki\Storage\BlobStore}.

## Concepts

### URL

Objects in external stores are internally identified by a special URL.
The URL is of the form `<store protocol>://<location>/<object name>`.

### Protocol

The protocol represents which ExternalStoreMedium class is used. The following protocols are
supported by default:

- `DB`: ExternalStoreDB
- `http`: ExternalStoreHttp
- `mwstore`: ExternalStoreMwstore

Multiple protocols may be enabled at the same time. For example, to support reading older data
while using a different protocol for new data.

Protocols are configured via {@link $wgExternalStores}. The ExternalStoreMedium class is decided
based on concatenating the value from $wgExternalStores to the string `ExternalStore`, with a
ucfirst transformation applied as-needed.

A custom protocol called "foobar" could be configured by implementing ExternalStoreMedium in a
subclass called `ExternalStoreFoobar`.

### Location

The location identifies a particular instance of given store protocol.

In the case of ExternalStoreDB, the location represents a database cluster (one or more database
servers that hold the same data).

When using the default of {@link Wikimedia::Rdbms::LBFactorySimple LBFactorySimple}, these
clusters can be configured via {@link $wgExternalServers}. Otherwise, external clusters must be
configured via {@link $wgLBFactoryConf}.

## New insertions

The destination of newly stored text blobs is configured via {@link $wgDefaultExternalStore}.
To enable use of %ExternalStore for new blobs, this must be set to a non-empty array. This can
be disabled to store new blobs in the main database instead, it does not affect how existing
blobs are read.

Each destination uses a partial URL of the form `<store protocol>://<location>`.
When a blob is inserted, we randomly pick an available protocol/location pair from this list.
Insertions will fail-over to another default destination if the chosen one is unavailable.

## Append-only {#externalstore-appendonly}

%ExternalStore is designed as an append-only system, to persist data in a way that is highly
reliable and immutable. As such, the interface is restricted to fetch and insert operations,
and specifically does not permit modification or deletion once data is stored.

This design benefits MediaWiki in a number of ways:

* The limited interface provides flexibility to each protocol implementation.
* Caching is trivial and safe.
* Stable references to external store can be kept outside of it, in the core database and anywhere
  else in caching or other storage layers, without needing to track of propagate changes.
* Historical data can be stored with high reliability guarantees and operational safety:
  * External database clusters may be operated in read-only mode, directly through MySQL.
  * Each replica within the cluster may operate as independent static backup.
  * Database replication between hosts may be turned off.
  * Even command-line access from outside MediaWiki can't accidentally affect historical data.

In case of maintenance tasks such as recompression, we generally iterate through known blobs
and write new blobs as-needed and gracefully update pointers accordingly. If an entire cluster
has been copied or recompressed to a new location, it can be taken out of rotation, with any
storage space freed at that time. Note that multiple locations may be physically colocated
on the same hardware, e.g. by running multiple instances of MySQL. Although it may be simpler
to free space by doing recompression during other routine maintenance, such as when migrating
data from old to new hardware.
