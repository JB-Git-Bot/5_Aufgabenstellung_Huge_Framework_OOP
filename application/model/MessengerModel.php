<?php

class MessengerModel
{
    public static function getAllUsersExcept($currentUserId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL sp_messenger_get_all_users_except(:id)";
        $q = $db->prepare($sql);
        $q->execute([':id' => $currentUserId]);

        $rows = $q->fetchAll();
        $q->closeCursor();

        return $rows;
    }

    public static function sendMessage($senderId, $receiverId, $content)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL sp_messenger_send_message(:s, :r, :c)";
        $q = $db->prepare($sql);
        $q->execute([
            ':s' => $senderId,
            ':r' => $receiverId,
            ':c' => $content
        ]);

        $row = $q->fetch();
        $q->closeCursor();

        return $row && (int)$row->affected_rows === 1;
    }

    public static function getConversation($currentUserId, $otherUserId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL sp_messenger_get_conversation(:me, :other)";
        $q = $db->prepare($sql);
        $q->execute([
            ':me' => $currentUserId,
            ':other' => $otherUserId
        ]);

        $rows = $q->fetchAll();
        $q->closeCursor();

        return $rows;
    }

    public static function markAsReadFromUser($currentUserId, $otherUserId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL sp_messenger_mark_as_read_from_user(:me, :other)";
        $q = $db->prepare($sql);
        $q->execute([
            ':me' => $currentUserId,
            ':other' => $otherUserId
        ]);

        $q->fetch();
        $q->closeCursor();
    }

    public static function countUnread($currentUserId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL sp_messenger_count_unread(:me)";
        $q = $db->prepare($sql);
        $q->execute([':me' => $currentUserId]);

        $row = $q->fetch();
        $q->closeCursor();

        return $row ? (int)$row->cnt : 0;
    }

    public static function getUserIdByUsername($username)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL sp_messenger_get_user_id_by_username(:name)";
        $q = $db->prepare($sql);
        $q->execute([':name' => $username]);

        $row = $q->fetch();
        $q->closeCursor();

        return $row ? (int)$row->user_id : 0;
    }
}
