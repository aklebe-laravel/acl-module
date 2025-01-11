<?php

namespace Modules\Acl\app\Events;

use Illuminate\Database\Eloquent\Model;

class BelongsToManySaved
{
    /**
     * @var Model|null
     */
    public ?Model $model = null;

    /**
     * Create a new event instance.
     */
    public function __construct(?Model $model)
    {
        // remember model for listeners ...
        $this->model = $model;
    }

}
