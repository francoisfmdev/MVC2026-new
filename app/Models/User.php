<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

/**
 * Compte utilisateur (authentification). Pas de CRUD public.
 * Ne jamais envoyer password_hash à Twig ni en JSON.
 */
final class User extends Model
{
    protected static string $table = 'users';

    public static function membersForTeam(int $teamId){

        return self::query('
        SELECT u.id, u.username, u.email, u.role, j.job
        FROM users u
        INNER JOIN userteam ut ON ut.user_id = u.id
        LEFT JOIN jobs j ON j.id = u.job_id
        WHERE ut.team_id = ?
        ORDER BY u.username ASC
    ',[$teamId]);
    }

    public static function availableUsersForTeam(int $teamId){
      return self::query(
            '
                SELECT users.id, users.username, users.email
                FROM users
                WHERE users.id NOT IN (
                    SELECT userteam.user_id
                    FROM userteam
                    WHERE userteam.team_id = ?
                )
                ORDER BY users.username ASC
            ',
            [$teamId]
        );
    }
    public static function getUserAndJob(){

            return self::query("SELECT
                                u.id,
                                u.username,
                                u.email,
                                u.created_at,
                                u.role,
                                j.job,
                                u.updated_at
                                FROM users u
                                LEFT JOIN jobs j ON j.id = u.job_id
                                ORDER BY u.created_at DESC;");

        
    }
}
