<?php

namespace App\Enums\History;

enum ActionEnum: string
{
    case View = 'view';
    case Edit = 'edit';
    case Delete = 'delete';
    case Restore = 'restore';
    case HardDelete = 'hard delete';
}
