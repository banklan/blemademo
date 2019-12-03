<template>
    <v-layout row wrap>
        <v-progress-circular indeterminate color="coral" :width="7" :size="70" v-if="!product"></v-progress-circular>
        <v-flex xs12 v-if="product">
            <v-card raised elevation="10" light ripple hover height="380">
                <router-link :to="{path: `/${product.category.slug}/${product.id}/${product.slug}`}">
                    <v-img contain height="210" width="100%" class="pt-2" :src="prodImg" transition="scale-transition"></v-img>
                    <v-card-title><div class="body-2 primary--text px-2">{{ product.name }} - &#8358;{{ product.price | price }} per {{ product.unit }}</div></v-card-title>
                </router-link>
                <v-card-text class="details">
                    <div class="body-5 px-2 grey--text">{{ product.description | truncate(35) }}</div>
                </v-card-text>
                <v-card-actions>
                    <v-select dense small :items="units" :label="product.unit" v-model="picked.units"></v-select>
                    <v-btn :loading="loading" :disabled="loading" text light class="primary--text" @click="addToCart(product)">Add To Cart</v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
        <v-row justify="center">
            <v-dialog v-model="confirmAdd" max-width="350">
                <v-card class="confirm_dialg">
                    <v-card-title class="subtitle-1 justify-center">Item Added To Cart</v-card-title>

                    <v-card-text>
                        <div class="subtitle-2 black--text">What do you want to do?</div>
                    </v-card-text>
                    <v-card-actions>
                        <div class="flex-grow-1"></div>
                        <v-btn color="primary" text @click="confirmAdd = false">
                            Continue Shopping
                        </v-btn>
                        <v-btn href="/my_cart" class='btn btn_submit'>Buy Now</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
    </v-layout>
</template>

<script>
export default {
    props: ['product'],
    data() {
        return {
            units: [1,2,3,4,5],
            unit: null,
            picked: {
                id: null,
                name: '',
                price: null,
                units: null,
                cost: null,
            },
            loading: false,
            confirmAdd: false,
            prodImg: ''
        }
    },
    methods: {
        getProductImg(){
           axios.get('/get_product_s3_image/' + this.product.id).then((res) => {
               this.prodImg = res.data
           })
        },
        addToCart(product){
            this.loading = true
            this.picked.id = product.id
            this.picked.name = product.name
            this.picked.price = product.price
            if(!this.picked.units){
                this.picked.units = 1
            }
            this.pickedUnit = this.picked.units
            this.picked.cost = parseFloat(product.price) * this.picked.units

            this.$store.commit('addItemsToCart', this.picked)
            this.picked = {}
            this.loading = false
            this.confirmAdd = true
        }
    },
}
</script>

<style lang="scss" scoped>
    *{
        text-transform: none !important;
    }
    .v-application .primary--text{
        color: #ff3c38 !important;
    }
    .v-application .secondary{
        background: #15C5C5 !important;
    }
    .v-application .sec--text{
        color: #15C5C5 !important;
    }
    .container.grid-list-sm .layout .flex {
        padding: 10px !important;
    }
    .v-card a{
        text-decoration: none !important;
    }
    .v-card__actions{
        margin-top: -1rem !important;
    }
    .v-card.v-card--hover.v-card--link{
        text-decoration: none !important;
    }
    .v-card .v-card__text.details{
        margin-top: -1.2rem !important;
    }
</style>
