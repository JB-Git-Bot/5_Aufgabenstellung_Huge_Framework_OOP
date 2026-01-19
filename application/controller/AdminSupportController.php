<?php

class AdminSupportController extends Controller
{
    private function requireAdmin()
    {
        Auth::checkAuthentication();

        // Bei dir: Admin = user_account_type 7
        if ((int) Session::get('user_account_type') !== 7) {
            Redirect::to('index');
            exit;
        }
    }

    public function index()
    {
        $this->requireAdmin();

        $tickets = SupportTicketModel::getAll();

        $this->View->render('adminSupport/index', [
            'tickets' => $tickets
        ]);
    }

    public function view($ticketId)
    {
        $this->requireAdmin();

        $ticketId = (int)$ticketId;
        $ticket = SupportTicketModel::getById($ticketId);

        if (empty($ticket)) {
            Redirect::to('adminSupport');
            return;
        }

        $messages = SupportMessageModel::getByTicketId($ticketId);

        $this->View->render('adminSupport/view', [
            'ticket' => $ticket,
            'messages' => $messages
        ]);
    }

    public function assign($ticketId)
    {
        $this->requireAdmin();

        $ticketId = (int)$ticketId;
        $adminId = (int) Session::get('user_id');

        SupportTicketModel::assignAdmin($ticketId, $adminId);

        Redirect::to('adminSupport/view/' . $ticketId);
    }

    public function addMessage($ticketId)
    {
        $this->requireAdmin();

        $ticketId = (int)$ticketId;
        $adminId = (int) Session::get('user_id');

        $message = isset($_POST['message']) ? trim($_POST['message']) : '';
        if ($message === '') {
            Redirect::to('adminSupport/view/' . $ticketId);
            return;
        }

        SupportMessageModel::create($ticketId, $adminId, $message);

        Redirect::to('adminSupport/view/' . $ticketId);
    }
}
