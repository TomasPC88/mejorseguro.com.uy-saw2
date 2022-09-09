<?php

namespace App\Mail;

class CanceledSubscriptionMail extends BaseMail
{
    protected $template;
    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = null, $template = 'page.components.mails.suscription_canceled')
    {
        parent::__construct();
        $this->template = $template;
        $this->data =  $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email_from_address, $this->app_name)
            ->subject(__('general.tit_suscripcion_cancelada', ['app' = $this->app_name]))
            ->markdown($this->template)
            ->with(['data' => $this->data]);
    }
}
