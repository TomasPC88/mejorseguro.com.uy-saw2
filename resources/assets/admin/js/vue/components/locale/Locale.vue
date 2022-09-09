<template>
   <div>
       <slot name="header"></slot>
       <div class="columns">
           <div class="column">
               <div ref="tabs" class="locale-tabs">
                   <div class="tabs is-toggle">
                       <ul ref="ul">
                           <li v-for="(lang,index) of getLang()" @click="select" :data-id="lang" :class="{'is-active':!index}">
                               <a href="#">
                                   {{lang}}
                               </a>
                           </li>
                       </ul>
                   </div>
                   <div v-for="(lang,index) of getLang()" :id="`tab-${lang}`" :class="{'is-active':!index}" class="tab">
                       <div class="field" v-for="(field,index) of locale.fields" :key="index" :data-alias="`${title || field.title} (${lang})`" :data-validation="field.rules">
                           <div class="control has-icons-right">
                               <input :id="getName(lang,field.name)" :value="getValue(lang,field.name)" :name="getName(lang,field.name)" :class="{'disabled':!field.active}" class="input" :type="field.type">
                               <span class="icon is-small is-right">
                                    <i class="fa "></i>
                                </span>
                               <p v-if="field.helper" class="help">{{field.helper}}<strong>({{lang}})</strong></p>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       <slot name="footer"></slot>
   </div>
</template>

<script>
    export default {
        name: "locale",
        props: {
            data: Object,
            locale: Object,
            title:String,
        },
        methods: {
            select(event) {
                Array.from(this.$refs.ul.querySelectorAll('li')).map((li) => {
                    li.classList.remove('is-active');
                });
                event.currentTarget.classList.add('is-active');
                Array.from(this.$refs.tabs.querySelectorAll('[id^="tab"]')).map((tab) => {
                    tab.classList.remove('is-active');
                });
                this.$refs.tabs.querySelector(`[id="tab-${event.currentTarget.dataset.id}"]`).classList.add('is-active');
            },
            getName(locale, name) {
                if(this.data.hasOwnProperty('id'))
                    return `${this.locale.section}['${this.data.id}'][translations][${locale}][${name}]`;

                return `${this.locale.section}[translations][${locale}][${name}]`;
            },
            getValue(locale, name) {
                if(this.data.hasOwnProperty('translations') && this.data.translations.length > 0){
                    let item = this.data.translations.find(item => item.locale === locale);
                    return item[name];
                }
                return '';
            },
            getLang() {
                return this.locale.lang.split(',') || [];
            }
        }
    }
</script>

<style scoped>
    label {
        font-weight: bold;
        text-transform: capitalize;
    }

    .disabled {
        pointer-events: none;
        background: rgba(0, 0, 0, 0.1);
        color: gray;
    }
</style>