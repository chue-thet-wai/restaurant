<?php

namespace App\Interfaces;

interface CommonRepositoryInterface 
{
    public function checkPermission($controllerName);  
    public function getStatus(); 
    public function getReservationStatus();  
    public function getAvailableTable($reservation_time,$reservation_date,$reservation_branchId);    
}