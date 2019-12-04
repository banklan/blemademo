<template>
    <v-app>
        <v-content>
            <v-container grid-list-sm>
                <v-btn href="/my_cart" fixed dark elevation="12" fab top right class="mt-5 mr-4"><v-icon>shopping_cart</v-icon></v-btn>
                <v-layout row wrap class="mb-4">
                    <v-flex xs12 sm6 class="mt-4">
                       <v-subheader color="primary">
                           <div class="title">Category: Foodstuffs</div>
                       </v-subheader>
                    </v-flex>
                    <v-flex xs12 sm4 offset-sm1>
                        <v-text-field label="Search for Product..." append-icon="search" v-model="q" @keyup.enter="searchForProd"></v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row wrap class="px-3" v-if="products.length">
                    <v-flex xs12 sm6 md3 v-for="product in products" :key="product.id" class="mb-1">
                        <product-organic :product="product"></product-organic>
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
            cat: 1,
            products: [],
            q: '',
            floatingBtn: false
        }
    },
    methods: {
        getproducts(){
            axios.get(`/get_products/${this.cat}`).then((res) => {
                console.log(res.data)
                this.products = res.data
            })
        },
        searchForProd(){
            if(!this.q.trim() == ""){
                axios.post('/search_for_product', {
                    q: this.q
                }).then((res) => {
                    console.log(res.data)
                    this.$router.push({name: 'ProductSearchResult', query:{q: this.q}, params:{result: res.data}})
                    localStorage.setItem('ProductSearchResult', JSON.stringify(res.data))
                    //pass to the search result page
                })
            }
        }
    },
    mounted() {
        this.getproducts()
    },
}
</script>

<style lang="scss" scoped>
    @media screen and (min-width: 960px) {
        .flex.md3{
            flex-basis: 23% !important;
        }
    }


</style>

