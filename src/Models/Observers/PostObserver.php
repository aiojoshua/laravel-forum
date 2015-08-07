<?php

namespace Riari\Forum\Models\Observers;

class PostObserver
{
    public function deleted($model)
    {
        if (!is_null($model->children)) {
            $model->children()->update(['post_id' => 0]);
        }

        if ($model->thread->posts->isEmpty()) {
            if ((!$model->thread->trashed() && !$model->thread->exists) || !$model->thread->exists) {
                $model->thread()->forceDelete();
            } else {
                $model->thread()->delete();
            }
        }
    }

    public function restored($model)
    {
        if (is_null($model->thread->posts)) {
            $model->thread()->withTrashed()->restore();
        }
    }
}