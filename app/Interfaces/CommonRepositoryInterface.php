<?php

namespace App\Interfaces;

interface CommonRepositoryInterface 
{
    public function checkPermission($controllerName);  
    public function getStatus(); 
    public function getReservationStatus();    
}