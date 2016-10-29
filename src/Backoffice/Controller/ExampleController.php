<?php

namespace Backoffice\Controller;

use Controller\Controller;

class ExampleController extends Controller
{
    public function __construct()
    {
        $this->example1 = new Example1Controller();
        $this->example2 = new Example2Controller();
        $this->example3 = new Example3Controller();
        $this->view = new \Backoffice\Views\DefaultViews();
        $this->arrayPost = $_POST;
    }

    public function contactForm()
    {
        $this->view->displayContactForm('');
    }

    public function makeContactMail()
    {
        if (isset($this->arrayPost)) {
            $this->msg = $this->checkForEmptyFields($this->arrayPost);
            $errors = array_filter($this->msg);
            if (empty($errors)) {
                $this->clean($this->arrayPost);
                $sender = $this->getSendersMail();
                $this->makeLetterAdmin($sender, $this->arrayPost['sujet'], $this->arrayPost['message']);
                $this->contactOk();
            } else {
                $this->view->displayContactForm($errors);
            }
        }
    }

    public function getSendersMail()
    {
        $user = \Component\UserSessionHandler::getUser();
        if (isset($user)) {
            return $mail = $user->email;
        } else {
            return $mail = $this->arrayPost['expediteur'];
        }
    }

    public function makeLetterAdmin($from, $subject, $letter, $to)
    {
        $subject = $subject;
        $message = '<html>
        <head>
        <title>' . $subject . '</title>
        </head>
        <body>
        ' . html_entity_decode($letter) . '
        </body>
        </html>';

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        $headers .= 'From: ' . $from . "\r\n";

        $mail = mail($to, $subject, $message, $headers); //marche

        if ($mail) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Sent contact form
     */

    public function contactOk()
    {
        $this->view->okContact();
    }

    /*
     * Page does not exist
     */

    public function unknownPage()
    {
        $this->view->noSuchPage();
    }

    /*
     * Public function support
     */
    public function support()
    {
        $this->view->displaySupport();
    }

    /*
     * Error 404
     */

    /*
     * Error 503
     */
}
