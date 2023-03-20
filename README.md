# symfony-product-es-demo
Simple skeleton cached REST API with elastic search implementation

## Requires

- PHP 8
- PHP-PDO-MYSQL, PHP-GD, PHP-ZIP, PHP-CURL
- REDIS
- ELASTIC SEARCH

## Installation

```shell
# install dependencies
docker-compose run php-cli composer install

# install db
docker-compose run php-cli php bin/console doctrine:migration:migrate

# run application
docker-compose up -d
```

## List of applictions

- API: http://localhost:8000
- Kibana: http://localhost:5601
- ElasticSearch: http://localhost:9200
- PHPmyAdmin: http://localhost:9001

## API Endpionts

- [Products API](/docs/products/README.md)
- [Categories API](/docs/categories/README.md)
- [Files API](/docs/files/README.md)