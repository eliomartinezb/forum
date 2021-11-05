<template>
    <button :class="classes" @click="onSubmit">{{ title }}</button>
</template>
<script>
export default {
    props: ['active'],
    computed: {
        classes() {
            return ['btn', this.active ? 'btn-primary' : 'btn-outline'];
        }
    },
    data() {
        return {
            title: 'Subscribe',
        };
    },
    methods: {
        onSubmit() {
            let method = !this.active ? 'POST' : 'DELETE';

            axios[method](location.pathname + '/subscriptions').then(response => {
                this.active = !this.active;
                this.title = this.active ? 'Unsubscribe' : 'Subscribe';
                flash('Success');
            }).catch();
        }
    }
}
</script>