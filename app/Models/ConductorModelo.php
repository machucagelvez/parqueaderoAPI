<?php namespace App\Models;

use CodeIgniter\Model;

class ConductorModelo extends Model
{
    protected $table = 'conductor';
    protected $primaryKey = 'idConductor';
    protected $allowedFields = ['idConductor', 'nombre', 'telefono', 'idContrato'];
}