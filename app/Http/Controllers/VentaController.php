<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use Illuminate\Support\Facades\DB;

class VentaController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = Venta::with('productos')->get()->map(function ($venta) {
            $cliente = $venta->cliente;
            $productos = $venta->productos->map(function ($producto){
                $catidad_vendido = $producto->vendido->cantidad;
                return [
                    'producto_id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'valor_unitario' => $producto->precio,
                    'iva' => $producto->iva,
                    'cantidad_vendido' => $catidad_vendido,
                    'valor_total' => ($producto->precio_con_iva() * $catidad_vendido)
                ];
            });
            $valor_total_venta = round($productos->sum('valor_total'), 2);
            return [
                'venta_id' => $venta->id,
                'numero_venta' => $venta->numero_venta,
                'cliente_id' => $cliente->id,
                'cliente' => $cliente->name,
                'telefono' => $cliente->telefono,
                'email' => $cliente->email,
                'total_venta' => $valor_total_venta,
                'productos' => $productos
            ];
        });
        return $this->showAll($ventas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVentaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreVentaRequest $request)
    {
        DB::beginTransaction();
        try {
            $cantida_registros = DB::table('ventas')->count('*');
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'numero_venta' => $cantida_registros + 1
            ]);
            // Productos a vender
            $productos_venta = $request->productos;
            foreach ($productos_venta as $producto) {
                // Producto a modificar si se puede hacer la venta
                $prod_cambiar = Producto::find($producto['producto_id']);
                if ($producto['cantidad'] > $prod_cambiar->cantidad) {
                    DB::rollBack();
                    return $this->errorResponse([
                        'mensaje' => "Cantidad insuficiente en producto_id: {$prod_cambiar->id}"
                    ], 402);
                }
                $prod_cambiar->cantidad -= $producto['cantidad'];
                $prod_cambiar->save();
            }
            $venta->productos()->attach($productos_venta);
            DB::commit();
            return $this->showOne($venta);
            }catch (\Exception $e){
                DB::rollBack();
                return $this->errorResponse($e->getMessage(), 500);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        $productos = $venta->productos->map(function ($producto){
            $catidad_vendido = $producto->vendido->cantidad;
            return [
                'producto_id' => $producto->id,
                'nombre' => $producto->nombre,
                'valor_unitario' => $producto->precio,
                'iva' => $producto->iva,
                'cantidad_vendido' => $catidad_vendido,
                'valor_total' => ($producto->precio_con_iva() * $catidad_vendido)
            ];
        });
        $valor_total_venta = round($productos->sum('valor_total'), 2);
        $cliente = $venta->cliente;
        $venta = [
            'venta_id' => $venta->id,
            'numero_venta' => $venta->numero_venta,
            'cliente_id' => $cliente->id,
            'cliente' => $cliente->name,
            'telefono' => $cliente->telefono,
            'email' => $cliente->email,
            'total_venta' => $valor_total_venta,
            'productos' => $productos
        ];
        return response()->json(['data' => $venta], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVentaRequest  $request
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVentaRequest $request, Venta $venta)
    {
        try {
            $venta->productos()->update([
                'producto_id' => $request->p
            ]);
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        try {
            $venta->delete();
            return $this->showOne($venta);
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
