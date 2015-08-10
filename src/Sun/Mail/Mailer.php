<?php

namespace Sun\Mail;

use PHPMailer;
use phpmailerException;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Sun\Contracts\Mail\Mailer as MailerContract;

class Mailer implements MailerContract
{
    /**
     * @var \Sun\Contracts\Application
     */
    protected  $app;

    /**
     * @var PHPMailer
     */
    protected $phpMailer;

    /**
     * @var \Sun\Contracts\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Log file name
     *
     * @var string
     */
    protected $logFileName;

    /**
     * Create a new Mailer instance
     *
     * @param \Sun\Contracts\Application            $app
     * @param PHPMailer                             $phpMailer
     * @param \Sun\Contracts\Filesystem\Filesystem  $filesystem
     */
    function __construct(Application $app, PHPMailer $phpMailer, Filesystem $filesystem)
    {
        $this->app = $app;
        $this->phpMailer = $phpMailer;
        $this->filesystem = $filesystem;
        $this->logFileName = $this->getLogDirectory().'/mailer.html';
    }

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
    public function send($email, $name = null, $subject, $body, $attachment = null, $bcc = null)
    {
        $this->phpMailer->isSMTP();
        $this->phpMailer->Host = $this->app->config->getMail('host');
        $this->phpMailer->SMTPAuth = true;
        $this->phpMailer->Username = $this->app->config->getMail('username');
        $this->phpMailer->Password = $this->app->config->getMail('password');
        $this->phpMailer->SMTPSecure = $this->app->config->getMail('encryption');
        $this->phpMailer->Port = $this->app->config->getMail('port');

        $this->phpMailer->From = $this->app->config->getMail('from.email');
        $this->phpMailer->FromName = $this->app->config->getMail('from.name');

        $this->phpMailer->addAddress($email, $name);

        $this->phpMailer->addReplyTo(
                                $this->app->config->getMail('reply.email'),
                                $this->app->config->getMail('mail.reply.name')
                            );

        $this->phpMailer->addBCC($bcc);

        $this->phpMailer->addAttachment($attachment);

        $this->phpMailer->isHTML(true);

        $this->phpMailer->Subject = $subject;
        $this->phpMailer->Body    = $body;

        /**
         * If log set to true
         * then log email
         */
        if ($this->app->config->getMail('log') == true) return $this->logEmail($body);

        /**
         * If log set to false
         * then send email
         */
        if ( $this->app->config->getMail('log') !== true)
        {
            try
            {
                set_time_limit(0);

                if( ! $this->phpMailer->send()) throw new MailerException($this->phpMailer->ErrorInfo);

                else return true;
            }
            catch(phpmailerException  $e)
            {
                throw new MailerException($e->errorMessage());
            }
        }
    }

    /**
     * Generate Email Log File
     *
     * @param $body
     *
     * @return bool
     */
    protected function logEmail($body)
    {
        if(!$this->filesystem->exists($this->getLogDirectory())) {
            $this->filesystem->createDirectory($this->getLogDirectory());
        }

        return $this->filesystem->create($this->logFileName, $body);
    }

    /**
     * To clean mailer log file
     */
    public function logClean()
    {
        $this->filesystem->delete($this->logFileName);
    }

    /**
     * To get mailer log directory name
     *
     * @return string
     */
    protected function getLogDirectory()
    {
        return public_path() . '/logs';
    }

}