#!/bin/bash

vendor/bin/swagger app/presenters/ -b bin/swaggerConstants.php --output www/api/swagger.json

sed -i -e 's/"http:\/\/petstore.swagger.io\/v2\/swagger.json"/"swagger.json"/g' www/api/swagger/index.html
sed -i -e 's/showOperationIds: false/showOperationIds: false, operationsSorter : "method"/g' www/api/swagger/index.html
