<?php

namespace App\Enums;

enum PurchaseOrderStatus: string
{
    case Pending = 'pendiente';
    case Received = 'recibido';
    case Cancelled = 'cancelado';
}