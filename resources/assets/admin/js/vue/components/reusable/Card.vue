<template>
    <div :class="{'disabled':!data.active}" class='card'>
        <div>
            <tile class="thumb" :src="firstImage"></tile>
        </div>
        <div>
            <h2>{{data.name}}</h2>
            <h4>{{data.status|statusName(data.statuses)}}</h4>
            <p>{{data.description}}</p>
        </div>
        <span class="badge">
            <p>{{data.fecha|dateFormat}}</p>
        </span>
    </div>
</template>

<script>
    import Tile from '../reusable/Tile'

    export default {
        name: "card",
        components: {
            tile: Tile
        },
        props: {
            data: Object,
        },
        computed: {
            firstImage: function () {
                if (this.data.first_image.thumb != undefined)
                    return this.data.first_image.thumb.thumb;

                return this.data.first_image.default.thumb;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .card {
        width: 300px;
        border: solid 1px #adcabc;
        cursor: pointer;
        text-align: justify;
        /*display: flex;*/
        flex:1;
        padding: 10px;
        position: relative;
        &.disabled{
            background: darken(white,20%);
        }
        div{
            &:first-child{
                height: 215px;
                width: 100%;
            }
            h2,h4{
                text-align: center;
                font-weight: bold;
                text-transform: uppercase;
            }
            h2{
                font-size:1rem;
            }
            h4{
                color: #53c78f;
                font-size: .65rem;
                margin-top:2px;
            }
            p{
                padding:8px;
                color:darken(white,35%);
                text-transform: capitalize;
                font-size:.8rem;
                margin-bottom: 25px;
            }
        }
        .badge{
            position: absolute;
            background: #53c78f;
            width: 220px;
            height: 25px;
            padding: 6px;
            right:0;
            bottom:0;
            p{
                color:white;
                font-weight: bold;
                font-size: .7rem;
                text-align: center;
            }
        }
        &:hover {
            border-color: #39E994;
            transition: border-color ease .5s;
        }
    }
</style>
