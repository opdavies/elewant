<?php

namespace Elewant\Infrastructure;

use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\HerdCollection;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\EventStore;

final class EventStoreHerdCollection extends AggregateRepository implements HerdCollection
{

    public function __construct(EventStore $eventStore)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass(Herd::class),
            new AggregateTranslator(),
            null,
            null,
            false
        );
    }

    public function save(Herd $herd): void
    {
        $this->saveAggregateRoot($herd);
    }

    /**
     * @return null|Herd
     */
    public function get(HerdId $herdId): ?Herd
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getAggregateRoot($herdId->toString());
    }
}
