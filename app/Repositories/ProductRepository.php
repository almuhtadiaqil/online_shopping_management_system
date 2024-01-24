<?php
namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository
{
    protected ProductImage $prod_image;

    public function __construct(Product $model, ProductImage $prod_image)
    {
        $this->model = $model;
        $this->prod_image = $prod_image;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->model->fill($request->all());
            $data->save();
            if (
                $request->input('image') != null &&
                $request->input('image') != ''
            ) {
                $this->prod_image->query()->create([
                    'product_id' => $data->id,
                    'image_path' => $request->input('image'),
                ]);
            }
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function update(Request $request, $column, $value)
    {
        try {
            DB::beginTransaction();
            $payload = $request->all();
            $payload = array_filter($payload, function ($value) {
                return $value != '' && $value != null;
            });
            $data = $this->model
                ->query()
                ->where($column, $value)
                ->first();
            $data->fill($payload);
            $data->save();
            if (array_key_exists('image', $payload)) {
                $this->prod_image->query()->updateOrCreate(
                    [
                        'product_id' => $data->id,
                    ],
                    [
                        'product_id' => $data->id,
                        'image_path' => $payload['image'],
                    ]
                );
            }
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
