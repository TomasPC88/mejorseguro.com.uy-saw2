<template>
    <div>
        <input ref="input" type="hidden" :name="name" :value="value">
        <vue-tags-input style="max-width: 100%"
                v-model="tag"
                :placeholder="placeholder||'Adicionar elemento'"
                :tags="tags"
                :validation="validation"
                :avoid-adding-duplicates="true"
                @tags-changed="newTags => tags = newTags">
        </vue-tags-input>
    </div>
</template>

<script>
    import TagInput from '@johmun/vue-tags-input'

    export default {
        name: "tag-input",
        components: {
            vueTagsInput: TagInput
        },
        props: {
            name: {
                required: true,
                type: String
            },
            propTags: {
                required: false,
                type: Array
            },
            validation: {
                required: false,
                type: Array
            },
            placeholder:{
                required:false,
                type:String
            }
        },
        data() {
            return {
                tag: '',
                tags: []
            }
        },
        created() {
            if (this.propTags)
                this.propTags.filter(tag => tag!=='').map((tag) => {
                    this.tags.push({
                        text: tag
                    })
                });
        },
        computed: {
            value() {
                return this.tags.map(({text}) => text)
            }
        }
    }
</script>

<style>
    .vue-tags-input .content{
        margin-bottom: 0!important;
    }
    .vue-tags-input .input{
        height: 50px;
    }
</style>