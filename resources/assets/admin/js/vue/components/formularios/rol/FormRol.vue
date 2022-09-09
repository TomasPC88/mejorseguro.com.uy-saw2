<template>
    <div class="columns">

        <div class="column">

            <div class="field" data-validation="['NE']" data-alias="Nombre" data-icon="">
                <div class="control has-icons-right">
                    <input name="name" class="input" type="text" placeholder="">
                </div>
            </div>

            <select-multiple
                    :rules="`['OPT']`"
                    :title="'Permisos'"
                    :name="'permissions[]'"
                    :url="{
                        fetch:data.url,
                    }"
                    :tags="[]">
            </select-multiple>
        </div>

    </div>

</template>

<script>
    import SelectMultiple from '../../select/SelectMultipleWithForms'

    export default {
        name: "form-rol",
        components:[SelectMultiple],
        props:{
            data:Object
        }
    }
</script>

<style scoped>

</style>