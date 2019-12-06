<template>
    <v-app>
        <v-content>
            <v-container grid-list-sm>
                <v-btn href="/my_cart" fixed dark elevation="12" fab top right class="mt-5 mr-4"><v-icon>shopping_cart</v-icon></v-btn>
                <v-layout row wrap class="mb-4">
                    <v-flex xs12 sm6 class="mt-4">
                       <v-subheader color="primary">
                           <div class="title">Blema Kitchen</div>
                       </v-subheader>
                    </v-flex>
                    <v-flex xs12 sm4 offset-sm1>
                        <product-search></product-search>
                    </v-flex>
                </v-layout>
                <v-layout row wrap class="px-3" v-if="products.length">
                    <v-flex xs12 sm6 md3 v-for="product in products" :key="product.id" class="mb-1">
                        <soup-card :product="product"></soup-card>
                    </v-flex>
                    <v-flex xs12 class="my-4">
                        <div class="order_special">I dont want any of these..
                            <v-btn href="/special_order" color="#ff3c38" hover dark elevation="12" class="ml-5">Make Special Order</v-btn>
                        </div>
                    </v-flex>
                </v-layout>
                <v-layout row wrap class="px-3" v-else>
                    <v-flex xs12 sm6>
                        <div class="alert alert-info text-center">
                            <p>No products for this category</p>
                        </div>
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
            id: 19,
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

<style lang="scss" scoped>
    .order_special{
        font-size: 18px !important;
        font-weight: 300 !important;
        display: inline-block;
        // margin-right: 6rem !important;
        margin-top: 2rem;
        margin-bottom: 4rem;

        a{
            text-decoration: none !important;
            text-transform: none !important;
            padding: 22px !important;
            transition: all .4s;

            &:hover{
                background: transparent !important;
                color: #ff3c38 !important;
                border: 0px solid #f9f9f9 !important;
            }
        }
    }
</style>

