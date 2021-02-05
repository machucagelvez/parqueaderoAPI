<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class APIController extends ResourceController
{
    protected $modelName = 'App\Models\ConductorModelo';
    protected $format    = 'json';

    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    public function resgistrarConductor()
    {
        //1. Recibir los datos del conductor desde elcliente
        $idConductor = $this->request->getPost('idConductor');
        $nombre = $this->request->getPost('nombre');
        $telefono = $this->request->getPost('telefono');
        $idContrato = $this->request->getPost('idContrato');

        //2. Armar un arreglo asociativo, donde las claves serán 
        //los nombres de la columnas o atributos de la tabla con 
        //los datos que llegan de la petición
        $datosEnvio = array(
            "idConductor"=>$idConductor,
            "nombre"=>$nombre,
            "telefono"=>$telefono,
            "idContrato"=>$idContrato,
        );

        //3. Ejecutamos validación y agregamos el registro
        if ($this->validate('conductor')) {
            $this->model->insert($datosEnvio);
            $mensaje = array('estado'=>true,'mensaje'=>"Registro agregado con exito");
            return $this->respond($mensaje);

        }else {
            $validation =  \Config\Services::validation();
            return $this->respond($validation->getErrors(),400);
            
        }        
    }

    public function editarConductor($id)
    {
        //1. Recibir los datos que llegan de la petición
        $datosPeticion = $this->request->getRawInput();
        
        //2. Obtener solo los datos que deseo editar
        $nombre = $datosPeticion["nombre"];
        $telefono = $datosPeticion["telefono"];

        //3. Creamos un arreglo asociativo con los datos para enviar al modelo
        $datosEnvio = array(
            "nombre"=>$nombre,
            "telefono"=>$telefono
        );

        //4. Validamos y ejecutamos la operación en BD
        if ($this->validate('conductorPUT')) {
            $this->model->update($id, $datosEnvio);
            $mensaje = array('estado'=>true,'mensaje'=>"Registro editado con exito");
            return $this->respond($mensaje);

        }else {
            $validation =  \Config\Services::validation();
            return $this->respond($validation->getErrors(),400);
            
        } 
    }

    public function eliminarConductor($id)
    {
        //1. Ejecutar la operación de delete en BD
        $consulta = $this->model->where('idConductor',$id)->delete();
        $filasAfectadas = $consulta->connID->affected_rows;
        //connID->affected_rows sale de lo que se guarda en $consulta

        //2. Validar si el registro a elimnar existe
        if ($filasAfectadas==1) {
            $mensaje = array('estado'=>true,'mensaje'=>"Registro eliminado con exito");
            return $this->respond($mensaje);
            
        }else {
            $mensaje = array('estado'=>false,'mensaje'=>"El conductor a eliminar no se encontró en BD");
            return $this->respond($mensaje,400);
        }
    }
}