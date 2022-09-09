<?php

namespace App\Mail;

class NewSuscriptionMail extends BaseMail
{
    protected $token;
    protected $email;
    protected $template;
    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$email,$token,$template = 'page.components.mails.suscription')
    {
        parent::__construct();
        $this->token=$token;
        $this->email=$email;
        $this->template=$template;
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
            ->subject(__('general.tit_suscripcion_nueva',['app'=>$this->app_name]))

          /*  Aqui se incluye el markdown para cargar en el correo nueva suscripción..., pueden ser
            varias suscripciones ya sea para la pagina en general como a una seccion por ejemplo.*/

            ->markdown($this->template)
            ->with(['data'=>$this->data,'email'=>$this->email,'token'=>$this->token]);
    }
}
