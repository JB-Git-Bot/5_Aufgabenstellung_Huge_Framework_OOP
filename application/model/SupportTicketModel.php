<?php

class SupportTicketModel
{
    public static function create($userId, $subject)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO support_tickets (user_id, subject)
                VALUES (:user_id, :subject)";

        $query = $database->prepare($sql);
        $query->execute([
            ':user_id' => (int)$userId,
            ':subject' => $subject
        ]);
    }

    public static function getByUser($userId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * 
                FROM support_tickets
                WHERE user_id = :user_id
                ORDER BY created_at DESC";

        $query = $database->prepare($sql);
        $query->execute([':user_id' => (int)$userId]);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getById($ticketId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * 
                FROM support_tickets 
                WHERE id = :id 
                LIMIT 1";

        $query = $database->prepare($sql);
        $query->execute([':id' => (int)$ticketId]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

    public static function userCanAccess($ticket, $userId)
    {
        return $ticket && (int)$ticket->user_id === (int)$userId;
    }

    public static function getAll()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM support_tickets ORDER BY created_at DESC";
        $query = $database->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public static function assignAdmin($ticketId, $adminId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE support_tickets
                SET assigned_admin_id = :admin_id, status = 'assigned'
                WHERE id = :id";

        $query = $database->prepare($sql);
        $query->execute([
            ':admin_id' => (int)$adminId,
            ':id' => (int)$ticketId
        ]);
    }
}
