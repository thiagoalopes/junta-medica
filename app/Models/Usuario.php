<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;



class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tb_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'email', 'senha', 'cpf','matricula','celular'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'senha', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function rules()
    {
        $cpfUnique = $this->id!=null?"unique:tb_usuario,cpf,{$this->id}":"unique:tb_usuario,cpf";
        $emailUnique = $this->id!=null?"unique:tb_usuario,email,{$this->id}":"unique:tb_usuario,email";
        $matriculaUnique = $this->id!=null?"unique:tb_usuario,matricula,{$this->id}":"unique:tb_usuario,matricula";


        return [
            'nome'=>'required|max:128|min:5|string',
            'cpf'=>'required|cpf|'.$cpfUnique,
            'matricula'=>'required|max:15|regex:/^[0-9]+$/|'.$matriculaUnique,
            'email'=>'required|'.$emailUnique,
            'celular'=>'required|celular_com_ddd',
        ];
    }

    public function messages()
    {
        return [
          'required'=>'Campo obrigatório',
          'max'=>'O limite máximo de caracteres é :max',
          'min'=>'O limite mínimo de caracteres é :min',
          'nome.string'=>'O campo só deve conter letras e espaços em branco',
          'matricula.regex'=>'O campo só deve conter números',
          'matricula.unique'=>'Esta matrícula (:input) já foi cadastrada',
          'cpf.unique'=>'Este CPF (:input) já foi cadastrado',
          'email.unique'=>'Este e-mail (:input) já foi cadastrado',

        ];
    }

    function findAndValidateForPassport($username, $password)
    {
        $usuario = $this->where('cpf', $username)->first();

        if($usuario)
        {
           if(Hash::check($password, $usuario->senha))
           {
                return $usuario;
           }

        }
        return null;
    }

    function permissoes()
    {
        return $this->hasOne('App\Models\PermissoesModel', 'cpf', 'cpf');
    }
}
