<?php
abstract class Service {
    public function __construct()
    {
        $this->Trig();
    }
    abstract function Trig();
}

