#!/bin/bash

set -e

PHP_FILE="${1}"

#run from project root

export PHP_IDE_CONFIG="serverName=_"

mkdir -p tests/temp
mkdir -p tests/log

vendor/bin/tester --stop-on-fail "tests/${PHP_FILE}"
