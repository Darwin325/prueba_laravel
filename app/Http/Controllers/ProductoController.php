<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Vendedor;
use Illuminate\Support\Facades\Storage;

class ProductoController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(Producto::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductoRequest $request)
    {
        try {
            $rutaArchivo = $request->file('foto')->store('productos');
            $data = $request->all();
            $data['foto'] = $rutaArchivo;
            $producto = Producto::create($data);
            return $this->showOne($producto);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        return $this->showOne($producto);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductoRequest  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        try {
            if ($request->has('nombre')) {
                $producto->nombre = $request->nombre;
            }
            if ($request->has('descripcion')) {
                $producto->descripcion = $request->descripcion;
            }
            if ($request->has('foto')) {
                Storage::delete($producto->foto);
                $rutaArchivo = $request->file('foto')->store('productos');
                $producto->foto = $rutaArchivo;
            }
            if ($request->has('cantidad')) {
                $producto->cantidad = $request->cantidad;
            }
            if ($request->has('precio')) {
                $producto->precio = $request->precio;
            }
            if ($request->has('iva')) {
                $producto->iva = $request->iva;
            }
            if ($request->has('vendedor_id')) {
                $producto->vendedor_id = $request->vendedor_id;
            }
            if (!$producto->isDirty()){
                return $this->errorResponse('Se debe especificar al menos un valor diferente para poder actualizar', 422);
            }
            $producto->save();
            return $this->showOne($producto);
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        try {
            $producto->delete();
            return $this->showOne($producto);
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
