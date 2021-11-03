<template>
    <div class="mt-4">
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)" class="mt-4"></reply>
        </div>
        <new-reply :endpoint="endpoint" @created="add"></new-reply>
    </div>
</template>
<script>
    import NewReply from './NewReply.vue';
    import Reply from './Reply.vue';
    export default {
        components: { Reply, NewReply },
        props: ['data'],
        data() {
            return { 
                items: this.data,
                endpoint: location.pathname + '/replies',
            };
        },
        methods: { 
            add(reply) {
                this.items.push(reply);
                this.$emit('added');
            },
            remove(index) {
                this.items.splice(index, 1);
                flash('Your reply has been deleted!');
                this.$emit('removed', index);
            }
        }
    }
</script>npm