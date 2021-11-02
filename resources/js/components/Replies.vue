<template>
    <div class="mt-4">
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>
    </div>
</template>
<script>
    import Reply from './Reply.vue';
    export default {
        components: { Reply },
        props: ['data'],
        data() {
            return { 
                items: this.data,
            };
        },
        methods: { 
            remove(index) {
                this.items.splice(index, 1);
                flash('Your reply has been deleted!');
                this.$emit('removed', index);
            }
        }
    }
</script>npm