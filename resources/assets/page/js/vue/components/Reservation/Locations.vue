<template>
    <div class="reservation-form__body">
        <div class="container-fluid">
            <div class="row" v-if="(diffOnCalendar <= 2) && isValid">
                <small class="col-12 text-right">{{$t('form.label.quote')}}</small>
            </div>
            <div class="row  py-3">
                <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-6">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="delivery-place">
                                {{ $t('form.label.lugar_entrega') }}
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                                <select-simple
                                        id="delivery-place"
                                        name="lugar_entrega"
                                        :value="lugar_entrega"
                                        :shared_store="{
                                            is:true,
                                            where:'lugares',
                                            substract:false
                                        }"
                                        :url="{
                                        fetch:routes.lugares
                                    }"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="return-place">
                                {{ $t('form.label.lugar_devolucion') }}
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                                <!--TODO Hacer lo del disabled aca cuando no hay seleccionado lugar de entrega-->
                                <select-simple
                                        :class="{disabled:!lugar_entrega}"
                                        id="return-place"
                                        name="lugar_devolucion"
                                        :value="lugar_devolucion"
                                        :shared_store="{
                                            is:true,
                                            where:'lugares',
                                            substract:false
                                        }"
                                        :url="{
                                        fetch:routes.lugares
                                    }"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-6">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="row" :class="{disabled:!lugar_entrega}">
                                <date-picker ref="before" icon="calendar-alt" name="fecha_entrega"
                                             :label="$t('form.label.fecha_entrega')"
                                             :value="fecha_entrega"
                                             :options="options.date"/>
                            </div>
                            <div class="row" :class="{disabled:!lugar_devolucion}">
                                <date-picker ref="after" icon="calendar-alt" name="fecha_devolucion"
                                             :label="$t('form.label.fecha_devolucion')"
                                             :value="fecha_devolucion"
                                             :options="options.date"/>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="row" :class="{disabled:!fecha_entrega}">
                                <date-picker icon="clock" name="hora_entrega" :label="$t('form.label.hora_entrega')"
                                             :value="hora_entrega"
                                             :options="options.time"/>
                            </div>
                            <div class="row" :class="{disabled:!fecha_devolucion}">
                                <date-picker icon="clock" name="hora_devolucion"
                                             :label="$t('form.label.hora_devolucion')"
                                             :value="hora_devolucion"
                                             :options="options.time"/>
                                <!-- comment Texto de la condicional de la tolerancia-->
                                <!-- <span v-if="overTolerance" class="helper">{{$t('form.label.tolerancia',{tolerance:configuraciones.tolerancia})}}</span>-->
                            </div>
                        </div>
                        <div v-if="alert.active" class="row alert alert-danger p-0 w-100">
                            <p class="p-1 text-center m-0 p-0 w-100">{{alert.text}}</p>
                        </div>
                        <!--TODO inyectar el boton en modo floating desde un padre con el slot floating-->
                        <slot name="plus"></slot>
                    </div>
                </div>
            </div>
            <div v-if="lugar_entrega" class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
                    <div class="form-check mt-2">
                        <input type="checkbox" v-model="sameLocation" class="form-check-input" id="sameLocation">
                        <label class="form-check-label" for="sameLocation">{{
                            $t('form.label.entregar_mismo_lugar')}}</label>
                    </div>
                </div>
            </div>
            <div class="row" v-if="fecha_entrega && fecha_devolucion && !static && !hasSettlement">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
                    <div class="form-check mt-2">
                        <input type="checkbox" @change="evt => deducible = evt.target.checked" class="form-check-input"
                               id="deducible">
                        <label class="form-check-label" for="deducible">{{ $t('form.label.deducible') }}</label>
                    </div>
                    <!--                    comment-->
                    <!--<span class="helper">
                        {{$t('form.label.deducible_helper',{
                            dias:diffInDays,
                            dias_seguro:configuraciones.dias_seguro,
                            cost: configuraciones.precio_dias_seguro,
                            total: (diffInDays + configuraciones.dias_seguro) * configuraciones.precio_dias_seguro
                        })}}
                    </span>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SelectSimple from '../Catalogue/Filters/SelectSimpleFilter'
    import DatePicker from '../Catalogue/Filters/DatePickerFilter'
    import Moment from 'moment'

    export default {
        name: "locations",
        components: {
            SelectSimple,
            DatePicker
        },
        props: {
            static: {
                type: Boolean,
                default:
                    false
            },
            routes: {
                type: Object,
                required: true
            },
            diffInDays:{ //It is the calculated difference in Days by the rules on places
                type:Number,
                default:0
            }
        },
        data() {
            return {
                options: {
                    date: {
                        minDate: "today",
                        locale: window.locale,
                        disable: [],
                        dateFormat: "d/m/Y"
                    },
                    time: {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true
                    },
                },
                sameLocation: false,
                alert: {
                    active: false,
                    message: ''
                },
                diffOnCalendar:0, //It is the difference in Days gotten by Moment on Calendar Flatpickr Objects
                overTolerance: false
            }
        },
        created() {
            //Hago la carga una sola vez de los elementos para validar el Form
            Object.keys(this.routes).forEach((r) => {
                if (!this.$options.filters.isNotEmpty(this.$store.getters.getItemsByName(r)))
                    this.$store.dispatch('fetch', {
                        name: r,
                        url: this.routes[r]
                    })
            });
        },
        mounted() {
            this.isSameLocation();
            this.validate();

            this.$nextTick(()=>{
                this.$watch(vm => [vm['fecha_entrega']], val => {
                    const [entrega] = val;
                    if(!this.fecha_devolucion){
                          this.$store.commit('putInSelected', {
                        key: 'fecha_devolucion',
                        value: entrega
                    })
                        this.$refs.after.instance.setDate(entrega,true,'d/m/Y')
                    }
                });
            });

            this.$watch(vm => [vm['fecha_entrega'], vm['fecha_devolucion'], vm['hora_entrega'],
                vm['hora_devolucion']], val => {
                this.validate();
            });

            this.$watch(vm => [vm['lugar_entrega'], vm['lugar_devolucion']], val => {
                this.validate();
                this.isSameLocation();
            });
        },
        methods: {
            isSameLocation(){
                this.sameLocation = this.lugar_entrega === this.lugar_devolucion;
            },
            validate() {
                this.showAlert(false);
                const before = Moment(this.fecha_entrega + ' ' + this.hora_entrega, 'DD/MM/YYYY HH:mm');
                const after = Moment(this.fecha_devolucion + ' ' + this.hora_devolucion, 'DD/MM/YYYY HH:mm');

                if (before._isValid && after._isValid) {
                    this.diffOnCalendar = after.diff(before,'days');
                    if (after.isBefore(before) || before.isAfter(after))
                        this.showAlert(true, this.$t('errors.rango_fecha_incorrecto'))
                    else {
                        //Validate On Places
                        if(this.isValid)
                        this.verifyOnPlaces(before, after);

                        //Validate on Restrictions
                        if (this.isValid)
                            this.verifyOnRestrictions(before, after)
                    }

                    this.$emit('validated', this.isValid)

                    if (this.isValid)
                        this.$emit('changed');
                }
            },
            showAlert(is = true, text = '') {
                this.alert.active = is;
                this.alert.text = text;
            },
            verifyOnPlaces(before, after) {
                //Verify if places can return or give at weekday selected
                const entrega = this.$store.getters.getItemsByName('lugares').find(l => l.id === this.lugar_entrega)
                const devolucion = this.$store.getters.getItemsByName('lugares').find(l => l.id === this.lugar_devolucion)

                if (this.isValid)
                    if (entrega) {
                        const beforeDay = entrega.dias.find(d => d.id == before.isoWeekday())
                        if (beforeDay) {
                            if (beforeDay.pivot.en_horario) {
                                if (before.hour() < beforeDay.pivot.hora_oficina_inicio)
                                    this.showAlert(true, this.$t('errors.lugar_fuera_horario.entrega', [entrega.name, beforeDay.pivot.hora_oficina_inicio]));
                            } else if (!beforeDay.pivot.entrega) {
                                // this.lugares.entrega.restriccion = (option) => option.id !== entrega.id
                                this.showAlert(true, this.$t('errors.lugar', [this.$t('form.label.lugar_entrega'), this.$t(`dias[${before.isoWeekday()}]`), before.format('DD')]));
                            }
                        }
                    }

                if (this.isValid)
                    if (devolucion) {
                        const afterDay = devolucion.dias.find(d => d.id == after.isoWeekday())
                        if (afterDay) {
                            if (afterDay.pivot.en_horario) {
                                if (after.hour() > afterDay.pivot.hora_oficina_fin)
                                    this.showAlert(true, this.$t('errors.lugar_fuera_horario.devolucion', [entrega.name, afterDay.pivot.hora_oficina_fin]));
                            } else if (!afterDay.pivot.devolucion) {
                                // this.lugares.devolucion.restriccion = (option) => option.id !== devolucion.id
                                this.showAlert(true, this.$t('errors.lugar', [this.$t('form.label.lugar_devolucion'), this.$t(`dias[${after.isoWeekday()}]`), after.format('DD')]));
                            }
                        }
                        if (this.diffOnCalendar < devolucion.min_dias)
                            this.showAlert(true, this.$t('errors.min_dias_x_lugar', [devolucion.min_dias, devolucion.name]));
                    }

            },
            verifyOnRestrictions(before, after) {
                before = Moment(before.format('YYYY-MM-DD'));
                after = Moment(after.format('YYYY-MM-DD'));

                if (!this.hasSettlement)
                    //Recorro las restricciones para ver si existe alguna q coincida con el rango de precios
                    for ({id, name, cant_dias, fecha_inicio, fecha_final} of this.restricciones) {
                        fecha_inicio = Moment(fecha_inicio, 'YYYY-MM-DD')
                        fecha_final = Moment(fecha_final, 'YYYY-MM-DD')
                        if (!fecha_final._isValid) {
                            if (Moment(before.format('YYYY-MM-DD')).isSame(fecha_inicio) && this.diffOnCalendar < cant_dias) {
                                this.showAlert(true, `${this.$t('errors.restriccion')}: ${name || id} , ${this.$t('errors.no_minimo_dias_alquilar', [cant_dias])}`);
                                break
                            }
                        } else if ((before.isSameOrAfter(fecha_inicio) && before.isSameOrBefore(fecha_final)) && this.diffOnCalendar < cant_dias) {
                            this.showAlert(true, `${this.$t('errors.restriccion')}: ${name || id} , ${this.$t('errors.no_minimo_dias_alquilar', [cant_dias])}`);
                            break
                        }
                    }
            }
        },
        computed: {
            lugar_devolucion() {
                return this.$store.getters.getSelectedByName('lugar_devolucion')
            },
            lugar_entrega() {
                return this.$store.getters.getSelectedByName('lugar_entrega')
            },
            fecha_devolucion() {
                return this.$store.getters.getSelectedByName('fecha_devolucion')
            },
            fecha_entrega() {
                return this.$store.getters.getSelectedByName('fecha_entrega')
            },
            hora_devolucion() {
                return this.$store.getters.getSelectedByName('hora_devolucion') || '09:00'
            },
            hora_entrega() {
                return this.$store.getters.getSelectedByName('hora_entrega') || '09:30'
            },
            configuraciones() {
                return this.$store.getters.getItemsByName('configuraciones') || {}
            },
            restricciones() {
                return this.$store.getters.getItemsByName('restricciones') || []
            },
            feriados() {
                return this.$store.getters.getItemsByName('feriados') || []
            },
            hasSettlement: {
                set(v) {
                    this.$store.commit('setSettlement', v)
                },
                get() {
                    return this.$store.getters.getSettlement()
                }
            },
            deducible: {
                set(v) {
                    this.$store.commit('putInSelected', {
                        key: 'use_deducible',
                        value: v
                    })
                },
                get() {
                    return this.$store.getters.getSelectedByName('use_deducible')
                }
            },
            isValid() {
                return ['lugar', 'fecha', 'hora'].every((pref) => {
                    return ['entrega', 'devolucion'].every((sub) => {
                        return this.$store.getters.getSelectedByName(`${pref}_${sub}`) !== undefined
                    })
                }) && !this.alert.active && !this.hasSettlement
            }
        },
        watch: {
            sameLocation(v) {
                if (v)
                    this.$store.commit('putInSelected', {
                        key: 'lugar_devolucion',
                        value: this['lugar_entrega']
                    });
            },
            isValid(v) {
                this.$emit('validated', v)
            },
            deducible(v) {
                this.$emit('deducible', v ? ((this.diffInDays + this.configuraciones.dias_seguro) * this.configuraciones.precio_dias_seguro) : 0)
            },
            diffInDays(v) {
                this.hasSettlement = v >= this.configuraciones.dias_para_convenir_alquiler
            }
        }
    }
</script>

<style lang="scss">
    .disabled {
        cursor: not-allowed;

        .form-control, input, select, .v-select {
            pointer-events: none;
            opacity: .8;
        }

    }

    span.helper {
        display: block;
        color: rgba(0, 0, 0, 0.4);
        font-size: .8rem;
        margin-left: 15px;
    }
</style>