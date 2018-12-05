<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Session.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session query()
 * @mixin \Eloquent
 *
 * @property string $id
 * @property string $session_id
 * @property string|null $encryption_payload
 * @property string|null $status
 * @property int $expires_in
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Call whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Call whereEncryptionPayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Call whereExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Call whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Call whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Call whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Call whereUpdatedAt($value)
 */
class Call extends Model
{
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'calls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'session_id',
        'status',
        'encryption_payload',
        'expires_in',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                 => 'string',
        'session_id'         => 'string',
        'status'             => 'string',
        'encryption_payload' => 'string',
        'expires_in'         => 'timestamp',
        'created_at'         => 'timestamp',
        'updated_at'         => 'timestamp',
    ];
}
