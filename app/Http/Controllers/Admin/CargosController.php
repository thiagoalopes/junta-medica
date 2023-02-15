<?php

namespace App\Http\Controllers\Admin;

use App\Models\CargosModel;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Exceptions\EntityNotFoundExpecion;
use App\Exceptions\OrderByValueInvalidException;

class CargosController extends Controller
{

    function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::allows('f_admin', Auth::user()))
        {
            try
            {
                $perPage = $request->has('perPage')?$request->input('perPage'):15;
                $order = $request->has('order')?$request->input('order'):'asc';

                return CargosModel::orderBy('descricao', $order)
                            ->simplePaginate($perPage);

            }
            catch(InvalidArgumentException $e)
            {
                throw new OrderByValueInvalidException('Os parâmetros order, perPage ou page foram informados de forma inválida.', $e, $request);
            }
        }
        return response()->json(null, 403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::allows('f_admin', Auth::user()))
        {
            $cargo = new CargosModel();
            $validated = $request->validate($cargo->rules(), $cargo->messages());
            $cargo = $cargo->create($validated);

            return response()->json(null, 201, ['location'=>route('cargos.show',['id'=>$cargo->id])]);
        }
        return response()->json(null, 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(Gate::allows('f_admin', Auth::user()))
        {
            $cargo = CargosModel::where('id', $id)->first();

            if(!$cargo)
            {
                throw new EntityNotFoundExpecion("Cargo não encontrado.", null, $request);
            }

            return response()->json($cargo);
            if(Gate::allows('f_admin', Auth::user()));
        }
        return response()->json(null, 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Gate::allows('f_admin', Auth::user()))
        {
            $cargo = CargosModel::where('id', $id)->first();

            if(!$cargo)
            {
                throw new EntityNotFoundExpecion("Cargo não encontrado.", null, $request);
            }

            $validated = $request->validate($cargo->rules(), $cargo->messages());
            $cargo->update($validated);

            return response()->json($cargo);
        }
        return response()->json(null, 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
