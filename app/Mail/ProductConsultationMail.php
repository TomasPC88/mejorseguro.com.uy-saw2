<?php

namespace App\Mail;

class ProductConsultationMail extends BaseMail
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
                    ->subject('¡Consulta desde el producto "'.$this->data[1]->name.'"!')
                    ->markdown('page.components.mails.product_consultation_mail')
                    ->with(['data', $this->data]);
    }
}
