<?php

namespace App\Enums;

enum StockMovementType: string
{
    case Entry = 'entrada';
    case Sale = 'venta';
    case Return = 'devolucion';
    case Cancellation = 'cancelacion';
    case Adjustment = 'ajuste';
}