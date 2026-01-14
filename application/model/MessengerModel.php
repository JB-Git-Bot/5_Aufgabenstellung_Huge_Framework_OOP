<?php

class MessengerModel
{
    public static function getAllUsersExcept($currentUserId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name
                FROM users
                WHERE user_id != :id
                AND user_active = 1
                AND user_deleted = 0
                ORDER BY user_name ASC";

        $q = $db->prepare($sql);
        $q->execute([':id' => $currentUserId]);

        return $q->fetchAll();
    }


    public static function sendMessage($senderId, $receiverId, $content)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO messages (sender_id, receiver_id, content, is_read, created_at)
                VALUES (:s, :r, :c, 0, NOW())";
        $q = $db->prepare($sql);
        return $q->execute([':s' => $senderId, ':r' => $receiverId, ':c' => $content]);
    }

    public static function getConversation($currentUserId, $otherUserId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        // Alle Nachrichten zwischen zwei Usern (hin + zurück)
        $sql = "SELECT m.id, m.sender_id, m.receiver_id, m.content, m.created_at,
                       u.user_name AS sender_name
                FROM messages m
                JOIN users u ON u.user_id = m.sender_id
                WHERE (m.sender_id = :me AND m.receiver_id = :other)
                   OR (m.sender_id = :other AND m.receiver_id = :me)
                ORDER BY m.created_at ASC, m.id ASC";
        $q = $db->prepare($sql);
        $q->execute([':me' => $currentUserId, ':other' => $otherUserId]);

        return $q->fetchAll();
    }

    public static function markAsReadFromUser($currentUserId, $otherUserId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        // Alles als gelesen markieren, was der andere an mich geschickt hat
        $sql = "UPDATE messages
                SET is_read = 1
                WHERE receiver_id = :me AND sender_id = :other AND is_read = 0";
        $q = $db->prepare($sql);
        $q->execute([':me' => $currentUserId, ':other' => $otherUserId]);
    }

    public static function countUnread($currentUserId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT COUNT(*) AS cnt
                FROM messages
                WHERE receiver_id = :me AND is_read = 0";
        $q = $db->prepare($sql);
        $q->execute([':me' => $currentUserId]);

        return (int) $q->fetch()->cnt;
    }

    public static function getUserIdByUsername($username)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id FROM users WHERE user_name = :name LIMIT 1";
        $q = $db->prepare($sql);
        $q->execute([':name' => $username]);

        $row = $q->fetch();
        return $row ? (int)$row->user_id : 0;
    }


}
