<?php

class SupportMessageModel
{
    public static function create($ticketId, $senderUserId, $message)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO support_messages (ticket_id, sender_user_id, message)
                VALUES (:ticket_id, :sender_user_id, :message)";

        $query = $database->prepare($sql);
        $query->execute([
            ':ticket_id' => (int)$ticketId,
            ':sender_user_id' => (int)$senderUserId,
            ':message' => $message
        ]);
    }

    public static function getByTicketId($ticketId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM support_messages
                WHERE ticket_id = :ticket_id
                ORDER BY created_at ASC";

        $query = $database->prepare($sql);
        $query->execute([':ticket_id' => (int)$ticketId]);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}
