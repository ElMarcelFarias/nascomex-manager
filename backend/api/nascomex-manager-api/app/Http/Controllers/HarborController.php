<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Harbor;
use App\Models\ShippingInstruction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class HarborController extends Controller
{
    public function index() 
    {
        try {
            $harbors = Harbor::all();

            return response()->json([
                'harbors' => $harbors,
                'message' => 'Lista de todos os portos',
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
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:255'
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
            $harbor = Harbor::createHarbor($request->all());

            return response()->json([
                'message' => 'Porto cadastrado com sucesso!',
                'code' => 201,
                'data' => $harbor
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
            $harbor = Harbor::findOrFail($id);

            // Verificar se existe alguma shipping instruction relacionada
            if (ShippingInstruction::where('banks_id', $id)->exists()) {
                return response()->json([
                    'message' => 'Não foi possível deletar este Porto, pois ele está referenciado em instruções de envio.',
                    'code' => 400
                ], 400);
            }

            $harbor->delete();

            return response()->json([
                'message' => 'Porto deletado com sucesso!',
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
            $harbor = Harbor::findOrFail($id);

            return response()->json([
                'harbor' => $harbor,
                'message' => "Registro do porto {$id}",
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
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:255'
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
            $harbor = Harbor::updateHarbor($id, $request->all());

            return response()->json([
                'message' => 'Porto alterado com sucesso!',
                'code' => 200,
                'data' => $harbor
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
