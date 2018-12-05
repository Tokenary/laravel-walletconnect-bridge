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
 * @property string|null $encryption_payload
 * @property string|null $token
 * @property string|null $type
 * @property string|null $webhook
 * @property int $expires_in
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session whereEncryptionPayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session whereExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Session whereWebhook($value)
 */
class Session extends Model
{
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
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
        'encryption_payload' => 'string',
        'expires_in'         => 'timestamp',
        'created_at'         => 'timestamp',
        'updated_at'         => 'timestamp',
    ];
}
