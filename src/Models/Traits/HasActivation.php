<?php

namespace InetStudio\AdminPanel\Models\Traits;

use InetStudio\AdminPanel\Models\UserActivationModel;

trait HasActivation
{
    /**
     * Отношение "один к одному" с моделью активации.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function activation()
    {
        return $this->hasOne(UserActivationModel::class, 'user_id', 'id');
    }
}
