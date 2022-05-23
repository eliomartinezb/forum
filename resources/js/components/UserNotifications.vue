<template>
    <li class="dropwdown" v-show="notifications.length">
        <a href="#" class="dropwdown-toggle" data-toggle="dropwdown">
            <span class="glyphicon glyphicon-bell"></span>
        </a>
        <ul class="dropwdown-menu">
            <li v-for="notfication in notifications">
                <a :href="notfication.data.link" v-text="notfication.data.message" @click="markAsRead(notification)"></a>
            </li>
        </ul>
    </li>
</template>
<script>
    export default {
        data() {
            return {
                notifications: false,
            }
        },
        created() {
            axios.get("/profiles/" + window.app.user.name + "/notifications")
                .then(response => this.notifications = response.data)

        },
        methods: {
            markAsRead(notification) {
                axios.delete("/profiles/" + window.app.user.name + "/notifications/" + notification.id);
            }
        }
    }
</script>
