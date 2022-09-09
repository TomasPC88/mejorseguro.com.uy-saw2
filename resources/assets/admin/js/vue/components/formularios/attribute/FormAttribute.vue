<template>
    <div>
        <div class="columns">
            <div class="column is-one-quarter">
                <p><strong>{{title}}</strong></p>
            </div>
            <div class="column is-one-quarter">
                <div v-multi-ref:fields class="field" :data-alias="title" data-validation="['OPT']">
                    
                    <div class="control has-icons-right wo2-vue-input">
                        <input v-model="pivot.value" :id="name('value')" :class="{'disabled':pivot.active}"
                               :name="name('value')" placeholder="Valor" class="input" type="text">
                    </div>
                </div>
            </div>
        </div>
        <slot name="footer"></slot>
    </div>
</template>

<script>
    export default {
        name: "attributeForm",
        props: {
            id: Number,
            title: String,
            data: Object
        },
        data() {
            return {
                pivot: {
                    value: '',
                }
            }
        },
        methods: {
            name(name) {
                return `[${this.id}][${name}]`;
            }
        },
        mounted() {
            if (this.data)
                if (this.data.hasOwnProperty('value'))
                    this.pivot.value = this.data.value;

            this.saveValidation(this.$refs.fields)
        },
        destroyed() {
            this.removeValidation(this.$refs.fields)
        }
    }
</script>
<style scoped>
    label {
        font-weight: bold;
        text-transform: capitalize;
    }

    /* .wo2-vue-input {
        margin-top: 0.5em;
    } */
</style>
