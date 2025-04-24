<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Decimal;

class Seat /*extends Model*/
{
//    protected $table = null;
    public int $row;
    public int $column;
    public bool $available;
    public Decimal $price;

    public function __construct(int $row, int $column, $price)
    {
//        parent::__construct();

        $this->row = $row;
        $this->column = $column;
        $this->available = true;
        $this->price = new Decimal($price);
    }
}
