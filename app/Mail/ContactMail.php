<?php

namespace App\Mail;

class ContactMail extends BaseMail
{
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        parent::__construct();
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email_from_address, $this->app_name)
                    ->subject('¡Nuevo mensaje de contacto!')
                    ->markdown('page.components.mails.contactform')
                    ->with(['data', $this->data]);
    }
}
