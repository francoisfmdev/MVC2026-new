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

        return  self::query(
            '
                SELECT users.id, users.username, users.email, users.role, users.job
                FROM users
                INNER JOIN userteam ON userteam.user_id = users.id
                WHERE userteam.team_id = ?
                ORDER BY users.username ASC
            ',
           
        );
    }

    public static function availableUsersForTeam(array $teamId){
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
        return  self::table()
            ->select('id', 'username', 'email', 'created_at','role','job', 'updated_at')
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
