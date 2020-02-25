# MediaWiki Developers

Welcome to the MediaWiki community! Please see [How to become a MediaWiki
hacker](https://www.mediawiki.org/wiki/How_to_become_a_MediaWiki_hacker) for
general information on contributing to MediaWiki.

## Docker Developer Environment

MediaWiki provides an extendable local development environment based on
Docker Compose.

The default environment provides PHP, Apache, XDebug and a SQLite database.
(**Do not run this stack in production! Bad things might happen!**)

More documentation is available at [mediawiki.org/wiki/Docker][mw-docker]
and example overrides and configuration recipes are available at
[mediawiki.org/wiki/Docker/Recipes][mw-docker-recipes].

Support is available on the [Freenode IRC network][freenode] at `#mediawiki`
and on Wikimedia Phabricator at [#MediaWiki-Docker][mw-docker-phab].

[mw-docker]: https://www.mediawiki.org/wiki/Docker
[mw-docker-recipes]: https://www.mediawiki.org/wiki/Docker/Recipes
[mw-docker-phab]: https://phabricator.wikimedia.org/project/profile/3094/
[freenode]: https://freenode.net/

### Requirements

You'll need a locally running Docker and Docker Compose:

  - [Docker installation instructions][docker-install]
  - [Docker Compose installation instructions][docker-compose]

[docker-install]: https://docs.docker.com/install/
[docker-compose]: https://docs.docker.com/compose/install/

### Quickstart

#### MacOS & Windows prerequisites

Hopefully, this should Just Work™.

#### Linux prerequisites

If you are developing on a Linux system, first create a
`docker-compose.override.yml` containing the following:

```yaml
version: '3.7'
services:
  mediawiki:
    # On Linux, these lines ensure file ownership is set to your host user/group
    user: "${MW_DOCKER_UID}:${MW_DOCKER_GID}"
```

Next, ensure that `$MW_DOCKER_UID` and `$MW_DOCKER_GID` are set in your environment:

```
export MW_DOCKER_UID=$(id -u)
export MW_DOCKER_GID=$(id -g)
```

The above lines may be added to your `.bashrc` or other shell configuration.

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
docker-compose exec mediawiki \
bash -c 'php maintenance/install.php \
--server $MW_SERVER \
--scriptpath=$MW_SCRIPTPATH \
--dbtype $MW_DBTYPE \
--dbpath $MW_DBPATH \
--lang $MW_LANG \
--pass $MW_PASS \
$MW_SITENAME $MW_USER'
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
docker-compose exec mediawiki php tests/phpunit/phpunit.php /path/to/test
```

See [PHPUnit Testing][phpunit-testing] on MediaWiki.org for more help.

[phpunit-testing]: https://www.mediawiki.org/wiki/Manual:PHP_unit_testing/Running_the_unit_tests

##### Selenium

You can use [Fresh][fresh] to run [Selenium in a dedicated
container][selenium-dedicated]. Example usage:

```sh
export MW_SERVER=http://localhost:8080
export MW_SCRIPT_PATH=/
export MEDIAWIKI_USER=Admin
export MEDIAWIKI_PASSWORD=dockerpass
fresh-node -env -net
npm ci
npm run selenium
```

[selenium-dedicated]: https://www.mediawiki.org/wiki/Selenium/Node.js/Target_Local_MediaWiki_(Container)

#### API Testing

You can use [Fresh][fresh] to run [API tests in a dedicated
container][api-dedicated]. Example usage:

```sh
export MW_SERVER=http://localhost:8080/
export MW_SCRIPT_PATH=/
export MEDIAWIKI_USER=Admin
export MEDIAWIKI_PASSWORD=dockerpass
fresh-node -env -net
# Create .api-testing.config.json as documented on
# https://www.mediawiki.org/wiki/MediaWiki_API_integration_tests
npm ci
npm run api-testing
```

[fresh]: (https://github.com/wikimedia/fresh
[api-dedicated]: https://www.mediawiki.org/wiki/MediaWiki_API_integration_tests

### Modifying the development environment

You can override the default services with a `docker-compose.override.yml`
file, and configure those overrides with changes to `LocalSettings.php`.
Example overrides and configurations can be found at
https://www.mediawiki.org/wiki/Docker

After updating `docker-compose.override.yml`, run `docker-compose down`
followed by `docker-compose up -d` for changes to take effect.

#### Installing extra packages

If you need root on the container to install packages for troubleshooting,
you can open a shell as root with `docker-compose exec --user root mediawiki
bash`.

#### XDebug

You can override the XDebug configuration included with the default image by
passing `XDEBUG_CONFIG={your config}` in `environment`:

``` yaml
version: '3.7'
services:
  mediawiki:
    environment:
      XDEBUG_CONFIG: remote_enable=1 remote_host=172.17.0.1 remote_log=/tmp/xdebug.log remote_port=9009
```

##### Troubleshooting

###### Port conflicts

If you installed php-fpm on your host, that is listening on port 9000 and
will conflict with XDebug. The workaround is to tell your IDE to listen on a
different port (e.g. 9009) and to set the configuration in your
`docker-compose.override.yml` file: `XDEBUG_CONFIG: remote_port=9009`

###### Linux desktop, host not found

The image uses `host.docker.internal` as the `remote_host` value which
should work for Docker for Mac/Windows. On Linux hosts, you need to specify
the hostname or IP address of your host. The IP address works more reliably.
You can obtain it by running e.g. `ip -4 addr show docker0` and copying the
IP address into the config, like `XDEBUG_CONFIG: remote_host=172.17.0.1`

###### Generating logs

Switching on the remote log for XDebug comes at a performance cost so only
use it while troubleshooting. You can enable it like so: `XDEBUG_CONFIG:
remote_log=/tmp/xdebug.log`
