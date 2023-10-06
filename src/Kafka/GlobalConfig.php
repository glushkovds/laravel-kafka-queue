<?php

namespace LaravelKafka\Kafka;

use RdKafka\Conf;

class GlobalConfig extends Conf
{
    public const DELAYED_QUEUE_POSTFIX = '-delayed';

    public const CONFIG_BROKER_LIST = 'metadata.broker.list';

    public function __construct(array $config)
    {
        parent::__construct();

        $this->set(self::CONFIG_BROKER_LIST, $config['broker_list']);
        if ($config['auth_login']) {
            $this->set('sasl.mechanisms', $config['auth_mechanism']);
            $this->set('sasl.username', $config['auth_login']);
            $this->set('sasl.password', $config['auth_password']);
            if ($config['auth_ssl_ca_location']) {
                $this->set('ssl.ca.location', $config['auth_ssl_ca_location']);
            }
        }
    }
}
