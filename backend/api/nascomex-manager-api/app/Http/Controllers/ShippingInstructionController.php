<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingInstruction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ShippingInstructionController extends Controller
{
    public function index() 
    {
        try {
            $shippingInstructions = ShippingInstruction::all();

            return response()->json([
                'shippingInstructions' => $shippingInstructions,
                'message' => 'Lista de todos os numerários',
                'code' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar os registros do servidor!',
                'code' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request): JsonResponse
    {
        // Validação dos dados da requisição
        $validator = Validator::make($request->all(), [
            'exporter' => 'required|string|max:255',
            'att' => 'required|string|max:255',
            'imports_id' => 'required|exists:imports,id',
            'volumes' => 'required|string|max:255',
            'ship' => 'required|string|max:255',
            'harbors_id' => 'required|exists:harbors,id',
            'data' => 'required|date',
            'invoices' => 'nullable|string',
            'thc' => 'nullable|numeric|min:0|max:99999999.99',
            'BL' => 'nullable|string|max:255',
            'office_fee' => 'nullable|numeric|min:0|max:99999999.99',
            'doc_bank' => 'nullable|numeric|min:0|max:99999999.99',
            'sda' => 'nullable|string|max:255',
            'origem' => 'nullable|string|max:255',
            'divergence' => 'nullable|string|max:255',
            'transport_docs' => 'nullable|string|max:255',
            'discount_installment' => 'nullable|string|max:255',
            'ship_transfer' => 'nullable|string|max:255',
            'installment_loan' => 'nullable|string|max:255',
            'banks_id' => 'required|exists:banks,id',
            'enterprise_name' => 'nullable|string|max:255',
            'enterprise_cnpj' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors(),
                'code' => 400
            ], 400);
        }

        try {
            // Criação do registro
            $shippingInstructions = ShippingInstruction::createShippingInstruction($request->all());

            return response()->json([
                'message' => 'Numerário cadastrado com sucesso!',
                'code' => 201,
                'data' => $shippingInstructions
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao salvar no servidor!',
                'code' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $shippingInstructions = ShippingInstruction::findOrFail($id);
            $shippingInstructions->delete();

            return response()->json([
                'message' => 'Numerário deletado com sucesso!',
                'code' => 200
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Registro não encontrado!',
                'code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao deletar no servidor!',
                'code' => 500
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $shippingInstruction = ShippingInstruction::findOrFail($id);

            return response()->json([
                'shippingInstruction' => $shippingInstruction,
                'message' => "Registro do numerário {$id}",
                'code' => 200
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Registro não encontrado!',
                'code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar o registro do servidor!',
                'code' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        // Validação dos dados da requisição
        $validator = Validator::make($request->all(), [
            'exporter' => 'required|string|max:255',
            'att' => 'required|string|max:255',
            'imports_id' => 'required|exists:imports,id',
            'volumes' => 'required|string|max:255',
            'ship' => 'required|string|max:255',
            'harbors_id' => 'required|exists:harbors,id',
            'data' => 'required|date',
            'invoices' => 'nullable|string',
            'thc' => 'nullable|numeric|min:0|max:99999999.99',
            'BL' => 'nullable|string|max:255',
            'office_fee' => 'nullable|numeric|min:0|max:99999999.99',
            'doc_bank' => 'nullable|numeric|min:0|max:99999999.99',
            'sda' => 'nullable|string|max:255',
            'origem' => 'nullable|string|max:255',
            'divergence' => 'nullable|string|max:255',
            'transport_docs' => 'nullable|string|max:255',
            'discount_installment' => 'nullable|string|max:255',
            'ship_transfer' => 'nullable|string|max:255',
            'installment_loan' => 'nullable|string|max:255',
            'banks_id' => 'required|exists:banks,id',
            'enterprise_name' => 'nullable|string|max:255',
            'enterprise_cnpj' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors(),
                'code' => 400
            ], 400);
        }

        try {
            // Atualização do registro usando o método estático da Model
            $shippingInstruction = ShippingInstruction::updateShippingInstruction($id, $request->all());

            return response()->json([
                'message' => 'Numerário alterado com sucesso!',
                'code' => 200,
                'data' => $shippingInstruction
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Registro não encontrado!',
                'code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar no servidor!',
                'code' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
