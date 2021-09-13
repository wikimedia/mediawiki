# MediaWiki Developers

Welcome to the MediaWiki community! Please see [How to become a MediaWiki
hacker](https://www.mediawiki.org/wiki/How_to_become_a_MediaWiki_hacker) for
general information on contributing to MediaWiki.

## Docker Developer Environment

MediaWiki provides an extendable local development environment based on
Docker Compose.

The default environment provides PHP, Apache, Xdebug and a SQLite database.
(**Do not run this stack in production! Bad things would happen!**)

More documentation as well as example overrides and configuration recipes
are available at [mediawiki.org/wiki/MediaWiki-Docker][mw-docker].

Support is available on the [Libera IRC network][Libera] at `#mediawiki`
and on Wikimedia Phabricator at [#MediaWiki-Docker][mw-docker-phab].

[mw-docker]: https://www.mediawiki.org/wiki/MediaWiki-Docker
[mw-docker-phab]: https://phabricator.wikimedia.org/project/profile/3094/
[Libera]: https://libera.chat/

### Requirements

You'll need a locally running Docker and Docker Compose:

  - [Docker installation instructions][docker-install]
  - [Docker Compose installation instructions][docker-compose]

[docker-install]: https://docs.docker.com/get-docker/
[docker-compose]: https://docs.docker.com/compose/install/

---

**Linux users**

* We recommend installing `docker-compose` by [downloading the binary
  release][dc-release]. You can also use `pip`, your OS package manager, or
  even run it in a container, but downloading the binary release is the easiest
  method.
* Follow the instructions to ["Manage Docker as a non-root user"][dc-non-root]

[dc-release]: https://docs.docker.com/compose/install/#install-compose-on-linux-systems
[dc-non-root]: https://docs.docker.com/install/linux/linux-postinstall/#manage-docker-as-a-non-root-user

---

### Quickstart

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

Linux users only: create a `docker-compose.override.yml` containing the following:

```yaml
version: '3.7'
services:
  mediawiki:
    # Linux users only: this extra_hosts section is necessary for Xdebug:
    extra_hosts:
      - "host.docker.internal:host-gateway"
```

#### Start environment and install MediaWiki

Start the environment:

```sh
# -d is detached mode - runs containers in the background:
docker-compose up -d
```

Install Composer dependencies:

```sh
docker-compose exec mediawiki composer update
```

Install MediaWiki in the environment:

```sh
docker-compose exec mediawiki /bin/bash /docker/install.sh
```

##### Re-install

Remove or rename `LocalSettings.php`, delete the `cache/sqlite` directory, then
re-run the installation command above. Copy over any changes from your previous
`LocalSettings.php` and then run `maintenance/update.php`.

### Usage

#### Running commands

You can use `docker-compose exec mediawiki bash` to open a bash shell in the
MediaWiki container, or you can run commands in the container from your host,
for example: `docker-compose exec mediawiki php maintenance/update.php`

#### Running tests

##### PHPUnit

Run all tests:

```sh
docker-compose exec mediawiki php tests/phpunit/phpunit.php
```

Run a single test:

```sh
docker-compose exec mediawiki php tests/phpunit/phpunit.php ./path/to/test
```

See [PHPUnit Testing][phpunit-testing] on MediaWiki.org for more help.

[phpunit-testing]: https://www.mediawiki.org/wiki/Manual:PHP_unit_testing/Running_the_tests

##### Selenium

You can use [Fresh][fresh] to run [Selenium in a dedicated
container][selenium-dedicated]. Example usage:

```sh
fresh-node -env -net
npm ci
npm run selenium-test
```

[selenium-dedicated]: https://www.mediawiki.org/wiki/Selenium/Getting_Started/Run_tests_using_Fresh

#### API Testing

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

### Modifying the development environment

You can override the default services with a `docker-compose.override.yml`
file, and configure those overrides with changes to `LocalSettings.php`.

Example overrides and configurations can be found at
[MediaWiki-Docker][mw-docker].

After updating `docker-compose.override.yml`, run `docker-compose down`
followed by `docker-compose up -d` for changes to take effect.

#### Installing extra packages

If you need root on the container to install packages for troubleshooting,
you can open a shell as root with `docker-compose exec --user root mediawiki
bash`.

#### Using extensions and skins

Using extensions and skins requires the extension or skin directory to exist
in the appropriate folder within the core directory, or added as a volume in
`docker-compose.override.yml`

##### Example: Use Vector skin

1. Clone the skin to `skins/Vector`:

    ```sh
    git clone "https://gerrit.wikimedia.org/r/mediawiki/skins/Vector" skins/Vector
    ```

    OR

    mount the directory as a volume in `docker-compose.override.yml`:
    ```yaml
   version: '3.7'
   services:
     mediawiki:
       volumes:
         - ~/vector:/var/www/html/w/skins/vector:cached
    ```

2. Configure MediaWiki to use the skin:

    ```sh
    echo "wfLoadSkin( 'Vector' );" >> LocalSettings.php
    ```

#### Xdebug

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

#### Caching

MediaWiki by default comes with fairly aggressive caching that may complicate
development. A common `LocalSettings.php` configuration for development is
as follows:

``` lang=php
$wgMainCacheType = CACHE_NONE;
$wgMessageCacheType = CACHE_NONE;
$wgParserCacheType = CACHE_NONE;
$wgResourceLoaderMaxage = [
  'versioned' => 0,
  'unversioned' => 0
];
```

For MacOS and Windows users, this may significantly slow down page loads.
Depending on what you're working on, it may be better to not disable caching and
instead do hard refreshes in your browser.

See [Manual:Caching][manual-caching] on MediaWiki.org for more information.

[manual-caching]: https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Caching

##### Troubleshooting

###### Xdebug ports

Older versions of Xdebug used port 9000, which could conflict with php-fpm
running on the host.  This document used to recommend a workaround of telling
your IDE to listen on a different port (e.g. 9009) and setting
`XDEBUG_CONFIG=remote_port=9009` in your `.env`.

Xdebug 3.x now uses the `client_port` value, which defaults to 9003.  This
should no longer conflict with local php-fpm installations, but you may need
to change the settings in your IDE or debugger.

###### Linux desktop, host not found

The image uses `host.docker.internal` as the `client_host` value which
should allow Xdebug work for Docker for Mac/Windows.

On Linux, you need to create a `docker-compose.override.yml` file with the following
contents:

``` lang=yaml
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

###### Generating logs

Switching on the remote log for Xdebug comes at a performance cost so only
use it while troubleshooting. You can enable it like so: `XDEBUG_CONFIG=remote_log=/tmp/xdebug.log`

###### "(Permission Denied)" errors on running docker-compose

See if you're able to run any docker commands to start with. Try running
`docker container ls` - it should also throw you a permission error. If not,
go through the following steps to get access to the socket that the docker
client uses to talk to the daemon.

`sudo usermod -aG docker $USER`

And then relogin (or `newgrp docker`) to re-login with the new group membership.

###### "(Cannot access the database: Unknown error (localhost))"

The environment's working directory has recently changed to `/var/www/html/w`.
Reconfigure this in your `LocalSettings.php` by ensuring that the following
values are set correctly:

```php
$wgScriptPath = '/w';
$wgSQLiteDataDir = "/var/www/html/w/cache/sqlite";
```
