#!/bin/bash -l

echo "
version: '3.7'
services:
  mediawiki:
    # On Linux, these lines ensure file ownership is set to your host user/group
    user: \"\${MW_DOCKER_UID}:\${MW_DOCKER_GID}\"
" >> docker-compose.override.yml

echo "MW_DOCKER_UID=$(id -u)
MW_DOCKER_GID=$(id -g)" >> .env