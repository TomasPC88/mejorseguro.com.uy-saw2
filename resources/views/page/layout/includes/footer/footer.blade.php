<footer>
    <suscription 
    url="{{route('page.suscribe')}}"
    :use-recaptcha="true"
    recaptcha-key="{{ cache('config')->recaptcha_public }}">
  </suscription>
</footer>
