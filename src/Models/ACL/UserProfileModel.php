<?php

namespace InetStudio\AdminPanel\Models\ACL;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfileModel extends Model
{
    use SoftDeletes;

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'users_profiles';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'additional_info',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы к базовым типам.
     *
     * @var array
     */
    protected $casts = [
        'additional_info' => 'array',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Обратное отношение с моделью пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
     * Determine if the media item has a custom property with the given name.
     */
    public function hasAdditionalInfo(string $propertyName): bool
    {
        return array_has($this->additional_info, $propertyName);
    }

    /**
     * Get if the value of additional info with the given name.
     *
     * @param string $propertyName
     * @param mixed $default
     *
     * @return mixed
     */
    public function getAdditionalInfo(string $propertyName, $default = null)
    {
        return array_get($this->additional_info, $propertyName, $default);
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function setAdditionalInfo(string $name, $value)
    {
        $additionalInfo = $this->additional_info;

        array_set($additionalInfo, $name, $value);

        $this->additional_info = $additionalInfo;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function forgetAdditionalInfo(string $name)
    {
        $additionalInfo = $this->additional_info;

        array_forget($additionalInfo, $name);

        $this->additional_info = $additionalInfo;

        return $this;
    }
}
