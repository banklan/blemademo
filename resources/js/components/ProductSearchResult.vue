<template>
    <v-app>
        <v-content>
            <v-container grid-list-sm>
                <v-btn href="/my_cart" fixed dark elevation="12" fab top right class="mt-5 mr-4"><v-icon>shopping_cart</v-icon></v-btn>
                <v-layout row wrap class="mb-4">
                    <v-flex xs12 sm6 class="mt-4">
                       <v-subheader color="primary">
                           <div class="title">Search results for {{ q }} ({{products.length}})</div>
                       </v-subheader>
                    </v-flex>
                    <v-flex xs12 sm4 offset-sm1>
                        <product-search></product-search>
                    </v-flex>
                </v-layout>
                <v-progress-circular v-if="loading" class="justify-center" indeterminate color="#ff383c" :width="5" :size="50"></v-progress-circular>
                <v-layout v-else row wrap class="px-3">
                    <v-flex xs12 sm6 md3 v-for="product in products" :key="product.id" class="mb-1">
                        <product-card :product="product"></product-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            products: [],
            q: this.$route.query.q,
            loading: true
        }
    },
    watch: {
        '$route.query.q': {
            handler(newVal){
                this.q = newVal
                axios.post('/search_for_product', {
                    q: this.q
                }).then((res) => {
                    this.loading = false
                    this.products = res.data
                })
            },
            immediate: true
        }
    },
    methods: {
        search(){
            axios.post('/search_for_product', {
                q: this.q
            }).then((res) => {
                this.loading = false
                this.products = res.data
            })
        }
    },
    mounted() {
        this.search()
    },
}
</script>

<style>

</style>
