<?php

namespace App\Listeners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Session;

class EloquentEventsSubscriber implements ShouldQueue
{
    use Queueable;

    /**
     * Handle created events.
     */
    public function onModelCreated($event_name, $model)
    {
        $model = current($model);
        $class = get_class($model);
        if ($class !== 'App\\Models\\Portada') {
            if (in_array('pos', $model->getFillable()))
                $model->increment('pos', $class::max('pos') + 200);
        } else
            $model->increment('pos', $class::max('pos') + 1);

        $this->putEventNameOnSession($event_name, $model);
    }

    /**
     * Handle updated events.
     */
    public function onModelUpdated($event_name, $model)
    {
        //Updated logic here
        $this->putEventNameOnSession($event_name, $model);
    }

    /**
     * Handle deleting events.
     */
    public function onModelDeleting($event_name, $model)
    {
        $model = current($model);
        foreach ($model->getRelations() as $relation => $options) {
            $model->{$relation}()->{$options['operation']}();
        }
    }

    /**
     * Handle deleted events.
     */
    public function onModelDeleted($event_name, $model)
    {
        //Deleted logic here
        $this->putEventNameOnSession($event_name, $model);
    }

    private function putEventNameOnSession($event_name, $model)
    {
        Session::put('model-changed', [
            'event' => $event_name,
            'model' => $model
        ]);
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'eloquent.created: *',
            'App\Listeners\EloquentEventsSubscriber@onModelCreated'
        );

        $events->listen(
            'eloquent.updated: *',
            'App\Listeners\EloquentEventsSubscriber@onModelUpdated'
        );

        $events->listen(
            'eloquent.deleting: *',
            'App\Listeners\EloquentEventsSubscriber@onModelDeleting'
        );

        $events->listen(
            'eloquent.deleted: *',
            'App\Listeners\EloquentEventsSubscriber@onModelDeleted'
        );
    }
}
