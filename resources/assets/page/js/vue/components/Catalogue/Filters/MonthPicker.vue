<template>
    <month-picker-input ref="picker" @input="change" class="picker" :lang="locale" :show-year="false"
                        v-model="selected" clearable/>
</template>

<script>
    import BaseFilter from '../../Base/BaseFilter'
    import {MonthPickerInput} from 'vue-month-picker'
    import {Channel} from '../../../vue-app'

    export default {
        name: "month-picker",
        extends: BaseFilter,
        props: {
            restrict: {
                type: Boolean,
                default: false
            },
            listen: String,
            over: {
                type: Boolean,
                default: true
            }
        },
        components: {
            MonthPickerInput
        },
        methods: {
            change() {
                this.$emit('rangeError', {
                    has:false,
                    message:''
                });

                if (this.restrict && this.toCompare) {
                    if (this.over) {
                        if (this.selected.monthIndex <= this.toCompare.monthIndex) {
                            this.$store.commit('removeFilter', {
                                group: this.group,
                                key: this.name
                            });
                            this.$refs.picker.selectedDate = null;
                            this.$emit('rangeError', {
                                has:true,
                                message:this.$t('date.from.error')
                            });
                        }
                    }
                    else {
                        if (this.selected.monthIndex >= this.toCompare.monthIndex) {
                            this.$store.commit('removeFilter', {
                                group: this.group,
                                key: this.name
                            });
                            this.$refs.picker.selectedDate = null;
                            this.$emit('rangeError',  {
                                has:true,
                                message:this.$t('date.to.error')
                            });
                        }
                    }
                }
            }
        },
        computed: {
            toCompare() {
                if (this.restrict)
                    return this.$store.getters.getFilterByName(this.group, this.listen);

                return false;
            },
            compared() {
                if (!this.restrict || this.toCompare)
                    return true;

                return false
            }
        }
    }
</script>

<style scoped>
    .picker {
        z-index: 1;
    }

    .disabled {
        pointer-events: none;
        background: rgba(0, 0, 0, 0.2);
    }
</style>