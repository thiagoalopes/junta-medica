<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissoesModel extends Model
{
    use HasFactory;

    protected $table = 'tb_permissoes';
    public $timestamps = true;

    protected $fillable = [
        'cpf',
        'f_admin',
        'f_desenvolvedor',
        'f_usuario',
        'f_medico',
    ];

    function colunas()
    {
        return [
            'f_admin',
            'f_desenvolvedor',
            'f_usuario',
            'f_medico',
        ];
    }
}
