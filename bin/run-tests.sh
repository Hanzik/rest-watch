#!/bin/bash

set -e

PHP_FILE="${1}"

export PHP_IDE_CONFIG="serverName=_"

mkdir -p tests/temp
mkdir -p tests/log

vendor/bin/tester -p php -c /etc/php/7.1/cli/php.ini --stop-on-fail "tests/${PHP_FILE}"
