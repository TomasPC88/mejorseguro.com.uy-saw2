<template>
    <div class="b-row__lang-box dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="selected-lang-box" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            <div class="flag" :class="`flag--${currentLang.name}`"></div>
            <span>({{currentLang.label}})</span>
        </button>
        <div class="dropdown-menu flags-list" aria-labelledby="selected-lang-box">
            <a v-for="(lg,index) of parsedLangs" :key="index" @click.prevent="set(lg.name)"
               class="dropdown-item flag-item" href="#">
                <div class="flag" :class="`flag--${lg.name}`"></div>
                <span>{{lg.label}}</span>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "lang-selector",
        props: {
            url:{
              type:String,
              required:true
            },
            langs: {
                type: Array,
                default: function () {
                    return [
                        {es: 'ES'},
                        {en: 'EN'},
                    ]
                }
            }
        },
        data() {
           return {
               parsedLangs : []
           }
        },
        created() {
            this.parsedLangs = this.langs.map(lang => {
                const keys = Object.keys(lang);
                return {
                    name:keys[0],
                    label:lang[keys[0]]
                }
            })
        },
        methods: {
            set(v) {
                axios.put(this.url, {
                    lang: v
                })
                    .then(() => {
                        this.clearStorage();
                        window.location.reload();
                    })
                    .catch((e) => {
                        console.log(e);
                    })
            }
        },
        computed: {
            currentLang() {
                return this.parsedLangs.find(lang => lang.name === this.locale)
            }
        }
    }
</script>

<style scoped>

</style>