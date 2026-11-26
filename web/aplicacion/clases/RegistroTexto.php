<?php 
class RegistroTexto{

    private string $_cadena;
    private datetime $_fecha_hora;

    public function __construct(string $cadena)
    {
        $this->_cadena = $cadena;
        $this->_fecha_hora = new DateTime();
    }

    public function getCadena(): string{
        return $this->_cadena;
    }

    public function getFechaHora(): DateTime{
        return $this->_fecha_hora;
    }

    public function __set(string $name, mixed $value)
    {
        throw new \Exception("No existe la propiedad $name");
    }

    public function __get(string $name)
    {
        throw new \Exception("No existe la propiedad $name");
    }

    public function __isset(string $name)
    {
        throw new \Exception("No existe la propiedad $name");
    }

    public function __unset(string $name)
    {
        throw new \Exception("No existe la propiedad $name");
    }
}