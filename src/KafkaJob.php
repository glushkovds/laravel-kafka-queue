<?php

namespace LaravelKafka;

use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Contracts\Queue\Job as JobInterface;
use Illuminate\Support\Str;
use Rapide\LaravelQueueKafka\Exceptions\QueueKafkaException;
use RdKafka\Message;

class KafkaJob extends Job implements JobInterface
{
    /** @var Container */
    protected $container;

    /** @var KafkaQueue */
    protected KafkaQueue $kafkaQueue;

    /** @var string */
    protected string $job;

    /** @var array */
    protected array $decoded;

    /** @var Message */
    protected Message $message;

    /**
     * @param Container $container
     * @param KafkaQueue $kafkaQueue
     * @param string $job
     * @param string $queue
     * @param Message $message
     */
    public function __construct(
        Container $container,
        KafkaQueue $kafkaQueue,
        string $job,
        string $queue,
        Message $message,
        string $connectionName,
    ) {
        $this->container = $container;
        $this->job = $job;
        $this->kafkaQueue = $kafkaQueue;
        $this->queue = $queue;
        $this->message = $message;
        $this->connectionName = $connectionName;
        $this->decoded = $this->payload();
    }

    /**
     * @return string
     */
    public function getRawBody(): string
    {
        return $this->job;
    }

    /**
     * @return int
     */
    public function attempts(): int
    {
        return ($this->decoded['attempts'] ?? null) + 1;
    }

    /**
     * @return string|null
     */
    public function getJobId(): ?string
    {
        return $this->decoded['id'] ?? null;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getMessageTimestamp(): int
    {
        return $this->message->timestamp / 1000;
    }

    /**
     * Release the job back into the queue.
     *
     * @param int $delay
     *
     * @throws Exception
     */
    public function release($delay = 0)
    {
        parent::release($delay);
        $this->delete();
        $this->kafkaQueue->release($delay, $this);
    }

}
