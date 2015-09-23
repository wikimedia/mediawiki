Kafka-php
==========

[![Build Status](https://travis-ci.org/nmred/kafka-php.svg?branch=master)](https://travis-ci.org/nmred/Kafka-php)

Kafka-php is a php client with Zookeeper integration for apache Kafka. It only supports the latest version of Kafka 0.8 which is still under development, so this module is _not production ready_ so far.

The Zookeeper integration does the following jobs:

* Loads broker metadata from Zookeeper before we can communicate with the Kafka server
* Watches broker state, if broker changes, the client will refresh broker and topic metadata stored in the client

## Requirements

* Minimum PHP version: 5.3.3.
* Apache Kafka 0.8.x
* You need to have access to your Kafka instance and be able to connect through TCP. You can obtain a copy and instructions on how to setup kafka at https://github.com/kafka-dev/kafka [kafka-08-quick-start](https://cwiki.apache.org/KAFKA/kafka-08-quick-start.html)
* The [PHP Zookeeper extension](https://github.com/andreiz/php-zookeeper) is required if you want to use the Zookeeper-based consumer.
* Productor can not dependency zookeeper

## Installation
Add the lib directory to the PHP include_path and use an autoloader like the one in the examples directory (the code follows the PEAR/Zend one-class-per-file convention).

## Composer Install

Simply add a dependency on nmred/kafka-php to your project's composer.json file if you use Composer to manage the dependencies of your project. Here is a minimal example of a composer.json file :

```
{
	"require": {
		"nmred/kafka-php": "0.1.*"
	}
}
```

## Produce

### \Kafka\Produce::getInstance($hostList, $timeout)

* `hostList` : zookeeper host list , example 127.0.0.1:2181,192.168.1.114:2181
* `timeout`  : zookeeper timeout

### \Kafka\Produce::setRequireAck($ack = -1)

* `ack`: This field indicates how many acknowledgements the servers should receive before responding to the request.

### \Kafka\Produce::setMessages($topicName, $partitionId, $messages)

* `topicName` : The topic that data is being published to.
* `partitionId` : The partition that data is being published to.
* `messages` : [Array] publish message.

### \Kafka\Produce::send()

send message sets to the server. 

### Example

``` php
$produce = \Kafka\Produce::getInstance('localhost:2181', 3000);

$produce->setRequireAck(-1);
$produce->setMessages('test', 0, array('test1111111'));
$produce->setMessages('test6', 0, array('test1111111'));
$produce->setMessages('test6', 2, array('test1111111'));
$produce->setMessages('test6', 1, array('test111111111133'));
$result = $produce->send();
var_dump($result);

```

## Consumer

### \Kafka\Consumer::getInstance($hostList, $timeout)

* `hostList` : zookeeper host list , example 127.0.0.1:2181,192.168.1.114:2181
* `timeout`  : zookeeper timeout

### \Kafka\Consumer::setGroup($groupName)

* `groupName` : Specify consumer group.

### \Kafka\Consumer::setPartition($topicName, $partitionId, $offset = 0)

* `topicName` : The topic that data is being fetch to. 
* `partitionId` : The partition that data is being fetch to.
* `offset`: set fetch offset. default `0`.

### \Kafka\Consumer::fetch()

return fetch message Iterator. `\Kafka\Protocol\Fetch\Topic`

### \Kafka\Protocol\Fetch\Topic

this object is iterator

`key` : topic name
`value`: `\Kafka\Protocol\Fetch\Partition`

### \Kafka\Protocol\Fetch\Partition

this object is iterator.

`key`: partition id
`value`: messageSet object

#### \Kafka\Protocol\Fetch\Partition::getErrCode()

return partition fetch errcode.

#### \Kafka\Protocol\Fetch\Partition::getHighOffset()

return partition fetch offset.

### \Kafka\Protocol\Fetch\MessageSet

this object is iterator. `\Kafka\Protocol\Fetch\Message`

### Example

``` php
$consumer = \Kafka\Consumer::getInstance('localhost:2181');

$consumer->setGroup('testgroup');
$consumer->setPartition('test', 0);
$consumer->setPartition('test6', 2, 10);
$result = $consumer->fetch();
foreach ($result as $topicName => $topic) {
    foreach ($topic as $partId => $partition) {
        var_dump($partition->getHighOffset());
        foreach ($partition as $message) {
            var_dump((string)$message);
        }
    }
}
```

## Basic Protocol
### Produce API

The produce API is used to send message sets to the server. For efficiency it allows sending message sets intended for many topic partitions in a single request.

\Kafka\Protocol\Encoder::produceRequest

#### Param struct
``` php
array(
    'required_ack' => 1,
        // This field indicates how many acknowledgements the servers should receive before responding to the request. default `0`
        // If it is 0 the server will not send any response
        // If it is -1 the server will block until the message is committed by all in sync replicas before sending a response 
        // For any number > 1 the server will block waiting for this number of acknowledgements to occur
    'timeout' => 1000,
        // This provides a maximum time in milliseconds the server can await the receipt of the number of acknowledgements in RequiredAcks.
    'data' => array(
        array(
            'topic_name' => 'testtopic',
                // The topic that data is being published to.[String]
            'partitions' => array(
                array(
                    'partition_id' => 0,
                        // The partition that data is being published to.
                    'messages' => array(
                        'message1', 
                        // [String] message
                    ),
                ),
            ),
        ),
    ),
);
```

#### Return

Array

#### Example

``` php

$data = array(
    'required_ack' => 1,
    'timeout' => 1000,
    'data' => array(
        array(
            'topic_name' => 'test',
            'partitions' => array(
                array(
                    'partition_id' => 0,
                    'messages' => array(
                        'message1',
                        'message2',
                    ),
                ),
            ),
        ),
    ),
);

$conn = new \Kafka\Socket('localhost', '9092');
$conn->connect();
$encoder = new \Kafka\Protocol\Encoder($conn);
$encoder->produceRequest($data);

$decoder = new \Kafka\Protocol\Decoder($conn);
$result = $decoder->produceResponse();
var_dump($result);

```
### Fetch API

The fetch API is used to fetch a chunk of one or more logs for some topic-partitions. Logically one specifies the topics, partitions, and starting offset at which to begin the fetch and gets back a chunk of messages

\Kafka\Protocol\Encoder::fetchRequest

#### Param struct
``` php
array(
    'replica_id' => -1,
        // The replica id indicates the node id of the replica initiating this request. default `-1`
    'max_wait_time' => 100,
        // The max wait time is the maximum amount of time in milliseconds to block waiting if insufficient data is available at the time the request is issued. default 100 ms.
    'min_bytes' => 64 * 1024 // 64k
        // This is the minimum number of bytes of messages that must be available to give a response. default 64k.
    'data' => array(
        array(
            'topic_name' => 'testtopic',
                // The topic that data is being published to.[String]
            'partitions' => array(
                array(
                    'partition_id' => 0,
                        // The partition that data is being published to.
                    'offset' => 0,
                        // The offset to begin this fetch from. default 0
                    'max_bytes' => 100 * 1024 * 1024,
                        // This is the minimum number of bytes of messages that must be available to give a response. default 100Mb
                ),
            ),
        ),
    ),
);
```

#### Return

\Kafka\Protocol\Fetch\Topic iterator

#### Example
``` php

$data = array(
    'data' => array(
        array(
            'topic_name' => 'test',
            'partitions' => array(
                array(
                    'partition_id' => 0,
                    'offset' => 0, 
                ),
            ),
        ),
    ),
);

$conn = new \Kafka\Socket('localhost', '9092');
$conn->connect();
$encoder = new \Kafka\Protocol\Encoder($conn);
$encoder->fetchRequest($data);

$decoder = new \Kafka\Protocol\Decoder($conn);
$result = $decoder->fetchResponse();
var_dump($result);

```
### Offset API

This API describes the valid offset range available for a set of topic-partitions. As with the produce and fetch APIs requests must be directed to the broker that is currently the leader for the partitions in question. This can be determined using the metadata API.

\Kafka\Protocol\Encoder::offsetRequest

####param struct
``` php
array(
    'replica_id' => -1,
        // The replica id indicates the node id of the replica initiating this request. default `-1`
    'data' => array(
        array(
            'topic_name' => 'testtopic',
                // The topic that data is being published to.[String]
            'partitions' => array(
                array(
                    'partition_id' => 0,
                        // The partition that get offset .
                    'time' => -1,
                        // Used to ask for all messages before a certain time (ms). 
                        // Specify -1 to receive the latest offsets
                        // Specify -2 to receive the earliest available offset. 
                    'max_offset' => 1, 
                        // max return offset element. default 10000.
                ),
            ),
        ),
    ),
);
```

#### Return

Array.

#### Example

``` php

$data = array(
    'data' => array(
        array(
            'topic_name' => 'test',
            'partitions' => array(
                array(
                    'partition_id' => 0,
                    'max_offset' => 10, 
                    'time' => -1, 
                ),
            ),
        ),
    ),
);

$conn = new \Kafka\Socket('localhost', '9092');
$conn->connect();
$encoder = new \Kafka\Protocol\Encoder($conn);
$encoder->offsetRequest($data);

$decoder = new \Kafka\Protocol\Decoder($conn);
$result = $decoder->offsetResponse();
var_dump($result);

```
### Metadata API

The metdata returned is at the partition level, but grouped together by topic for convenience and to avoid redundancy. For each partition the metadata contains the information for the leader as well as for all the replicas and the list of replicas that are currently in-sync.

\Kafka\Protocol\Encoder::metadataRequest

####param struct
``` php
array(
   'topic_name1', // topic name
);
```

#### Return

Array.

#### Example

``` php

$data = array(
    'test'
);

$conn = new \Kafka\Socket('localhost', '9092');
$conn->connect();
$encoder = new \Kafka\Protocol\Encoder($conn);
$encoder->metadataRequest($data);

$decoder = new \Kafka\Protocol\Decoder($conn);
$result = $decoder->metadataResponse();
var_dump($result);

```
### Offset Commit API

These APIs allow for centralized management of offsets. 

\Kafka\Protocol\Encoder::commitOffsetRequest

####param struct
``` php
array(
    'group_id' => 'testgroup',
        // consumer group 
    'data' => array(
        array(
            'topic_name' => 'testtopic',
                // The topic that data is being published to.[String]
            'partitions' => array(
                array(
                    'partition_id' => 0,
                        // The partition that get offset .
                    'offset' => 0,
                        // The offset to begin this fetch from.
                    'time' => -1, 
                        // If the time stamp field is set to -1, then the broker sets the time stamp to the receive time before committing the offset.
                ),
            ),
        ),
    ),
);
```

#### Return

Array.

#### Example

``` php
$data = array(
    'group_id' => 'testgroup',
    'data' => array(
        array(
            'topic_name' => 'test',
            'partitions' => array(
                array(
                    'partition_id' => 0,
                    'offset' => 2, 
                ),
            ),
        ),
    ),
);


$conn = new \Kafka\Socket('localhost', '9092');
$conn->connect();
$encoder = new \Kafka\Protocol\Encoder($conn);
$encoder->commitOffsetRequest($data);

$decoder = new \Kafka\Protocol\Decoder($conn);
$result = $decoder->commitOffsetResponse();
var_dump($result);

```
### Offset Fetch API

These APIs allow for centralized management of offsets. 

\Kafka\Protocol\Encoder::fetchOffsetRequest

####param struct
``` php
array(
    'group_id' => 'testgroup',
        // consumer group 
    'data' => array(
        array(
            'topic_name' => 'testtopic',
                // The topic that data is being published to.[String]
            'partitions' => array(
                array(
                    'partition_id' => 0,
                        // The partition that get offset .
                ),
            ),
        ),
    ),
);
```

#### Return

Array.

#### Example

``` php
$data = array(
    'group_id' => 'testgroup',
    'data' => array(
        array(
            'topic_name' => 'test',
            'partitions' => array(
                array(
                    'partition_id' => 0,
                ),
            ),
        ),
    ),
);


$conn = new \Kafka\Socket('localhost', '9092');
$conn->connect();
$encoder = new \Kafka\Protocol\Encoder($conn);
$encoder->fetchOffsetRequest($data);

$decoder = new \Kafka\Protocol\Decoder($conn);
$result = $decoder->fetchOffsetResponse();
var_dump($result);

```
