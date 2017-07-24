<template>
    <div>
        <vue-slider ref="slider" v-model="value"></vue-slider>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                auth: Componists.auth,
                value: 0
            }
        },
        props: {
            fragmentId: null,
        },
        methods: {
            getVolume() {
                return this.$http.get('/projects/fragments/' + this.fragmentId + '/getVolume').then((response) => {
                    this.value = response.body.volume * 100;
                });
            },
            setVolume(val) {
                return this.$http.post('/componists/fragments/' + this.fragmentId + '/setVolume/' + val/100).then((response) => {

                });
            }
        },
        mounted() {
            this.getVolume();
        },
        watch: {
            value: function(val, oldVal){
                this.setVolume(val);
            }
        }
    }
</script>