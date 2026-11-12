<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

/**
 * Liaison simple entre un utilisateur et une equipe.
 */
final class UserTeam extends Model
{
    protected static string $table = 'userteam';
}
