# MediaWiki Developers

Welcome to the MediaWiki community! Please see [How to become a MediaWiki
hacker](https://www.mediawiki.org/wiki/How_to_become_a_MediaWiki_hacker) for
general information on contributing to MediaWiki.

## Development environment

MediaWiki provides an extendable local development environment based on Docker Compose. This environment provides PHP, Apache, Xdebug and a SQLite database.

**Do not use the development environment to serve a public website! Bad things would happen!**

More documentation, examples, and configuration recipes are available at [mediawiki.org/wiki/MediaWiki-Docker][mw-docker].

Support is available on the [Libera IRC network][libera-home] in the [#mediawiki channel][libera-webchat], and on Phabricator by creating tasks with the [MediaWiki-Docker][mw-docker-phab] tag.

[mw-docker]: https://www.mediawiki.org/wiki/MediaWiki-Docker
[mw-docker-phab]: https://phabricator.wikimedia.org/tag/mediawiki-docker/
[libera-home]: https://libera.chat/
[libera-webchat]: https://web.libera.chat/#mediawiki

## Quickstart

### 1. Requirements

You'll need to have Docker installed:

* [Docker Desktop][docker-install] for macOS or Windows.
* [docker][docker-linux] and [docker-compose][docker-compose] for Linux.

[docker-install]: https://docs.docker.com/get-docker/
[docker-linux]: https://docs.docker.com/engine/install/
[docker-compose]: https://docs.docker.com/compose/install/

**Linux users**:

* We recommend installing `docker-compose` by [downloading the binary
  release][dc-release]. You can also use `pip`, your OS package manager, or
  even run it in a container, but downloading the binary release is the easiest
  method.
* Follow the instructions to ["Manage Docker as a non-root user"][dc-non-root]

[dc-release]: https://docs.docker.com/compose/install/#install-compose-on-linux-systems
[dc-non-root]: https://docs.docker.com/install/linux/linux-postinstall/#manage-docker-as-a-non-root-user

### 2. Prepare `.env` file

Using a text editor, create a `.env` file in the root of the MediaWiki core
repository, and copy these contents into that file:

```sh
MW_SCRIPT_PATH=/w
MW_SERVER=http://localhost:8080
MW_DOCKER_PORT=8080
MEDIAWIKI_USER=Admin
MEDIAWIKI_PASSWORD=dockerpass
XDEBUG_CONFIG=
XDEBUG_ENABLE=true
XHPROF_ENABLE=true
```

Next, run the following command to add your user ID and group ID to your `.env` file:

```sh
echo "MW_DOCKER_UID=$(id -u)
MW_DOCKER_GID=$(id -g)" >> .env
```

Linux users: If you'd like to use Xdebug features inside your IDE, then create a `docker-compose.override.yml` file as well:

```yaml
version: '3.7'
services:
  mediawiki:
    # For Linux: This extra_hosts section enables Xdebug-IDE communication:
    extra_hosts:
      - "host.docker.internal:host-gateway"
```

### 3. Create the environment

* Start the containers:
  ```sh
  docker-compose up -d
  ```
  The "up" command makes sure that the PHP and webserver containers are running (and any others in the `docker-compose.yml` file). It is safe to run at any time, and will do nothing if the containers are already running.

  The first time, it may take a few minutes to download new Docker images.

  The `-d` argument stands for "detached" mode, which run the services in the background. If you suspect a problem with one of the services, you can run it without `-d` to follow the server logs directly from your termimnal. You don't have to stop the services first, if you ran it with `-d` and then without, you'll get connected to the already running containers including a decent backscroll of server logs.

  Note that MediaWiki debug logs go to `/cache/*.log` files (not sent to docker).

* Install PHP dependencies from Composer:
  ```sh
  docker-compose exec mediawiki composer update
  ```

* Install MediaWiki:
  ```sh
  docker-compose exec mediawiki /bin/bash /docker/install.sh
  ```

Done! The wiki should now be available for you at <http://localhost:8080>.

## Usage

### Running commands

You can use `docker-compose exec mediawiki bash` to open a bash shell in the
MediaWiki container. You can then run one or more commands as needed and stay
within the container shell.

You can also run a single command in the container directly from your host
shell, for example: `docker-compose exec mediawiki php maintenance/update.php`.

### PHPUnit

Run a single PHPUnit file or directory:

```sh
docker-compose exec mediawiki bash
instance:/w$ cd tests/phpunit
instance:/w/tests/phpunit$ php phpunit.php path/to/my/test/
```

See [PHPUnit on mediawiki.org][phpunit-testing] for more examples.

[phpunit-testing]: https://www.mediawiki.org/wiki/Manual:PHP_unit_testing/Running_the_tests

### Selenium

You can use [Fresh][fresh] to run [Selenium in a dedicated
container][selenium-dedicated]. Example usage:

```sh
fresh-node -env -net
npm ci
npm run selenium-test
```

[selenium-dedicated]: https://www.mediawiki.org/wiki/Selenium/Getting_Started/Run_tests_using_Fresh

### API Testing

You can use [Fresh][fresh] to run [API tests in a dedicated
container][api-dedicated]. Example usage:

```sh
export MW_SERVER=http://localhost:8080/
export MW_SCRIPT_PATH=/w
export MEDIAWIKI_USER=Admin
export MEDIAWIKI_PASSWORD=dockerpass
fresh-node -env -net
# Create .api-testing.config.json as documented on
# https://www.mediawiki.org/wiki/MediaWiki_API_integration_tests
npm ci
npm run api-testing
```

[fresh]: https://github.com/wikimedia/fresh
[api-dedicated]: https://www.mediawiki.org/wiki/MediaWiki_API_integration_tests

## Modify the development environment

You can override the default services from a `docker-compose.override.yml`
file, and make use of those overrides by changing `LocalSettings.php`.

Example overrides and configurations can be found under
[MediaWiki-Docker on mediawiki.org][mw-docker].

After updating `docker-compose.override.yml`, run `docker-compose down`
followed by `docker-compose up -d` for changes to take effect.

### Install extra packages

If you need root on the container to install system packages with `apt-get` for
troubleshooting, you can open a shell as root with
`docker-compose exec --user root mediawiki bash`.

### Install extensions and skins

To install an extension or skin, follow the intructions of the mediawiki.org
page for the extension or skin in question, and look for any dependencies
or additional steps that may be needed.

For most extensions, only two steps are needed: download the code to the
right directory, and then enable the component from `LocalSettings.php`.

To install the Vector skin:

1. Clone the skin:
    ```sh
    cd skins/
    git clone https://gerrit.wikimedia.org/r/mediawiki/skins/Vector
    ```

2. Enable the skin, by adding the following to `LocalSettings.php`:
    ```php
    wfLoadSkin( 'Vector' );
    ```

To install the EventLogging extension:

1. Clone the extension repository:

    ```sh
    cd extensions/
    git clone https://gerrit.wikimedia.org/r/mediawiki/extensions/EventLogging
    ```

    Alternatively, if you need to extension repositories elsewhere on disk, mount each one as a overlapping volume in `docker-compose.override.yml`. The is comparable to a symlink, but those are not well-supported in Docker.

    ```yaml
   version: '3.7'
   services:
     mediawiki:
       volumes:
         - ~/Code/Vector:/var/www/html/w/skins/vector:cached
    ```

2. Enable the extension, by adding the following to `LocalSettings.php`:
    ```php
    wfLoadExtension( 'EventLogging' );
    ```

### Xdebug

By default, you will need to set `XDEBUG_TRIGGER=1` in the GET/POST, or as an
environment variable, to turn on Xdebug for a request.

You can also install a browser extension for controlling whether Xdebug is
active.  See the [official Xdebug Step Debugging][step-debug], particularly the
"Activating Step Debugging" section, for more details.

[step-debug]: https://xdebug.org/docs/step_debug

If you wish to run Xdebug on every request, you can set
`start_with_request=yes` in `XDEBUG_CONFIG` in your .env file:

```
XDEBUG_CONFIG=start_with_request=yes
```

You can pass any of Xdebug's configuration values in this variable.  For example:

```
XDEBUG_CONFIG=client_host=192.168.42.34 client_port=9000 log=/tmp/xdebug.log
```

This shouldn't be necessary for basic use cases, but see [the Xdebug settings
documentation](https://xdebug.org/docs/all_settings) for available settings.

### Stop or recreate environment

Stop the environment, perhaps to reduce load when working on something
else. This preserves the containers, to be restarted later quickly with
the `docker-compose up -d` command.

```sh
docker-compose stop
```

Destroy and re-create the environment. This will delete the containers,
including any logs, caches, and other modifications you may have made
via the shell.

```sh
docker-compose down
docker-compose up -d
```

### Re-install the database

To empty the wiki database and re-install it:

* Remove or rename the `LocalSettings.php` file.
* Delete the `cache/sqlite` directory.
* Re-run the "Install MediaWiki database" command.

You can now restore or copy over any modifications you had in your previous `LocalSettings.php` file. And if you have additonal extensions installed that required a database table, then also run: `docker-compose exec mediawiki php maintenance/update.php`.

## Troubleshooting

### Caching

If you suspect a change is not applying due to caching, start by [hard
refreshing](https://en.wikipedia.org/wiki/Wikipedia:Bypass_your_cache) the browser.

If that doesn't work, you can narrow it down by disabling various server-side
caching layers in `LocalSettings.php`, as follows:

```php
$wgMainCacheType = CACHE_NONE;
$wgMessageCacheType = CACHE_NONE;
$wgParserCacheType = CACHE_NONE;
$wgResourceLoaderMaxage = [
  'versioned' => 0,
  'unversioned' => 0
];
```

The default settings of MediaWiki are such that caching is smart and changes
propagate immediately. Using the above settings may slow down your wiki
significantly. Especially on macOS and Windows, where Docker Desktop uses
a VM internally and thus has longer file access times.

See [Manual:Caching][manual-caching] on mediawiki.org for more information.

[manual-caching]: https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Caching

### Xdebug ports

Older versions of Xdebug used port 9000, which could conflict with php-fpm
running on the host.  This document used to recommend a workaround of telling
your IDE to listen on a different port (e.g. 9009) and setting
`XDEBUG_CONFIG=remote_port=9009` in your `.env`.

Xdebug 3.x now uses the `client_port` value, which defaults to 9003.  This
should no longer conflict with local php-fpm installations, but you may need
to change the settings in your IDE or debugger.

### Linux desktop, host not found

The image uses `host.docker.internal` as the `client_host` value which
should allow Xdebug work for Docker for Mac/Windows.

On Linux, you need to create a `docker-compose.override.yml` file with the following
contents:

```yaml
version: '3.7'
services:
  mediawiki:
    extra_hosts:
      - "host.docker.internal:host-gateway"
```

With the latest version of Docker on Linux hosts, this _should_ work
transparently as long as you're using the recommended
`docker-compose.override.yml`.  If it doesn't, first check `docker version` to
make sure you're running Docker 20.10.2 or above, and `docker-compose version`
to make sure it's 1.27.4 or above.

If Xdebug still doesn't work, try specifying the hostname or IP address of your
host. The IP address works more reliably.  You can obtain it by running e.g.
`ip -4 addr show docker0` and copying the IP address into the config in `.env`,
like `XDEBUG_CONFIG=remote_host=172.17.0.1`

### Generating logs

Switching on the remote log for Xdebug comes at a performance cost so only
use it while troubleshooting. You can enable it like so: `XDEBUG_CONFIG=remote_log=/tmp/xdebug.log`

### "(Permission Denied)" errors on running docker-compose

See if you're able to run any docker commands to start with. Try running
`docker container ls`, which should also throw a permission error. If not,
go through the following steps to get access to the socket that the docker
client uses to talk to the daemon.

`sudo usermod -aG docker $USER`

And then relogin (or `newgrp docker`) to re-login with the new group membership.

### "(Cannot access the database: Unknown error (localhost))"

The environment's working directory has recently changed to `/var/www/html/w`.
Reconfigure this in your `LocalSettings.php` by ensuring that the following
values are set correctly:

```php
$wgScriptPath = '/w';
$wgSQLiteDataDir = "/var/www/html/w/cache/sqlite";
```
