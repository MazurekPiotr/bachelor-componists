<template>
    <div>
        <span v-if="reported">Reported <span class="glyphicon glyphicon-flag"></span></span>
        <a href="#" @click.prevent="report()" v-if="!reported && auth">Report <span class="glyphicon glyphicon-flag"></span></a>
    </div>
</template>

<script>
    export default {
        data() {
            return {
               reported: false,
               auth: Componists.auth
            }
        },
        props: {
            projectSlug: null,
            fragmentId: null
        },
        methods: {
            getStatus() {
                return this.$http.get('/projects/' + this.projectSlug + '/fragments/' + this.fragmentId + '/report/status').then((response) => {
                    this.reported = ('type' in response.body) ? true : false;
                });
            },
            report() {
                return this.$http.post('/componists/projects/' + this.projectSlug + '/fragments/' + this.fragmentId + '/report').then((response) => {
                    this.getStatus();
                });
            }
        },
        mounted() {
            this.getStatus();
        }
    }
</script>
