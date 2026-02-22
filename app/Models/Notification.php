<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Отметить уведомление как прочитанное
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Отметить уведомление как непрочитанное
     */
    public function markAsUnread()
    {
        if (!is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
        }
    }

    /**
     * Получить уведомляемый объект
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Получить данные уведомления
     */
    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Проверить, прочитано ли уведомление
     */
    public function read()
    {
        return $this->read_at !== null;
    }

    /**
     * Проверить, не прочитано ли уведомление
     */
    public function unread()
    {
        return $this->read_at === null;
    }
}