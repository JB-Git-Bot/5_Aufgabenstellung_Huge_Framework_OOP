<?php

class MessengerController extends Controller
{
    private function getCurrentUserId(): int
    {
        $me = (int) Session::get('user_id');
        if ($me <= 0) {
            $me = MessengerModel::getUserIdByUsername((string) Session::get('user_name'));
        }
        return $me;
    }

    public function index()
    {
        Auth::checkAuthentication();

        $me = $this->getCurrentUserId();
        $users = MessengerModel::getAllUsersExcept($me);

        $selectedUserId = !empty($users) ? (int) $users[0]->user_id : 0;

        $this->View->render('messenger/index', [
            'users' => $users,
            'selectedUserId' => $selectedUserId
        ]);
    }

    public function conversation()
    {
        Auth::checkAuthentication();
        header('Content-Type: application/json; charset=utf-8');

        $me = $this->getCurrentUserId();
        $other = (int) Request::get('user_id');

        if ($other <= 0) {
            echo json_encode(['ok' => false, 'error' => 'Kein user_id angegeben.']);
            exit;
        }

        MessengerModel::markAsReadFromUser($me, $other);

        $messages = MessengerModel::getConversation($me, $other);
        echo json_encode(['ok' => true, 'messages' => $messages]);
        exit;
    }

    public function send()
    {
        Auth::checkAuthentication();
        header('Content-Type: application/json; charset=utf-8');

        $me = $this->getCurrentUserId();

        $receiverId = (int) Request::post('receiver_id');
        $content = trim((string) Request::post('content'));

        if ($receiverId <= 0 || $content === '') {
            echo json_encode(['ok' => false, 'error' => 'Empfänger oder Text fehlt.']);
            exit;
        }

        if (mb_strlen($content) > 2000) {
            echo json_encode(['ok' => false, 'error' => 'Nachricht ist zu lang.']);
            exit;
        }

        $ok = MessengerModel::sendMessage($me, $receiverId, $content);
        echo json_encode(['ok' => (bool) $ok]);
        exit;
    }

    public function sendUrl()
    {
        Auth::checkAuthentication();

        $me = $this->getCurrentUserId();
        $to = (int) Request::get('to');
        $text = trim((string) Request::get('text'));

        if ($to <= 0 || $text === '') {
            echo "Fehler: to und text sind notwendig";
            exit;
        }

        if ($to === $me) {
            echo "Fehler: Du kannst nicht an dich selbst senden.";
            exit;
        }

        if (mb_strlen($text) > 2000) {
            echo "Fehler: Nachricht ist zu lang.";
            exit;
        }

        MessengerModel::sendMessage($me, $to, $text);
        echo "OK";
        exit;
    }

}
