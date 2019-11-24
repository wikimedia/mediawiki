FileBackend Architecture {#filebackendarch}
========================

Introduction
------------

To abstract away the differences among different types of storage media,
MediaWiki is providing an interface known as FileBackend. Any MediaWiki
interaction with stored files should thus use a FileBackend object.

Different types of backing storage media are supported (ranging from local
file system to distributed object stores). The types include:

* FSFileBackend (used for mounted file systems)
* SwiftFileBackend (used for Swift or Ceph Rados+RGW object stores)
* FileBackendMultiWrite (useful for transitioning from one backend to another)

Configuration documentation for each type of backend is to be found in their
__construct() inline documentation.

Setup
-----

File backends are registered in LocalSettings.php via the global variable
$wgFileBackends. To access one of those defined backends, one would use
FileBackendStore::get( <name> ) which will bring back a FileBackend object
handle. Such handles are reused for any subsequent get() call (via singleton).
The FileBackends objects are caching request calls such as file stats,
SHA1 requests or TCP connection handles.

Note: Some backends may require additional PHP extensions to be enabled or can rely on a
MediaWiki extension. This is often the case when a FileBackend subclass makes use of an
upstream client API for communicating with the backing store.

## File operations

The MediaWiki FileBackend API supports various operations on either files or
directories. See FileBackend.php for full documentation for each function.

### Reading

The following basic operations are supported for reading from a backend:

On files:
* stat a file for basic information (timestamp, size)
* read a file into a string or  several files into a map of path names to strings
* download a file or set of files to a temporary file (on a mounted file system)
* get the SHA1 hash of a file
* get various properties of a file (stat information, content time, MIME information, ...)

On directories:
* get a list of files directly under a directory
* get a recursive list of files under a directory
* get a list of directories directly under a directory
* get a recursive list of directories under a directory

Note: Backend handles should return directory listings as iterators, all though in some cases
they may just be simple arrays (which can still be iterated over). Iterators allow for
callers to traverse a large number of file listings without consuming excessive RAM in
the process. Either the memory consumed is flatly bounded (if the iterator does paging)
or it is proportional to the depth of the portion of the directory tree being traversed
(if the iterator works via recursion).

### Writing

The following basic operations are supported for writing or changing in the backend:

On files:
* store (copying a mounted file system file into storage)
* create (creating a file within storage from a string)
* copy (within storage)
* move (within storage)
* delete (within storage)
* lock/unlock (lock or unlock a file in storage)

The following operations are supported for writing directories in the backend:
* prepare (create parent container and directories for a path)
* secure (try to lock-down access to a container)
* publish (try to reverse the effects of secure)
* clean (remove empty containers or directories)

### Invoking an operation

Generally, callers should use doOperations() or doQuickOperations() when doing
batches of changes, rather than making a suite of single operation calls. This
makes the system tolerate high latency much better by pipelining operations
when possible.

doOperations() should be used for working on important original data, i.e. when
consistency is important. The former will only pipeline operations that do not
depend on each other. It is best if the operations that do not depend on each
other occur in consecutive groups. This function can also log file changes to
a journal (see FileJournal), which can be used to sync two backend instances.
One might use this function for user uploads of file for example.

doQuickOperations() is more geared toward ephemeral items that can be easily
regenerated from original data. It will always pipeline without checking for
dependencies within the operation batch. One might use this function for
creating and purging generated thumbnails of original files for example.

## Consistency

Not all backing stores are sequentially consistent by default. Various FileBackend
functions offer a "latest" option that can be passed in to assure (or try to assure)
that the latest version of the file is read. Some backing stores are consistent by
default, but callers should always assume that without this option, stale data may
be read. This is actually true for stores that have eventual consistency.

Note that file listing functions have no "latest" flag, and thus some systems may
return stale data. Thus callers should avoid assuming that listings contain changes
made my the current client or any other client from a very short time ago. For example,
creating a file under a directory and then immediately doing a file listing operation
on that directory may result in a listing that does not include that file.

## Locking

Locking is effective if and only if a proper lock manager is registered and is
actually being used by the backend. Lock managers can be registered in LocalSettings.php
using the $wgLockManagers global configuration variable.

For object stores, locking is not generally useful for avoiding partially
written or read objects, since most stores use Multi Version Concurrency
Control (MVCC) to avoid this. However, locking can be important when:
* One or more operations must be done without objects changing in the meantime.
* It can also be useful when a file read is used to determine a file write or DB change.
  For example, doOperations() first checks that there will be no "file already exists"
  or "file does not exist" type errors before attempting an operation batch. This works
  by stating the files first, and is only safe if the files are locked in the meantime.

When locking, callers should use the latest available file data for reads.
Also, one should always lock the file *before* reading it, not after. If stale data is
used to determine a write, there will be some data corruption, even when reads of the
original file finally start returning the updated data without needing the "latest"
option (eventual consistency). The "scoped" lock functions are preferable since
there is not the problem of forgetting to unlock due to early returns or exceptions.

Since acquiring locks can fail, and lock managers can be non-blocking, callers should:
* Acquire all required locks up font
* Be prepared for the case where locks fail to be acquired
* Possible retry acquiring certain locks

MVCC is also a useful pattern to use on top of the backend interface, because operations
are not atomic, even with doOperations(), so doing complex batch file changes or changing
files and updating a database row can result in partially written "transactions". Thus one
should avoid changing files once they have been stored, except perhaps with ephemeral data
that are tolerant of some degree of inconsistency.

Callers can use their own locking (e.g. SELECT FOR UPDATE) if it is more convenient, but
note that all callers that change any of the files should then go through functions that
acquire these locks. For example, if a caller just directly uses the file backend store()
function, it will ignore any custom "FOR UPDATE" locks, which can cause problems.

## Object stores

Support for object stores (like Amazon S3/Swift) drive much of the API and design
decisions of FileBackend, but using any POSIX compliant file systems works fine.
The system essentially stores "files" in "containers". For a mounted file system
as a backing store, "files" will just be files under directories. For an object store
as a backing store, the "files" will be objects stored in actual containers.

## File system and Object store differences

An advantage of object stores is the reduced Round-Trip Times. This is
achieved by avoiding the need to create each parent directory before placing a
file somewhere. It gets worse the deeper the directory hierarchy is. Another
advantage of object stores is that object listings tend to use databases, which
scale better than the linked list directories that file sytems sometimes use.
File systems like btrfs and xfs use tree structures, which scale better.
For both object stores and file systems, using "/" in filenames will allow for the
intuitive use of directory functions. For example, creating a file in Swift
called "container/a/b/file1" will mean that:
- a "directory listing" of "container/a" will contain "b",
- and a "file listing" of "b" will contain "file1"

This means that switching from an object store to a file system and vise versa
using the FileBackend interface will generally be harmless. However, one must be
aware of some important differences:

* In a file system, you cannot have a file and a directory within the same path
  whereas it is possible in an object stores. Calling code should avoid any layouts
  which allow files and directories at the same path.
* Some file systems have file name length restrictions or overall path length
  restrictions that others do not. The same goes with object stores which might
  have a maximum object length or a limitation regarding the number of files
  under a container or volume.
* Latency varies among systems, certain access patterns may not be tolerable for
  certain backends but may hold up for others. Some backend subclasses use
  MediaWiki's object caching for serving stat requests, which can greatly
  reduce latency. Making sure that the backend has pipelining (see the
  "parallelize" and "concurrency" settings) enabled can also mask latency in
  batch operation scenarios.
* File systems may implement directories as linked-lists or other structures
  with poor scalability, so calling code should use layouts that shard the data.
  Instead of storing files like "container/file.txt", one can store files like
  "container/<x>/<y>/file.txt". It is best if "sharding" optional or configurable.
