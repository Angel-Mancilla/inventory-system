<?php

namespace App\Enums;

enum UserStatus: string
{
    case Activo = 'active';
    case Inactivo = 'inactive';
}