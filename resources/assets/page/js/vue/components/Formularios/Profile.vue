<template>
    <form ref="form" @submit.prevent="go" novalidate class="vee mb-4" >
        <alert v-if="alert.active" ref="alert" :type="alert.type" :css="{'text-align':'center'}" :message="alert.text" class="alert"></alert>
        <input type="hidden" :value="previousUrl" name="previous">

        <vue-recaptcha v-if="useRecaptcha"
                       ref="recaptcha"
                       @verify="send"
                       @expired="resetRecaptcha"
                       size="invisible"
                       :sitekey="recaptchaKey">
        </vue-recaptcha>
        <div class="form-row mt-5 mb-4">
            <div class="col px-0">
                <h3>Datos de registro</h3>
                <small>Ingresa tu informacion personal y direccion de envío</small>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <input type="text" v-validate="'required'" v-model="user.nombre"
                       class="form-control" placeholder="Nombre*" name="nombre">
                <span>{{errors.first('nombre')}}</span>
            </div>
            <div class="col-md-6 mb-3">
                <input type="email" v-validate="'required|email'" v-model="user.email"
                       class="form-control" placeholder="Email*" name="email">
                <span>{{errors.first('email')}}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <input type="text"  v-validate="'numeric'" v-model="user.telefono"
                       class="form-control" placeholder="Teléfono" name="telefono">
                <span>{{errors.first('telefono')}}</span>
            </div>
            <div class="col-md-6 mb-3">
                <input type="text"  v-validate="{ required: true, regex: /^09\d \d{3} \d{3}$/ }"
                       v-mask="'09# ### ###'"
                       v-model="user.celular"
                       class="form-control" placeholder="Celular*" name="celular">
                <span>{{errors.first('celular')}}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <select name="departamento" class="custom-select" v-validate="'required'" v-model="user.departamento">
                    <option value="" selected>Departamento</option>
                    <option v-for="(depto,index) of departamentos" :key="index" :value="index">{{depto.name}}</option>
                </select>
                <span>{{errors.first('departamento')}}</span>
            </div>
            <div class="col-md-6 mb-3">
                <input class="form-control" type="text" name="ciudad"
                       v-validate="'required'" v-model="user.ciudad" placeholder="Ciudad">
                <span>{{errors.first('ciudad')}}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <input class="form-control" type="text" name="direccion"
                       v-validate="'required'" v-model="user.direccion" placeholder="Dirección">
                <span>{{errors.first('direccion')}}</span>
            </div>
            <div class="col-md-6 mb-3">
                <input class="form-control" type="text" name="codigo_postal"
                       v-validate="'required'" v-model="user.codigo_postal" placeholder="Código Postal">
                <span>{{errors.first('codigo_postal')}}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <input class="form-control" type="password" name="password" ref="password"
                       v-validate="!user.id || user.password?'min:8':''" v-model="user.password" placeholder="Contraseña">
                <span>{{errors.first('password')}}</span>
            </div>
            <div class="col-md-6 mb-3">
                <input class="form-control" type="password" name="confirm"
                       v-validate="!user.id || user.password?'confirmed:password':''" v-model="user.confirm" placeholder="Confirmar contraseña">
                <span>{{errors.first('confirm')}}</span>
            </div>
        </div>
        <div class="form-row mt-5 mb-4 border-top border-bottom py-4">
            <div class="col px-0">
                <h3>Dirección de envío</h3>
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="envio" name="envio" v-model="user.envio">
                    <label class="custom-control-label check_box_alter_mail" for="envio">Quiero
                        que mi compra sea
                        entregada a una dirección diferente</label>
                </div>
                <div class="form-group alter-mail hide">
                    <div class="row mb-3 center-flex">
                        <div class="col-md-6">
                            <select class="custom-select" name="envio_departamento"
                                    v-validate="user.envio ? 'required' : ''"
                                    v-model="user.envio_departamento">
                                <option value="" selected>Departamento</option>
                                <option v-for="(depto,index) of departamentos" :key="index" :value="index">{{depto.name}}</option>
                            </select>
                            <span>{{errors.first('envio_departamento')}}</span>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="text"
                                   name="envio_ciudad"
                                   v-model="user.envio_ciudad"
                                   v-validate="user.envio ? 'required' : ''"
                                   placeholder="Ciudad">
                            <span>{{errors.first('envio_ciudad')}}</span>
                        </div>
                    </div>
                    <div class="row mt-3 center-flex">
                        <div class="col-md-6">
                            <input class="form-control" type="text"
                                   name="envio_direccion"
                                   v-model="user.envio_direccion"
                                   v-validate="user.envio ? 'required' : ''"
                                   placeholder="Dirección">
                            <span>{{errors.first('envio_direccion')}}</span>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="text"
                                   name="envio_codigo_postal"
                                   v-model="user.envio_codigo_postal"
                                   v-validate="user.envio ? 'required' : ''"
                                   placeholder="Código Postal">
                            <span>{{errors.first('envio_codigo_postal')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row w-100 btn-row">
            <spinner v-if="isLoading" :css="{height:'50px',float:'right'}"></spinner>
            <button v-else class="float-right btn btn-primary submit" type="submit" name="button">Actualizar</button>
        </div>
    </form>
</template>

<script>
    import Vue from 'vue'
    import { VueMaskDirective } from 'v-mask'

    Vue.directive('mask', VueMaskDirective);

    import BaseForm from '../Base/BaseForm'
    import Alert from '../Alert'
    import Spinner from '../Spinner'

    export default {
        name: "profile",
        extends: BaseForm,
        props: {
            data: {
                type: Object,
                default: function () {
                    return {
                        departamento:'',
                        envio_departamento:''
                    }
                }
            },
            previousUrl:String
        },
        components: {
            alert: Alert,
            spinner: Spinner
        },
        data() {
            return {
                // user: {},
                // TODO Hacer la carga del store
                departamentos:[]
            }
        },
        computed: {
            user() {
                return this.$store.getters.user
            }
        }
    }
</script>

<style scoped>
    .form-row span {
        font-size: 12px;
        color: red;
    }
</style>