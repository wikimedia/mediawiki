JobQueue Architecture {#jobqueuearch}
=====================
Notes on the Job queuing system architecture.

## Introduction

The data model consists of the following main components:
* The JobSpecification class represents a job type and associated parameters
  that can be enqueued via a JobQueue or JobQueueGroup without needing to construct
  the full PHP class associated with the given job type.
* The Job class represents a particular deferred task that happens in the
  background. All jobs subclass the Job class and put the main logic in the
  function called run().
* The JobQueue class represents a particular queue of jobs of a certain type.
  For example there may be a queue for email jobs and a queue for CDN purge
  jobs.
* The JobQueueGroup class represents all job queues for a given wiki
  and provides helper methods to enqueue or dequeue jobs on that wiki
  without the caller needing to be aware of type-specific queue configuration.
  The `JobQueueGroup` service offers a convenience JobQueueGroup instance
  for the common case of dealing with jobs in the context of the local wiki.
* The JobQueueGroupFactory class manages per-wiki JobQueueGroup objects.

## Job queues

Each job type has its own queue and is associated to a storage medium. One
queue might save its jobs in Redis, while another one uses would use a database.

Storage mediums are defined in a JobQueue subclass. Before using it, you must
define in $wgJobTypeConf a mapping of the job type to the given JobQueue subclass.

The following core queue classes are available:
* JobQueueDB (stores jobs in the `job` table in a database)
* JobQueueRedis (stores jobs in a redis server)

All queue classes support some basic operations (though some may be no-ops):
* enqueueing a batch of jobs
* dequeueing a single job
* acknowledging a job is completed
* checking if the queue is empty

All queue implementations must offer at-least-once execution guarantees for enqueued jobs.
The execution order of enqueued jobs may however vary depending on the implementation,
so callers should not assume any particular execution order.

## Job queue aggregator

Since each job type has its own queue, and wiki-farms may have many wikis,
there might be a large number of queues to keep track of. To avoid wasting
large amounts of time polling empty queues, aggregators exists to keep track
of which queues are ready.

The following queue aggregator classes are available:
* JobQueueAggregatorRedis (uses a redis server to track ready queues)

Some aggregators cache data for a few minutes while others may be always up to date.
This can be an important factor for jobs that need a low pickup time (or latency).

## Job execution
The high-level job execution flow for a queue consists of the following steps:

1. Dequeue a job specification (type and corresponding parameters) from the corresponding storage medium.
2. Deduplicate the job according to deduplication rules (optional).
3. Marshal the job into the corresponding Job subclass and run it via Job::execute().
4. Run Job::tearDown().
5. If the Job failed (as described below), attempt to retry it up to the configured retry limit.

An exception thrown by Job::run(), or Job::run() returning `false`, will cause
the job runner to retry the job up to the configured retry limit, unless Job::allowRetries() returns `false`.
As of MediaWiki 1.43, no job runner implementation makes a distinction between transient errors
(which are retry-safe) and non-transient errors (which are not retry-safe).
A Job implementation that is expected to have both transient and non-transient error states
should therefore catch and process non-transient errors internally and return `true`
from Job::run() in such cases, to reduce the incidence of unwanted retries for such errors
while still benefiting from the automated retry logic for transient errors.

Note that in a distributed job runner implementation, the above steps
may be split between different infrastructure components, as is the case with
the changeprop-based system used by Wikimedia Foundation. This may require
additional configuration than overriding Job::allowRetries() to ensure that
other job runner components do not attempt to retry a job that is not retry-safe (T358939).

Since job runner implementations may vary in reliability, job classes should be
idempotent, to maintain correctness even if the job happens to run more than once.

## Job deduplication
A Job subclass may override Job::getDeduplicationInfo() and Job::ignoreDuplicates() to allow jobs to be deduplicated if the job runner in use supports it.

If Job::ignoreDuplicates() returns `true`, the deduplication logic must consider the job to be a duplicate if a Job of the same type with identical deduplication info has been executed later than the enqueue timestamp of the job.

Jobs that spawn many smaller jobs (so-called "root" and "leaf" jobs) may enable additional deduplication logic,
to make in-flight leaf-jobs no-ops, when a newer root job with identical parameters gets enqueued.
This is done by passing two special parameters, `rootJobTimestamp` and `rootJobSignature`,
which hold the MediaWiki timestamp at which the root job was enqueued, and an SHA-1 checksum uniquely identifying the root job, respectively.
The Job::newRootJobParams() convenience method facilitates adding these parameters to a preexisting parameter set.
When deduplicating leaf jobs, the job runner must consider a leaf job to be a duplicate
if a root job with an identical signature has been executed by the runner later than the
`rootJobTimestamp` of the leaf job.

## Enqueueing jobs
For enqueueing jobs, JobQueue and JobQueueGroup offer the JobQueue::push() and
JobQueue::lazyPush() methods. The former synchronously enqueues the job and propagates
a JobQueueError exception to the caller in case of failure, while the latter defers enqueueing
the job when running a web request context until after the response has been flushed to the client.
Callers should prefer using `lazyPush` unless it is necessary to surface enqueue failures.
