<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'status'];

    public static function createImport(array $data): self
    {
        return self::create($data);
    }

    public static function updateImport(int $id, array $data): self
    {
        $import = self::findOrFail($id);
        $import->update($data);

        return $import;
    }
}
