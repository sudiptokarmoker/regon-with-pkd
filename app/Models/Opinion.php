<?php

namespace App\Models;

use Eloquent as Model;



/**
 * Class Opinion
 * @package App\Models
 * @version January 22, 2022, 9:43 pm UTC
 *
 * @property \App\Models\User $user
 * @property integer $doc_delivery
 * @property integer $payment
 * @property integer $cooperation
 * @property string $comment
 * @property integer $user_id
 */
class Opinion extends Model
{
    public $table = 'opinions';

    public $fillable = [
        'nip',
        'doc_delivery',
        'payment',
        'cooperation',
        'comment',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'doc_delivery' => 'integer',
        'payment' => 'integer',
        'cooperation' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nip' => 'required',
        'doc_delivery' => 'nullable|min:0|max:5',
        'payment' => 'nullable|min:0|max:5',
        'cooperation' => 'nullable|min:0|max:5',
        'comment' => 'required|max:255'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
