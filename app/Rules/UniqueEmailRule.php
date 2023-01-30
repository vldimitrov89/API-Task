<?php

namespace Api\app\Rules;

use Rakit\Validation\Rule;
use Api\app\Models\User as User;

class UniqueEmailRule extends Rule
{
    protected $message = ":attribute has been used";

    protected $fillableParams = ['column'];

    protected $pdo;

    public function __construct(User $pdo)
    {
        $this->pdo = $pdo;
    }

    public function check($value): bool
    {
        // make sure required parameters exists
        $this->requireParameters(['column']);

        // getting parameters
        $column = $this->parameter('column');

        // do query
        $count = $this->pdo::where($column, '=', $value)->count();

        // true for valid, false for invalid
        return intval($count) === 0;
    }
}
