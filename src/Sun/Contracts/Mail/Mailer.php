<?php

namespace Sun\Contracts\Mail;

interface Mailer
{
    /**
     * To send an email
     *
     * @param   string    $email
     * @param   string    $name
     * @param   string    $subject
     * @param   string    $body
     * @param   string    $attachment
     * @param   array     $bcc
     *
     * @return  bool
     * @throws  MailerException
     */
    public function send($email, $name = null, $subject, $body, $attachment = null, $bcc = null);

    /**
     * To clean mailer log file
     */
    public function logClean();
}