## statsd-php-client

Be careful, see the [Upgrading section](Readme.md#upgrade) for <= v1.0.4, there's a BC.

[![Build Status](https://secure.travis-ci.org/liuggio/statsd-php-client.png)](http://travis-ci.org/liuggio/statsd-php-client) [![Latest Stable Version](https://poser.pugx.org/liuggio/statsd-php-client/v/stable.png)](https://packagist.org/packages/liuggio/statsd-php-client) [![Total Downloads](https://poser.pugx.org/liuggio/statsd-php-client/downloads.png)](https://packagist.org/packages/liuggio/statsd-php-client)

`statsd-php-client` is an Open Source, and **Object Oriented** Client for **etsy/statsd** written in php

- `StatsdDataFactory` creates the `Liuggio\StatsdClient\Entity\StatsdDataInterface` Objects

- `Sender` just sends data over the network (there are many sender)

- `StatsdClient` sends the created objects via the `Sender` to the server

## Why use this library instead the [statsd/php-example](https://github.com/etsy/statsd/blob/master/examples/php-example.php)?

- You are wise.

- You could also use monolog to redirect data to statsd

- This library is tested.

- This library optimizes the messages to send, compressing multiple messages in individual UDP packets.

- This library pays attention to the maximum length of the UDP.

- This library is made by Objects not array, but it also accepts array.

- You do want to debug the packets, and using `SysLogSender` the packets will be logged in your `syslog` log (on debian-like distro: `tail -f /var/log/syslog`)


## Example

1. create the Sender

2. create the Client

3. create the Factory

4. the Factory will help you to create data

5. the Client will send the data

### Standard Usage

```php
use Liuggio\StatsdClient\StatsdClient,
    Liuggio\StatsdClient\Factory\StatsdDataFactory,
    Liuggio\StatsdClient\Sender\SocketSender,
    Liuggio\StatsdClient\Service\StatsdService;
// use Liuggio\StatsdClient\Sender\SysLogSender;

$sender = new SocketSender(/*'localhost', 8126, 'udp'*/);
// $sender = new SysLogSender(); // enabling this, the packet will not send over the socket

$client  = new StatsdClient($sender);
$factory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');
$service = new StatsdService($client, $factory);

// create the metrics with the service
$service->timing('usageTime', 100);
$service->increment('visitor');
$service->decrement('click');
$service->gauge('gaugor', 333);
$service->set('uniques', 765);

// send the data to statsd
$service->flush();

```

### Usage with Monolog

```php
use Liuggio\StatsdClient\StatsdClient,
    Liuggio\StatsdClient\Factory\StatsdDataFactory,
    Liuggio\StatsdClient\Sender\SocketSender;
// use Liuggio\StatsdClient\Sender\SysLogSender;

use Monolog\Logger;
use Liuggio\StatsdClient\Monolog\Handler\StatsDHandler;

$sender = new SocketSender(/*'localhost', 8126, 'udp'*/);
// $sender = new SysLogSender(); // enabling this, the packet will not send over the socket
$client = new StatsdClient($sender);
$factory = new StatsdDataFactory();

$logger = new Logger('my_logger');
$logger->pushHandler(new StatsDHandler($client, $factory, 'prefix', Logger::DEBUG));

$logger->addInfo('My logger is now ready');
```

the output will be:  `prefix.my_logger.INFO.My-logger:1|c" 36 Bytes`




## Short Theory

### Easily Install StatSD and Graphite

In order to try this application monitor you have to install etsy/statsd and Graphite

see this blog post to install it with vagrant [Easy install statsd graphite](http://welcometothebundle.com/easily-install-statsd-and-graphite-with-vagrant/).

#### [StatsD](https://github.com/etsy/statsd)

StatsD is a simple daemon for easy stats aggregation

#### [Graphite](http://graphite.wikidot.com/)

Graphite is a Scalable Realtime Graphing

#### The Client sends data with UDP (faster)

https://www.google.com/search?q=tcp+vs+udp

## Contribution

Active contribution and patches are very welcome.
To keep things in shape we have quite a bunch of unit tests. If you're submitting pull requests please
make sure that they are still passing and if you add functionality please
take a look at the coverage as well it should be pretty high :)

- First fork or clone the repository

```
git clone git://github.com/liuggio/statsd-php-client.git
cd statsd-php-client
```

- Install vendors:

``` bash
composer.phar install
```

- This will give you proper results:

``` bash
phpunit --coverage-html reports
```

## Upgrade

BC from the v1.0.4 version, [see Sender and Client differences](https://github.com/liuggio/statsd-php-client/pull/5/files).


## TODO

example with monolog
