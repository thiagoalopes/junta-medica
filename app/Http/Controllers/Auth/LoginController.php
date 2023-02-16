<?php

namespace App\Http\Controllers\Auth;

use App\Models\Usuario;
use App\Models\PermissoesModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Passport\Exceptions\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use League\OAuth2\Server\Exception\OAuthServerException as ExceptionOAuthServerException;

class LoginController extends AccessTokenController
{
    public function issueToken(ServerRequestInterface $request)
    {
        try {

            if(!isset($request->getParsedBody()['username']))
            {
                throw new ExceptionOAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);
            }

            $permissoes = new PermissoesModel();


            $usuario = Usuario::where('cpf', $request->getParsedBody()['username'])->first();

            $permissoes = PermissoesModel::select($permissoes->colunas())
            ->where('cpf', $usuario->cpf )
            ->first()->toArray();

            $permissoeAtribuidas = [];

            foreach ($permissoes as $key => $permissao) {
                if($permissoes[$key] == 1)
                {
                    array_push($permissoeAtribuidas, $key);
                }
            }

            $usuario->permissoes = $permissoeAtribuidas;

            //generate token
            $tokenResponse = parent::issueToken($request);
            //dd($tokenResponse);
            //convert response to json string
            $content = $tokenResponse->getContent();

            //convert json to array
            $data = json_decode($content, true);

            if(isset($data["error"]))
                throw new ExceptionOAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);


            //add access token to user
            $data['data'] = $usuario;

            return response()->json($data);
        }
        catch (ModelNotFoundException $e) { // email notfound
            //return error message
            return response(["message" => "User not found"], 500);
        }
        catch (OAuthServerException $e) { //password not correct..token not granted
            //return error message
            return response(["message" => "The user credentials were incorrect.', 6, 'invalid_credentials"], 500);
        }
        catch (Exception $e) {
            ////return error message
            return response(["message" => "Internal server error"], 500);
        }
    }
}
