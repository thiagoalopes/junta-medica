<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\EntityNotFoundExpecion;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Database\QueryException;
use Psr\Http\Message\ServerRequestInterface;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\Http\Controllers\AccessTokenController;

class LoginController extends AccessTokenController
{
    public function issueToken(ServerRequestInterface $request)
    {
        try
        {
            $grantType = isset($request->getParsedBody()['grant_type'])?$request->getParsedBody()['grant_type']:null;

            if($grantType && $grantType == 'refresh_token')
            {
                $request->s()['refresh_token'] = $request->getCookieParams()['refresh_token'];
            }

            $tokenContent = json_decode(parent::issueToken($request)->getContent(), 1);

            return response()->json(Arr::except($tokenContent, ['refresh_token']))
                ->withCookie(cookie('refresh_token', $tokenContent['refresh_token'], 60,'/', 'localhost'));

        } catch(HandlesOAuthErrors $e)
        {
            return response()->json($e);
        }
        catch(QueryException $e)
        {
            throw new EntityNotFoundExpecion('Invalid Client',$e, 404, $request->getUri()->getPath());
        }
    }
}
