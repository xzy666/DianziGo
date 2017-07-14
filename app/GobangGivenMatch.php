<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GobangGivenMatch extends Model
{
    /**
     * Use Table
     * @var string
     */
    protected $table = 'gobang_given_match';

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
