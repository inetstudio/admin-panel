<?php

namespace InetStudio\AdminPanel\Models\Traits;

use InetStudio\AdminPanel\Models\UserProfileModel;
use InetStudio\AdminPanel\Models\UserSocialProfileModel;

trait HasProfiles
{
    /**
     * Отношение "один к одному" с моделью профиля.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function profile()
    {
        return $this->hasOne(UserProfileModel::class, 'user_id', 'id');
    }

    /**
     * Отношение "один ко многим" с моделью социального профиля.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function socialProfiles()
    {
        return $this->hasMany(UserSocialProfileModel::class, 'user_id', 'id');
    }
}
