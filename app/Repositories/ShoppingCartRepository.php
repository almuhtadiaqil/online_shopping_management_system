<?php
namespace App\Repositories;

use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShoppingCartRepository extends BaseRepository
{
    protected Product $product_model;

    protected Transaction $transaction;

    public function __construct(
        ShoppingCart $model,
        Product $product_model,
        Transaction $transaction
    ) {
        $this->model = $model;
        $this->product_model = $product_model;
        $this->transaction = $transaction;
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        try {
            DB::beginTransaction();
            $products = $request->products;
            foreach ($products as $key => $value) {
                $product = $this->product_model
                    ->query()
                    ->where('id', $value['product_id'])
                    ->first();
                if (!$product->exists()) {
                    throw new Exception(
                        'Product id' . $value['product_id'] . ' is not exists',
                        400
                    );
                }
                if ($product->stock < $value['quantity']) {
                    throw new Exception(
                        'the purchase quantity ' .
                            $product->name .
                            ' exceeds the available stock',
                        400
                    );
                }
                $this->model->query()->create([
                    'user_id' => $user->id,
                    'product_id' => $value['product_id'],
                    'quantity' => $value['quantity'],
                ]);
            }
            DB::commit();
            return $products;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function checkout(Request $request)
    {
        try {
            $ids = $request->shopping_cart_id;
            $user = auth()->user();
            DB::beginTransaction();
            $total_amount = 0;
            foreach ($ids as $key => $id) {
                $cart = $this->model->query()->find($id);
                $product = $this->product_model
                    ->query()
                    ->find($cart->product_id);
                $total_amount += $cart->quantity * $product->price;
                $product->stock = $product->stock - $cart->quantity;
                $product->save();
                $cart->is_checkout = true;
                $cart->checkout_date = now();
                $cart->save();
            }
            $this->transaction->query()->create([
                'user_id' => $user->id,
                'total_amount' => $total_amount,
                'transaction_date' => now(),
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
