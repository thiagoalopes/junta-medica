<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargosModel extends Model
{
    use HasFactory;

    protected $table = 'tb_cargos';
    public $timestamps = true;

    protected $fillable = [
        'descricao',
    ];

    public function rules()
    {
        $descricaoUnique = $this->id!=null?"unique:tb_cargos,descricao,{$this->id}":"unique:tb_cargos,descricao";

        return [
            'descricao'=>'required|max:128|min:5|regex:/^[a-z A-Z]+$/|'.$descricaoUnique,
        ];
    }

    public function messages()
    {
        return [
          'descricao.unique'=>'Este cargo já foi cadastrado',
        ];
    }
}
