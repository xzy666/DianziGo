<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    /**
     * Use Table
     * @var string
     */
    protected $table = 'challenge';

    /**
     * Use Timestamps
     * @var bool
     */
    public $timestamps = true;

    /**
     * Forbid Input param
     * @var array
     */
    protected $guarded = [
        'id',
    ];
}
