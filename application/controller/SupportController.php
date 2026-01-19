<?php

class SupportController extends Controller
{
    /**
     * Kunde-Bereich: Jeder eingeloggte User darf seine eigenen Tickets sehen.
     */
    private function requireUser()
    {
        Auth::checkAuthentication();
    }

    public function index()
    {
        $this->requireUser();

        $userId = (int) Session::get('user_id');

        $tickets = SupportTicketModel::getByUser($userId);

        $this->View->render('support/index', [
            'tickets' => $tickets
        ]);
    }

    public function create()
    {
        $this->requireUser();

        $userId = (int) Session::get('user_id');
        $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';

        if ($subject !== '') {
            SupportTicketModel::create($userId, $subject);
        }

        Redirect::to('support');
    }

    public function view($ticketId)
    {
        $this->requireUser();

        $userId = (int) Session::get('user_id');
        $ticketId = (int) $ticketId;

        $ticket = SupportTicketModel::getById($ticketId);

        if (!SupportTicketModel::userCanAccess($ticket, $userId)) {
            Redirect::to('support');
            return;
        }

        $messages = SupportMessageModel::getByTicketId($ticketId);

        $this->View->render('support/view', [
            'ticket' => $ticket,
            'messages' => $messages
        ]);
    }

    public function addMessage($ticketId)
    {
        $this->requireUser();

        $userId = (int) Session::get('user_id');
        $ticketId = (int) $ticketId;

        $message = isset($_POST['message']) ? trim($_POST['message']) : '';
        if ($message === '') {
            Redirect::to('support/view/' . $ticketId);
            return;
        }

        $ticket = SupportTicketModel::getById($ticketId);

        if (!SupportTicketModel::userCanAccess($ticket, $userId)) {
            Redirect::to('support');
            return;
        }

        SupportMessageModel::create($ticketId, $userId, $message);

        Redirect::to('support/view/' . $ticketId);
    }
}
