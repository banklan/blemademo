<template>
    <v-app>
        <v-content>
            <v-container grid-list-sm>
                <v-btn href="/my_cart" fixed dark elevation="12" fab top right class="mt-5 mr-4"><v-icon>shopping_cart</v-icon></v-btn>
                <v-layout row wrap class="mb-4">
                    <v-flex xs12 sm6 class="mt-4">
                       <v-subheader color="primary">
                           <div class="title">Category: Proteins</div>
                       </v-subheader>
                    </v-flex>
                    <v-flex xs12 sm4 offset-sm1>
                        <product-search></product-search>
                    </v-flex>
                </v-layout>
                <v-layout row wrap class="px-3" v-if="products.length">
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
            id: 16,
            products: []
        }
    },
    methods: {
        getRecipes(){
            axios.get(`/get_products_categories/${this.id}`).then((res) => {
                this.products = res.data
                console.log(res.data)
            })
        }
    },
    mounted() {
        this.getRecipes()
    },
}
</script>
