<template>
    <ul class="pagination mt-4" v-if="shouldPaginate">
        <li class="page-item" v-show="prevUrl">
            <a class="page-link" href="#" rel="prev" @click.prevent="page--">&laquo; Previous</a>
        </li>
        <!-- <li class="page-item">
            <a class="page-link" href="#">1</a>
        </li>
        <li class="page-item">
            <a class="page-link" href="#">2</a>
        </li>
        <li class="page-item">
            <a class="page-link" href="#">3</a>
        </li> -->
        <li class="page-item" v-show="nextUrl" >
            <a class="page-link" href="#" rel="next" @click.prevent="page++">Next &raquo;</a>
        </li>
    </ul>
</template>
<script>
export default {
    props: ['dataSet'],
    data() {
        return {
            page: 1,
            prevUrl: false,
            nextUrl: false,
        };
    },
    computed: {
        shouldPaginate() {
            return !!this.prevUrl || !!this.nextUrl;
        }
    },
    methods: {
        brodcast() {
            return this.$emit('changed', this.page);
        },
        updateUrl() {
            history.pushState(null, null, '?page=' + this.page)
        }
    },
    watch: {
        dataSet() {
            this.page = this.dataSet.current_page;
            this.prevUrl = this.dataSet.prev_page_url;
            this.nextUrl = this.dataSet.next_page_url;
        },
        page() {
            this.brodcast().updateUrl();
        }
    }
}
</script>
