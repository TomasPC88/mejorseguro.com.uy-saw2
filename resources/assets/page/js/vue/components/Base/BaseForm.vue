<script>
    // TODO Hacer la herencia de templates con Pug
    import VueRecaptcha from 'vue-recaptcha'

    export default {
        name: "base-form",
        components: {
            vueRecaptcha: VueRecaptcha,
        },
        data() {
            return {
                alert: {
                    active: false,
                    type: 'info',
                    text: 'Hello World!!!'
                },
                requestParams: {}
            }
        },
        props: {
            useRecaptcha: {
                required: true,
                type: Boolean
            },
            recaptchaKey: {
                required: false,
                type: String
            },
            url: {
                required: true,
                type: String
            }
        },
        mounted() {
            document.addEventListener('click', () => {
                setTimeout(() => {
                    this.alert.active = false;
                }, 1500);
            })
        },
        methods: {
            go(params = {}) {
                this.requestParams = params;
                this.$validator.validate().then(result => {
                    if (result) {
                        this.useRecaptcha ? this.$refs.recaptcha.execute() : this.send();
                    }
                });
            },
            send() {
                this.$store.commit('setLoading', {
                    is: true,
                    uid: this._uid
                });
                axios.post(this.url, Object.assign(Object.fromEntries(new FormData(this.$refs.form)), this.requestParams))
                    .then((response) => {
                        const {message, redirect, callback} = response.data;
                        if (message) {
                            this.alert.type = message.type;
                            this.alert.text = message.text;
                            this.alert.active = true;
                        }
                        if (redirect)
                            if (message) {
                                setTimeout(() => {
                                    window.location = redirect;
                                }, 3000);
                            } else
                                window.location = redirect;

                        if (callback)
                            eval(callback)
                    })
                    .catch((e) => {
                        const {message} = e.response.data;
                        if (message) {
                            this.alert.text = message;
                            this.alert.type = 'danger';
                            this.alert.active = true;
                        } else
                            console.log(e);

                        if (e.response && e.response.status === 401)
                            this.setErrors(e.response.data.errors)
                    })
                    .finally(() => {
                        this.$store.commit('setLoading', {
                            is: false,
                            uid: this._uid
                        });
                        if (this.useRecaptcha)
                            this.resetRecaptcha();
                        this.$refs.form.reset();

                        this.$emit('sent');
                    })
            },
            setErrors(errors) {
                Object.entries(errors).forEach((value) => {
                    //Aqui busco el campo en el validator base pues es
                    const field = this.$validator._base.fields.find({name: value[0]});
                    this.errors.add({
                        id: field.id,
                        field: field.name,
                        msg: value[1][0],
                        scope: field.scope,
                        vmId: field.vmId
                    });
                    field.setFlags({
                        valid: !value[1].length,
                        dirty: true
                    });
                })
            },
            resetRecaptcha() {
                this.$refs.recaptcha.reset();
            }
        }
    }
</script>

<style lang="scss">
    form.vee {
        button.disabled {
            pointer-events: none;
            opacity: .6;
            background: #8790AE;
        }

        span.error {
            font-size: .8rem;
            color: darkred;
        }
    }
</style>