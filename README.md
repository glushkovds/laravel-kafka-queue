# Laravel Kafka Queue Driver

## Features

1. Supports delayed jobs
2. Supports retries  
3. Supports Laravel 10
4. Supports authorization

## Attention 

The code this library is still dirty, there is no stable version. Use it at your own risk.
Follow the updates, suggest yours.

## Thanks to

* https://github.com/rapideinternet/laravel-queue-kafka - sources, it seems this lib is abandoned
* https://github.com/lukaszaleckas/laravel-kafka-queue - delayed jobs, docker environment

## Installation

1. Build and install librdkafka library, install pecl extension rdkafka

```dockerfile
ENV LIBRDKAFKA_VERSION "v2.2.0"
RUN cd /tmp \
    && mkdir librdkafka \
    && cd librdkafka \
    && git clone --branch ${LIBRDKAFKA_VERSION} --depth 1 https://github.com/confluentinc/librdkafka.git . \
    && ./configure \
    && make \
    && make install \
    && rm -rf /tmp/librdkafka
RUN pecl install rdkafka
RUN docker-php-ext-enable rdkafka
```
2. Install this package via composer using

```bash
composer require glushkovds/laravel-kafka-queue
```

3. Add service provider to `app.php`

```php
LaravelKafka\KafkaQueueServiceProvider::class
```

4. Add Kafka's connection to `queue.php` config

```php
'kafka' => [
    'driver' => 'kafka',
    'broker_list' => env('KAFKA_BROKER_LIST', 'kafka:9092'),
    'queue' => env('KAFKA_QUEUE', 'default'),
    'heartbeat' => 5 * 1000, //Heartbeat in milliseconds
    'group_name' => env('KAFKA_QUEUE_GROUP', 'default'),
    'producer_timeout' => 1 * 1000, //Producer timeout in milliseconds
    'consumer_timeout' => 3 * 1000, //Consumer timeout in milliseconds
    'auth_login' => env('KAFKA_AUTH_LOGIN'),
    'auth_mechanism' => env('KAFKA_AUTH_MECHANISM', 'PLAIN'),
    'auth_password' => env('KAFKA_AUTH_PASSWORD'),
    'auth_ssl_ca_location' => env('KAFKA_AUTH_SSL_CA_LOCATION'),
],
```

5. Add credentials to `.env` file

```
KAFKA_BROKER_LIST=kafka1:9092,kafka2:9093
KAFKA_QUEUE=my-queue
KAFKA_QUEUE_GROUP=my-app
KAFKA_AUTH_LOGIN=
KAFKA_AUTH_PASSWORD=
```

### More about pecl extension rdkafka

https://arnaud.le-blanc.net/php-rdkafka-doc/phpdoc/index.html
