<?php


class ChatController extends Controller
{
    public function index()
    {
        Auth::checkAuthentication();
        $this->View->render('chat/index');
    }
}