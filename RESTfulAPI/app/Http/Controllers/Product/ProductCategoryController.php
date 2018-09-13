<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Category;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
        //Relação de N pra N
        /**
         * attach = adiciona uma category, mas tbm adiciona repetidamente
         * sync = adiciona uma category e remove todas as outras
         * sycnWithoutDetaching = adiciona uma category, sem remover as outras e NÃO adiciona repetidamente se enviar a mesma category
         */
        //attach, sync, syncWithoutDetaching
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->find($category->id)) {
            return $this->errorResponse("A categoria especificada não é uma categoria deste produto", 404);
        }

        $product->categories()->detach($category->id);

        return $this->showAll($product->categories);
    }
}
