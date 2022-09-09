<?php

namespace App\Mail;

class UserRegisteredMail extends BaseMail
{
    protected $user;
    protected $to_admin;

    /**
     * UserRegistered constructor.
     * @param $user
     */
    public function __construct($user, $to_admin = false)
    {
        parent::__construct();
        $this->user = $user;
        $this->to_admin = $to_admin;
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
            ->subject(sprintf('Nuevo registro en %s!',$this->app_name))
            ->markdown('page.components.mails.new_user',['to_admin'=>$this->to_admin])
            ->with('token', $this->user['token']);
    }
}
