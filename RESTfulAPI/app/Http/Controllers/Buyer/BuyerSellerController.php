<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()->with('product.seller') //procura um seller passasndo pelas relações entre buyer e transactions, com produtos e vendedores
            ->get() //pega a coleção
            ->pluck('product.seller') //retorna apenas os seller (retira o resto do array do retorno)
            ->unique('id') //retorna apenas uma vez cada seller (mas isso deixa um espaço em branco no retorno)
            ->values(); //recria os indices da coleção (retira os espaços em brancos que seriam seller repetidos)

        return $this->showAll($sellers);
    }
}
