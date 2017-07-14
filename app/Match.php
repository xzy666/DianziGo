<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * Use Table
     * @var string
     */
    protected $table = 'match';

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
