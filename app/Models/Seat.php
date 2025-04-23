<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    public int $row;
    public int $column;
    public bool $available;

    public function __construct(int $row, int $column)
    {
        parent::__construct();

        $this->row = $row;
        $this->column = $column;
        $this->available = true;
    }
}
