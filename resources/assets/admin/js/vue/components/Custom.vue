<script>
    import {Channel} from "../vue-app";

    export default {
        name: "custom",
        props: {
            data: Object,
            listen:String
        },
        data(){
          return {
              value:''
          }
        },
        created(){

            //ASI SE INICIALIZA
        // <custom :listen="'geolocated'" :data="{
        //     element:'input',
        //         attrs:{
        //         name:'lat',
        //             type:'text',
        //             readonly:true
        //     },
        //     children:[]
        // }"></custom>

          Channel.$on(this.listen,(payload)=>{
              try{
                  // TODO ver porque el custom no actualiza el componente cuando se actualiza el attrs con $set
                  this.$set(this.data.attrs,'value',payload[this.data.attrs.name]);
                  this.$forceUpdate();
              }
              catch(e){
                  console.log(e);
              }
          })
        },
        render(createElement) {

            return createElement(
                this.data.element,
                {
                    style:this.data.style,
                    attrs: this.data.attrs,
                    on: {
                        input: event => {
                            this.value = event.target.value
                            this.$emit('input', event.target.value)
                        }
                    }
                },
                [
                    this.data.children.map((item) => {
                        return createElement(item.element, {
                            style:item.style,
                            attrs: item.attrs
                        },item.text)
                    })
                ]
            )
        }
    }
</script>