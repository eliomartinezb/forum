<template>
    <div class="mt-4">
       <div v-if="signedIn">
           <div class="form-group" >
                <textarea name="body" id="body" placeholder="Have something to say?..." class="w-100" v-model="body"></textarea> 
            </div>
            
            <button type="submit" class="btn btn-primary" @click="addReply">Post</button>
       </div>
       <div v-else>
           Please <a href="/login">sign in</a> to participate in this discussion.
       </div>
    </div>
</template>
<script>
    export default {
        props: ['endpoint'],
        data() {
            return {
                body: '',
            }
        },
        computed: { 
            signedIn() {
                return window.app.signedIn;
            },
        },
        methods: {
            addReply() {
                axios.post(this.endpoint, {
                    body: this.body
                }).then(({data}) => {
                    this.body = '';
                    flash('Your reply has been posted');
                    this.$emit('created', data);
                })
            }
        }
    }
</script>