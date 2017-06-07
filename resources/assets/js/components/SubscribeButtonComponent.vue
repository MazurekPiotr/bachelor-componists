<template>
    <button v-if="verb" class="btn btn-default" @click.prevent="update()">{{ verb }}</button>
</template>

<script>
    export default {
        data() {
            return {
                // if verb is null in the template, the user is probably not logged in..
                verb: null
            }
        },
        props: {
            topicSlug: null
        },
        methods: {
            update() {
                console.log('update');
                return this.$http.post('/componists/projects/' + this.topicSlug + '/subscription').then((response) => {
                    this.getStatus();
                });
            },
            getStatus() {
                return this.$http.get('/componists/projects/' + this.topicSlug + '/subscription/status').then((response) => {
                    if (response !== null) {
                        if (response.body === '1') {
                            // subscribed
                            this.verb = 'Unsubscribe';
                        } else {
                            // not subscribed
                            this.verb = 'Subscribe';
                        }
                    } else {
                        // no row in database matching criteria
                        this.verb = 'Subscribe';
                    }
                });
            }
        },
        mounted() {
            this.getStatus();
        }
    }
</script>
