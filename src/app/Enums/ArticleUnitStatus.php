<?php

namespace App\Enums;

enum ArticleUnitStatus: string
{
    case InStock = 'en_stock';
    case Sold = 'vendido';
    case Returned = 'devuelto';
    case Repair = 'reparado';
    case Damaged = 'dañado';
}