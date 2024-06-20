<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Import;
use App\Models\ShippingInstruction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ImportController extends Controller
{
    public function index() 
    {
        try {
            $imports = Import::all();

            return response()->json([
                'imports' => $imports,
                'message' => 'Lista de todos os Importadores',
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
            'nome' => 'required|string|max:255',
            'status' => 'required|string|max:255'
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
            $import = Import::createImport($request->all());

            return response()->json([
                'message' => 'Importador cadastrado com sucesso!',
                'code' => 201,
                'data' => $import
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
            $import = Import::findOrFail($id);

            // Verificar se existe alguma shipping instruction relacionada
            if (ShippingInstruction::where('imports_id', $id)->exists()) {
                return response()->json([
                    'message' => 'Não foi possível deletar este Importador, pois ele está referenciado em instruções de envio.',
                    'code' => 400
                ], 400);
            }

            $import->delete();

            return response()->json([
                'message' => 'Importador deletado com sucesso!',
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
            $import = Import::findOrFail($id);

            return response()->json([
                'import' => $import,
                'message' => "Registro do importador {$id}",
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
            'nome' => 'required|string|max:255',
            'status' => 'required|string|max:255'
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
            $import = Import::updateImport($id, $request->all());

            return response()->json([
                'message' => 'Importador alterado com sucesso!',
                'code' => 200,
                'data' => $import
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
