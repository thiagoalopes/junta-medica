<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Usuario;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Models\PermissoesModel;
use App\Exceptions\StoreExpecion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Exceptions\EntityNotFoundExpecion;
use App\Exceptions\OrderByValueInvalidException;

class UsuariosController extends Controller
{

    function __construct() {
        $this->middleware('auth:api');
    }

    public function logged()
    {
        $permissoes = new PermissoesModel();
        $usuarioLogado = Auth::user();

        $permissoes = PermissoesModel::select($permissoes->colunas())
        ->where('cpf', $usuarioLogado->cpf )
        ->first()->toArray();

        $permissoeAtribuidas = [];

        foreach ($permissoes as $key => $permissao) {
            if($permissoes[$key] == 1)
            {
                array_push($permissoeAtribuidas, $key);
            }
        }

        $usuarioLogado->permissoes = $permissoeAtribuidas;
        return response()->json($usuarioLogado);
    }

    public function payload()
    {
        $permissoes = new PermissoesModel();
        $usuarioLogado = Auth::user();

        $permissoes = PermissoesModel::select($permissoes->colunas())
        ->where('cpf', $usuarioLogado->cpf )
        ->first()->toArray();

        $permissoeAtribuidas = [];

        foreach ($permissoes as $key => $permissao) {
            if($permissoes[$key] == 1)
            {
                array_push($permissoeAtribuidas, $key);
            }
        }
        $usuarioLogado->permissoes = $permissoeAtribuidas;
        Arr::except($usuarioLogado, ['id','celular','matricula','email','isbloqueado']);

        return response()->json($usuarioLogado);
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

                return Usuario::with('permissoes')->orderBy('nome', $order)
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
            $usuario = new Usuario();

            $validated = $request->validate($usuario->rules(), $usuario->messages());

            //cria e inclui a senha padrao
            $validated['senha'] = Hash::make($validated['cpf'].'@'.Carbon::now()->format('Y'));

            try
            {
                DB::beginTransaction();
                $usuario = Usuario::create($validated);

                $permissaoModel = PermissoesModel::create(['cpf'=>$validated['cpf']]);

                if($permissaoModel)
                {
                    if($request->has('permissao'))
                    {   $listaPermissao = [];
                        foreach ($request->input('permissao') as $permissao) {
                            $listaPermissao[$permissao] = '1';
                        }

                        $permissaoModel->update($listaPermissao);
                    }
                }

                DB::commit();
                return response()->json($validated);
            }
            catch(Exception $e)
            {
                DB::rollBack();
                throw new StoreExpecion('Ocorreu um erro ao tentar criar um usuário.
                Se o problema persistir informe o administrador do sistema.', $e, $request);
            }
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
            $usuario = Usuario::with('permissoes')->where('id', $id)->first();

            if(!$usuario)
            {
                throw new EntityNotFoundExpecion("Usuario não encontrado.", null, $request);
            }

            return response()->json($usuario);
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
            $usuario = Usuario::where('id', $id)->first();

            if(!$usuario)
            {
                throw new EntityNotFoundExpecion("Usuário não encontrado.", null, $request);
            }

            $validated = $request->validate($usuario->rules(), $usuario->messages());
            $usuario->update($validated);

            return response()->json($usuario);
        }
        return response()->json(null, 403);
    }

    public function updatePermissoesUsuario(Request $request, $id)
    {
        if(Gate::allows('f_admin', Auth::user()))
        {
            $usuario = Usuario::where('id', $id)->first();

            if(!$usuario)
            {
                throw new EntityNotFoundExpecion("Usuário não encontrado.", null, $request);
            }

            $permissaoModel = PermissoesModel::where('cpf', $usuario->cpf)->first();

            if(!$permissaoModel)
            {
                $permissaoModel = PermissoesModel::create(['cpf'=>$usuario->cpf]);
            }

            if($permissaoModel)
            {
                if($request->has('permissao'))
                {
                    $listaPermissao = [];

                    foreach ($permissaoModel->colunas() as $coluna)
                    {
                        if(in_array($coluna, $request->input('permissao')))
                        {
                            $listaPermissao[$coluna] = '1';
                        }
                        else
                        {
                            $listaPermissao[$coluna] = '0';
                        }
                    }
                    $permissaoModel->update($listaPermissao);
                    return response()->json($permissaoModel);
                }            if(Gate::allows('f_admin', Auth::user()));

            }
        }
        return response()->json(null, 403);
    }

    public function disable(Request $request, $id)
    {
        if(Gate::allows('f_admin', Auth::user()))
        {
            $usuario = Usuario::with('permissoes')->where('id', $id)->first();

            if(!$usuario)
            {
                throw new EntityNotFoundExpecion("Usuario não encontrado.", null, $request);
            }

            $usuario->isbloqueado = true;

            return response()->json($usuario);
        }
        return response()->json(null, 403);
    }

    public function enable(Request $request, $id)
    {
        if(Gate::allows('f_admin', Auth::user()))
        {
            $usuario = Usuario::with('permissoes')->where('id', $id)->first();

            if(!$usuario)
            {
                throw new EntityNotFoundExpecion("Usuario não encontrado.", null, $request);
            }

            $usuario->isbloqueado = false;

            return response()->json($usuario);
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
