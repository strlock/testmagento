#!/bin/bash
bin/magento setup:install \
--base-url=http://magento1 \
--db-host=localhost \
--db-name=magento1 \
--db-user=root \
--db-password=skodachiter \
--admin-firstname=admin \
--admin-lastname=admin \
--admin-email=admin@admin.com \
--admin-user=admin \
--admin-password=admin123 \
--language=en_US \
--currency=USD \
--timezone=America/Chicago \
--use-rewrites=1 \
--search-engine=elasticsearch7 \
--elasticsearch-host=127.0.0.1 \
--elasticsearch-port=9200 \
--elasticsearch-index-prefix=magento2_1 \
--elasticsearch-timeout=15

