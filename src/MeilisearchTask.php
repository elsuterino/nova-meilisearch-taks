<?php

namespace Elsuterino\NovaMeilisearchTask;

use Laravel\Nova\Card;

class MeilisearchTask extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    /**
     * Get the component name for the element.
     */
    public function component(): string
    {
        return 'meilisearch-task';
    }

    public function indexes(string ...$indexes): static
    {
        $this->withMeta(['indexes' => $indexes]);

        return $this;
    }
}
