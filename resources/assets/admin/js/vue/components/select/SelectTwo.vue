<template>
    <div>
        <input type="hidden" :name="name" :value="items.selected.map(item=>item.id)">
        <div class="container field" :data-validation="rules" :data-alias="title">
            <div>
                <input class="input" type="text" placeholder="filtrar..." v-model="needles.selected">
                <select @click="items.all = []" class="input" ref="selected" v-model="picked" multiple="multiple">
                    <option :value="item" v-for="item of items.filtered.selected" :key="item.id">{{item.name}}</option>
                </select>
            </div>
            <div class="actions">
                <div>
                    <span :class="{disabled:!items.all.length}" @click="add()"><i class="fa fa-chevron-left"></i></span>
                    <span :class="{disabled:!items.selected.length}" @click="remove()"><i class="fa fa-chevron-right"></i></span>
                </div>
            </div>
            <div class="is-pulled-right">
                <input class="input" type="text" placeholder="filtrar..." v-model="needles.all">
                <select @click="items.selected = []" class="input" ref="all" v-model="picked" multiple>
                    <option :value="item" v-for="item of items.filtered.all" :key="item.id">{{item.name}}</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "select-two",
        props: {
            name: String,
            title: String,
            rules: String,
            tags: Array,
            url: String
        },
        data() {
            return {
                items: {
                    all: [],
                    selected: [],
                    filtered: {
                        all: [],
                        selected: []
                    }
                },
                picked:[],
                needles: {
                    all: '',
                    selected: ''
                }
            }
        },
        created() {
            axios.get(`${this.baseUrl}/${this.url}`)
                .then((response) => {
                    this.items.all = this.items.filtered.all = response.data.data;
                    this.picked = this.tags;
                    this.add();
                })
                .catch((e) => {
                    this.toast('error', 'Ocurrió un error en la transacción');
                    console.log(e);
                })
        },
        methods: {
            add() {
                const union = this.items.selected.concat(this.picked);
                this.items.all = this.items.filtered.all = this.items.all.filter(item => this.picked.findIndex(i => i.id === item.id) === -1);
                this.items.selected = this.items.filtered.selected = union;
            },
            remove() {
                const union = this.items.all.concat(this.picked);
                this.items.selected = this.items.filtered.selected = this.items.selected.filter(item => this.picked.findIndex(i => i.id === item.id) === -1);
                this.items.all = this.items.filtered.all = union;
            }
        },
        watch: {
            'needles.all'(newVal) {
                this.items.filtered.all = this.items.all.filter(item => item.name.includes(newVal));
            },
            'needles.selected'(newVal) {
                this.items.filtered.selected = this.items.selected.filter(item => item.name.includes(newVal));
            }
        }
    }
</script>

<style lang="scss" scoped>
    @mixin background {
        background: #39e994;
        color: white;
        transition: background-color .25s ease;
    }

    .field.container {
        width: 100%;
        div:not([class="actions"]) {
            width: 40%;
            height: 250px;
            display: inline-block;
            float: left;
            input {
                margin-bottom: 5px;
            }
            select {
                height: inherit;
                width: 100%;
                font-size: 1rem;
                padding: 10px;
                cursor: pointer;
                text-transform: capitalize;
                option {
                    padding: 5px;
                    &:hover {
                        @include background;
                    }
                }
            }
        }
        .actions {
            position: relative;
            width: 10%;
            display: inline-block;
            text-align: center;
            div {
                width: 100%;
                position: absolute;
                top: 140px;
                span {
                    padding: 5px;
                    cursor: pointer;
                    border-radius: 2px;
                    &:hover {
                        @include background;
                    }
                    &.disabled{
                        pointer-events: none;
                        color:#cccccc;
                    }
                }
            }
        }
    }
</style>