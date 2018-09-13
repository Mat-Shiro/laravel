<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Transaction;

class ProductBuyerTransactionController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request, $rules);

        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse("O comprador deve ser diferente do vendedor", 409);
        }

        if (!$buyer->isVerified()) {
            return $this->errorResponse("O comprador deve ser um usuário verificado", 409);
        }

        if (!$product->seller->isVerified()) {
            return $this->errorResponse("O vendedor deve ser um usuário verificado", 409);
        }

        if (!$product->isAvailable()) {
            return $this->errorResponse("O produto não está disponível", 409);
        }

        if ($product->quantity < $request->quantity) {
            return $this->errorResponse("O produto não tem unidades suficientes para a transação", 409);
        }

        /**
         * Garante que o produto tem unidades suficientes para uma transação,
         * mesmo que aconteçam ao mesmo tempo por diferentes usuários,
         * caso não haja, ele faz rollback para o estado anterior
         */
        return DB::transaction(function() use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction, 201);
        });
    }
}
