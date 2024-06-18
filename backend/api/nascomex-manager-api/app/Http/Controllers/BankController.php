<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\ShippingInstruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class BankController extends Controller
{
    public function index() 
    {
        try {
            $banks = Bank::all();

            return response()->json([
                'banks' => $banks,
                'message' => 'Lista de todos os bancos',
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
            'bank' => 'required|string|max:255',
            'agency' => 'required|string|max:255',
            'number' => 'required|integer',
            'name' => 'required|string|max:255'
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
            $bank = Bank::createBank($request->all());

            return response()->json([
                'message' => 'Banco cadastrado com sucesso!',
                'code' => 201,
                'data' => $bank
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
            $bank = Bank::findOrFail($id);

            // Verificar se existe alguma shipping instruction relacionada
            if (ShippingInstruction::where('banks_id', $id)->exists()) {
                return response()->json([
                    'message' => 'Não foi possível deletar este Banco, pois ele está referenciado em instruções de envio.',
                    'code' => 400
                ], 400);
            }

            $bank->delete();

            return response()->json([
                'message' => 'Banco deletado com sucesso!',
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
            $bank = Bank::findOrFail($id);

            return response()->json([
                'bank' => $bank,
                'message' => "Registro do banco {$id}",
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
            'bank' => 'required|string|max:255',
            'agency' => 'required|string|max:255',
            'number' => 'required|integer',
            'name' => 'required|string|max:255'
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
            $bank = Bank::updateBank($id, $request->all());

            return response()->json([
                'message' => 'Banco alterado com sucesso!',
                'code' => 200,
                'data' => $bank
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
