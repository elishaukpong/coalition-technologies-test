<?php

namespace App\Observers;

use App\Tasks;
use Log;

class TaskObserver
{
    /**
     * Handle the task "creating" event.
     *
     * @param  \App\Tasks  $task
     * @return void
     */
    public function creating(Tasks $task)
    {
        if (is_null($task->position)) {
            $task->position = Tasks::whereProjectId($task->project_id)->max('position') + 1;
            return;
        }
    }

    /**
     * Handle the task "updating" event.
     *
     * @param  \App\Tasks  $task
     * @return void
     */
    public function updating(Tasks $task)
    {

        if ($task->isClean('position')) {
            return;
        }

        if (is_null($task->position)) {
            $task->position = Tasks::whereProjectId($task->project_id)->max('position');
        }

        if ($task->getOriginal('position') > $task->position) {
            $newAndOldPosition = [
                $task->position, $task->getOriginal('position')
            ];
        } else {
            $newAndOldPosition = [
                $task->getOriginal('position'), $task->position
            ];
        }

        $lowerTasks = Tasks::where('project_id', $task->project_id)
            ->whereBetween('position', $newAndOldPosition)
            ->where('id', '!=', $task->id)
            ->get();

        foreach ($lowerTasks as $lowerTask) {
            if ($task->getOriginal('position') < $task->position) {
                $lowerTask->position--;
            } else {
                $lowerTask->position++;
            }
            $lowerTask->saveQuietly();
        }

    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Tasks  $task
     * @return void
     */
    public function deleted(Tasks $task)
    {
        $lowerTasks = Tasks::whereProjectId($task->project_id)
            ->where('position', '>', $task->position)
            ->get();

        foreach ($lowerTasks as $lowerTask) {
            $lowerTask->position--;
            $lowerTask->saveQuietly();
        }
    }
}
