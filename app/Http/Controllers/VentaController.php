<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;

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
                return [
                    'nombre' => $producto->nombre,
                    'cantidad' => $producto->cantidad,
                    'valor_unitario' => $producto->precio,
                    'iva' => $producto->iva,
                    'valor_total' => $producto->precio_con_iva()
                ];
            });
            $valor_total_venta = round($productos->sum('valor_total'), 2);
            return [
                'numero_venta' => $venta->id,
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVentaRequest $request)
    {
        //
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
            return [
                'nombre' => $producto->nombre,
                'cantidad' => $producto->cantidad,
                'valor_unitario' => $producto->precio,
                'iva' => $producto->iva,
                'valor_total' => $producto->precio_con_iva()
            ];
        });
        $valor_total_venta = round($productos->sum('valor_total'), 2);
        $cliente = $venta->cliente;
        $venta = [
            'numero_venta' => $venta->id,
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
        //
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
