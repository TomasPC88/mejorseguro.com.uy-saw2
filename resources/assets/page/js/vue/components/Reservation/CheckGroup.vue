<template>
    <div v-if="$options.filters.isNotEmpty(items)" class="custom-block">
        <div class="custom-block__header">
            <h3>{{$t('form.label.extras')}}</h3>
        </div>
        <ul class="custom-block__body">
            <li v-for="item of items" :key="item.id">
                <label :for="`check_${item.id}`">
                    <div class="checkbox checkbox--round">
                        <input :value="item.id" v-model="selected" :name="name" :id="`check_${item.id}`" type="checkbox"/>
                        <span class="checkmark"></span>
                    </div>
                    <span class="label">{{item.name}}</span>
                </label>
            </li>
        </ul>
    </div>
</template>

<script>
    import BaseStore from '../Base/BaseStore'

    export default {
        name: "check-group",
        extends: BaseStore,
        props: {
            title: {
                type: String,
                required: true
            },
            items:{
                type:Array,
                default:function(){
                    return []
                }
            }
        },
        computed: {
            selected: {
                set(v) {
                    this.$store.commit('putInSelected', {key: this.name, value:v});
                },
                get() {
                    return this.$store.getters.getSelectedByName(this.name) || []
                }
            }
        }
    }
</script>