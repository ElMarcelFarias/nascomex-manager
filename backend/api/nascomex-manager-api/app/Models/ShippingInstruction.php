<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
                            'exporter', 'att', 'imports_id', 'volumes', 'ship', 'harbors_id', 'data', 'invoices', 'thc', 'BL', 'office_fee', 'doc_bank', 'sda', 
                            'origem', 'divergence', 'transport_docs', 'discount_installment', 'ship_transfer', 'installment_loan', 'banks_id', 'enterprise_name', 'enterprise_cnpj'
                        ];

    public static function createShippingInstruction(array $data): self
    {
    return self::create($data);
    }

    public static function updateShippingInstruction(int $id, array $data): self
    {
    $shippingInstruction = self::findOrFail($id);
    $shippingInstruction->update($data);

    return $shippingInstruction;
    }
}
