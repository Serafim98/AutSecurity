<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $fillable = ['Cargo', 'NomeCompleto','DatadeNascimento','CPF','RG','Endereco', 'Telefone', 'Email', 'Banco', 'Agencia', 'Conta', 'seguradora_id'];

    public function seguradora(){
        return $this->belongsTo(Seguradora::class);
    }


}
