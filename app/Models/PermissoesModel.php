<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissoesModel extends Model
{
    use HasFactory;

    protected $table = 'permissoes';
    public $timestamps = true;

    protected $fillable = [
        'cpf',
        'f_admin',
        'f_desenvolvedor',
        'f_usuario',
        'f_medico',
    ];
}
