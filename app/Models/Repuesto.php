<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    protected $table = 'repuestos';
    protected $primaryKey = 'cod_repuestos';
    public $incrementing = false;    
    protected $keyType = 'string';
    public $timestamps = false;
 
    protected $fillable = [
        'nom_rep',          // Nombre del repuesto (único)
        'pre_rep',          // Precio unitario (NUEVO - antes no existía en repuestos)
        'stock',            // Stock actual (antes se llamaba "cant_rep")
        'cod_categoria_rep',// FK a categorias_repuestos (antes era string "cat_rep")
        // Parámetros de teoría de inventarios (algunos existían antes con nombres similares):
        'dda_rep',          // Demanda promedio
        'dan_rep',          // Demanda anual
        'cmi_rep',          // Costo mínimo (antes existía como cmi_rep)
        'cse_rep',          // Cantidad de seguridad (antes existía como cse_rep)
        'nre_rep',          // Nivel de reorden (antes existía como nre_rep)
        'cop_rep',          // Costo de pedido (antes como cop_rep)
        'cma_rep',          // Cantidad máxima (antes como cma_rep)
        'tle_rep',          // Tiempo de entrega (nuevo nombre)
        'cor_rep',          // Costo de oportunidad o similar
        'cal_rep',          // Cálculo auxiliar
        // ELIMINADOS: cod_inv, cat_rep (string), img_rep, est_rep, mar_rep, mod_rep, fma_rep, fsa_rep
    ];
 
    // =========================================================
    // ACCESSOR: estado calculado del stock
    // =========================================================
 
    /**
     * Antes existía "est_rep" como campo en BD.
     * Ahora se calcula dinámicamente comparando stock con umbrales.
     * Úsalo en vistas como $repuesto->estado_stock
     */
    public function getEstadoStockAttribute(): string
    {
        if ($this->stock === 0) return 'AGOTADO';
        if ($this->stock <= ($this->cse_rep ?? 0)) return 'POR AGOTARSE';
        if ($this->stock <= ($this->nre_rep ?? 0)) return 'NIVEL DE REORDEN';
        return 'EN ALMACÉN';
    }
 
    // =========================================================
    // RELACIONES
    // =========================================================
 
    public function categoria()
    {
        return $this->belongsTo(CategoriaRepuesto::class, 'cod_categoria_rep', 'cod_categorias');
    }
 
    /**
     * Mantenimientos donde se usó este repuesto [5FN].
     */
    public function mantenimientos()
    {
        return $this->belongsToMany(
            Mantenimiento::class,
            'mantenimiento_repuestos',
            'cod_repuestos',
            'cod_mantenimientos'
        )->withPivot('cantidad');
    }
 
    /**
     * Solicitudes de repuesto realizadas por técnicos.
     */
    public function solicitudesRepuesto()
    {
        return $this->hasMany(SolicitudRepuesto::class, 'cod_repuestos_sol', 'cod_repuestos');
    }
 
    /**
     * ELIMINADAS: inventario() ya no existe como tabla separada
     */
}