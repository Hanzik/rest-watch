#!/bin/bash

set -e

VERSION="2.2.8"

if [[ ! -L www/api/swagger ]] && [[ ! -d www/api/swagger ]] && [[ ! -f www/api/swagger ]]; then
	mkdir -p www/api/swagger
fi

cd www/api

wget -q "https://github.com/swagger-api/swagger-ui/archive/v${VERSION}.tar.gz" -O swagger-ui.tar.gz

rm -rf swagger

tar -zxf swagger-ui.tar.gz
rm swagger-ui.tar.gz

ln -s "swagger-ui-${VERSION}/dist" swagger
