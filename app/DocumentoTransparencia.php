<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoTransparencia extends Model
{
    //
    protected $table = 'documentotransparencia';
    protected $fillable = ['iduser', 'nombre', 'descripcion', 'archivo', 'fecha', 'nombrearchivo'];

    public function usuario()
    {
        return $this->hasOne('App\User');
    }
}
