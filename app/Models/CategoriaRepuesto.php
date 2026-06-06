<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
// =====================================================================
// CATEGORIA REPUESTO [NUEVO - 4FN]
// =====================================================================
// Antes: la categoría era un campo string dentro de "repuestos" (cat_rep).
// Ahora: es una tabla separada para eliminar la dependencia multivaluada.
class CategoriaRepuesto extends Model
{
    protected $table = 'categorias_repuestos';
    protected $primaryKey = 'cod_categorias';
    public $timestamps = false;
 
    protected $fillable = ['nom_cat'];
 
    public function repuestos()
    {
        return $this->hasMany(Repuesto::class, 'cod_categoria_rep', 'cod_categorias');
    }
}