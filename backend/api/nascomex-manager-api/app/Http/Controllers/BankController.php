<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index() {
        $banks = Bank::all();
        
        return response()->json(
            [
                'banks' => $banks,
                'message' => 'Banks',
                'code' => 200
            ]
        );
    }

    public function create(Request $request) {
        $bank = new Bank();
        $bank->bank = $request->bank;
        $bank->agency = $request->agency;
        $bank->number = $request->number;
        $bank->name = $request->name;
        $bank->save();

        return response()->json([
            'message' => 'Banco cadastrado com sucesso!',
            'code' => 200
        ]);

    }

    public function destroy($id){
        $bank = Bank::find($id);
        if($bank) {
            $bank->delete();
            return response()->json([
                'message' => 'Banco deletado com sucesso!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Registro não encontrado!',
                'code' => 404
            ]);
        }
    }

    public function show($id) {
        $bank = Bank::find($id);
        if($bank) {
            return response()->json([
                'bank' => $bank,
                'message' => 'Registro do banco {$id}',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'bank' => $bank,
                'message' => 'Registro não encontrado!',
                'code' => 404
            ]);
        }
    }

    function update(Request $request, $id){
        $bank = Bank::where('id', $id)->first();
        $bank->bank = $request->bank;
        $bank->agency = $request->agency;
        $bank->number = $request->number;
        $bank->name = $request->name;
        $bank->save();
        return response()->json([
            'message' => 'Banco alterado com sucesso!',
            'code' => 200
        ]);
    }


}
