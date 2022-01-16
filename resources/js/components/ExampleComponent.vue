<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Datos: {{ num }}</div>

                    <div class="card-body">
                        <!-- <button v-on:click.prevent="sumNum()">Sumar uno</button> -->
                        <button @click.prevent="sumNum()">Sumar uno</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                num: 5,
            }
        },
        methods: {
            sumNum() {
                this.num++;
            }
        },
    }
</script>
