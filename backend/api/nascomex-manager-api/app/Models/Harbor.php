<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harbor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'state'];

    public static function createHarbor(array $data): self
    {
        return self::create($data);
    }

    public static function updateHarbor(int $id, array $data): self
    {
        $harbor = self::findOrFail($id);
        $harbor->update($data);

        return $harbor;
    }
}
