<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Decimal;
use function PHPUnit\Framework\isTrue;

class Seat /*extends Model*/
{
//    protected $table = null;
    public int $id;
    public int $event_id;
    public int $sector_id;
    public int $row;
    public int $column;
    public bool $available;
    public Decimal $price;

    public function __construct(int $eventId, int $sectorId, int $row, int $column, $price)
    {
        $this->event_id = $eventId;
        $this->sector_id = $sectorId;
        $this->row = $row;
        $this->column = $column;
        $this->available = $this->isAvailable();
        $this->price = new Decimal($price);
    }

    public function isAvailable(): bool
    {
        return !Ticket::where('event_id', $this->event_id)
            ->where('sector_id', $this->sector_id)
            ->where('row', $this->row)
            ->where('column', $this->column)
            ->whereIn('status', ['purchased', 'reserved'])
            ->exists();
    }
}
