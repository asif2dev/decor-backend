<?php

namespace App\Http\DTO;

class ProfessionalFull extends Professional
{
    public string $services;
    public string $categories;
    public string $phone1;
    public string $phone2;
    public string $latLng;
    public string $fullAddress;
    public string $workScope;
    public array $social;
}
