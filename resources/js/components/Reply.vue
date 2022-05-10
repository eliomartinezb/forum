<template>
    <div :id="'reply-' + id" class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"></a> said <span v-text="ago"></span>...
            </div>
            <div v-if="signedIn">
                <favorite :reply="data"></favorite>
            </div>
        </div>
        <div class="card-body">
            <div class="body" v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div class="body" v-else v-text="body"></div>
        </div>
        <!-- @can('update', $reply)

        @endcan -->
        <div class="panel-footer level p-2" v-if="userCanUpdate">
            <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
            <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
        </div>
    </div>
</template>
<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';
    export default {
        components: { Favorite },
        props: ['data'],
        data() {
            return {
                id: this.data.id,
                body: this.data.body,
                editing: false,
            };
        },
        computed: {
            ago() {
                return moment(this.data.owner.created_at).fromNow();
            },
            signedIn() {
                return window.app.signedIn;
            },
            userCanUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
            }
        },
        methods: {
            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);
            },
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body,
                }).catch(error => {
                    flash(error.response.error, 'danger');
                });

                this.editing = false;

                flash('Updated!');
            }
        }
    }
</script>
