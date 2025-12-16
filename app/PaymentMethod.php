<?php

namespace App;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case QRIS = 'qris';
    case EWALLET = 'ewallet';
}
