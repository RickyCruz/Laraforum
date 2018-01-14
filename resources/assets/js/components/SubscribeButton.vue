<template lang="html">
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {
        props: ['active'],

        data() {
            return {
                isActive: this.active
            }
        },

        computed: {
            classes() {
                return ['btn', this.isActive ? 'btn-primary' : 'btn-default'];
            }
        },

        methods: {
            subscribe() {
                let requestType = this.isActive ? 'delete' : 'post';

                axios[requestType](location.pathname + '/subscriptions');

                this.isActive = ! this.isActive;
            }
        }
    }
</script>
