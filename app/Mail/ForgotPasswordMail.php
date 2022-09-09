<?php

namespace App\Mail;

class ForgotPasswordMail extends BaseMail
{
    protected $user;

    /**
     * UserRegistered constructor.
     * @param $user
     */
    public function __construct($user)
    {
        parent::__construct();
        $this->user = $user;
    }
    /**
     * Create a new message instance.
     *
     * @return void
     */


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email_from_address, $this->app_name)
            ->subject(sprintf('Recuperación de password en %s!', $this->app_name))
            ->markdown('page.components.mails.forgot_password', ['user' => $this->user]);
    }
}
