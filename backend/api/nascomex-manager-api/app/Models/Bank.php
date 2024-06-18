<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = ['bank', 'agency', 'number', 'name'];

    public static function createBank(array $data): self
    {
        return self::create($data);
    }

    public static function updateBank(int $id, array $data): self
    {
        $bank = self::findOrFail($id);
        $bank->update($data);

        return $bank;
    }
}
?>
