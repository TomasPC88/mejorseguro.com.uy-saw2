<template>
    <div class="wo-loading">

        <div class="init-slide-cell">
            <svg :style="css" class="slide-wo-loading"  viewBox="0 0 56 56" >
                <g>
                    <circle cx="28" cy="28" id="spinner" r="16"/>
                </g>
            </svg>
        </div>
    </div>
</template>

<script>
    export default {
        name: "spinner",
        props:{
            css:Object
        }
    }
</script>
