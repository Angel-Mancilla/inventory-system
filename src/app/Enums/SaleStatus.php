<?php

namespace App\Enums;

enum SaleStatus: string
{
    case Completed = 'completado';
    case Cancelled = 'cancelado';
}