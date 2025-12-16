<?php

namespace App;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CASHIER = 'cashier';
    case OWNER = 'owner';
}
