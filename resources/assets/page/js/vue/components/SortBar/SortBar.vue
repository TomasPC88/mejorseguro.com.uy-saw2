<template>
    <div class="proyecto__searcher my-4">
        <form class="form-inline">
            <select-filter
                    group="projects"
                    name="departamento"
                    label="Departamento"
                    :options="departamentos"
            >
            </select-filter>
            <select-filter
                    group="projects"
                    name="ciudad"
                    label="Ciudad"
                    :related="true"
                    listen="projects.departamento"
                    url="departamento/{id}/ciudades"
            >
            </select-filter>
            <select-filter
                    group="projects"
                    name="zona"
                    label="Zona"
                    :related="true"
                    listen="ciudad"
                    url="ciudad/{id}/zonas"
            >
            </select-filter>
        </form>
    </div>
</template>
<script>
    import SelectFilter from '../Catalogue/Filters/SelectFilter'
    import departamentos from '../assets/departamentos'

    export default{
        name: 'sort-bar',
        components:{
            SelectFilter
        },
        data(){
            return {
                departamentos:departamentos
            }
        }
    }
</script>
<style scoped>

</style>