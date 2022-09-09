<template>
    <footer class="modal-card-foot">
        <div class="foot-btn" ref="buttonGroup">
            <button :disabled="!can" v-for="action of actions" @click.prevent="send(action.url)" :style="action.cssRules" :class="action.classNames" class="button">
                <span class="icon is-small"><i class="fa" :class="[{'fa-spinner fa-spin':sending,[action.icon]:!sending}]"></i></span>
                {{action.title}}
            </button>
            <button @click.prevent="close()" class="button">
                <!-- <span class="icon is-small"></span> -->
                Cancelar
            </button>
            <button  v-if="crear" v-for="action of actions" @click.prevent="send(action.url)" :style="action.cssRules" :class="action.classNames" class="button right">
                <span class="icon is-small"><i class="fa" :class="[{'fa-spinner fa-spin':sending,[action.icon]:!sending}]"></i></span>
                Crear
            </button>
        </div>
    </footer>
</template>

<script>
    import {Channel} from "../../vue-app";

    export default {
        name: "m-footer",
        props: {
            actions: Array,
            crear: {
                type:Boolean,
                default:false
            },
            can:{
                type:Boolean,
                default:false
            }
        },
        data() {
            return {
                sending: false
            }
        },
        created() {
            Channel.$on('sending', (on) => {
                this.sending = on;
                Array.from(this.$refs.buttonGroup.querySelectorAll('button')).map((button) => {
                    button.style.setProperty('pointer-events', on ? 'none' : 'auto');
                });
            })
        },
        methods: {
            send(url) {
                this.$emit('send', url);
            },
            close() {
                this.$emit('close');
            }
        }
    }
</script>

<style scoped>
    .fa.fa-check, .fa.fa-spinner {
        margin-right: 10px;
    }
    .right{
        float: right;
    }
</style>
