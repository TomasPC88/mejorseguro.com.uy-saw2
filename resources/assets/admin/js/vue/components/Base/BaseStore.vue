<script>
    export default {
        name: "base-store",
        props: {
            name: {
                type: String,
                required: true
            },
            value: [Array, Number, String]
        },
        created() {
            this.selected = this.value
        },
        methods: {
            clearSelected() {
                switch (typeof value) {
                    case 'number':
                        this.selected = 0;
                        break;
                    case 'string':
                        this.selected = '';
                        break;
                    default:
                       this.selected = [];
                }
            }
        },
        computed: {
            selected: {
                set(v) {
                    if(v!==this.selected)
                        this.$store.commit('putInSelected', {
                            key: this.name,
                            value: v,
                        })
                },
                get() {
                    return this.$store.getters.getSelectedByName(this.name)
                }
            }
        }
    }
</script>