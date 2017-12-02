<?php

namespace InetStudio\AdminPanel\Models\ACL;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\AdminPanel\Models\Traits\HasJSONColumns;

class UserProfileModel extends Model
{
    use SoftDeletes;
    use HasJSONColumns;

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
     * Обратное отношение с моделью пользователя.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
