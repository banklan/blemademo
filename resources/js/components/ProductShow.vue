<template>
    <v-app light>
        <v-content>
            <v-container grid-list-sm>
                <v-btn fixed dark elevation="12" fab top right class="mt-5 mr-4" href="/my_cart"><v-icon>shopping_cart</v-icon></v-btn>
                <v-layout my-3>
                    <v-flex xs4>
                        <v-btn color="#ff3c38" dark raised rounded ripple @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
                    </v-flex>
                    <v-flex xs8>
                        <h4 class="subtitle grey--text">{{ product && product.category.name }}</h4>
                    </v-flex>
                </v-layout>
                <v-layout row wrap class="mb-4">
                    <v-flex xs12 sm8 class="mt-4">
                        <v-card raised elevation="12" light ripple hover min-height="380">
                            <v-container grid-list-sm>
                                <v-layout row wrap v-if="product" class="my-3">
                                    <v-flex xs12 sm6 text-xs-center >
                                        <v-img contain max-height="320" :src="prodImg" transition="scale-transition"></v-img>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                    <v-card-title>
                                        <div class="subtitle primary--text">{{ product.name }}  - &#8358;{{ product.price | price }}</div>
                                    </v-card-title>
                                        <v-card-text>
                                            <div class="body-3 grey--text mb-3">{{ product.description }}</div>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-container fluid>
                                                <v-row align="center">
                                                    <v-col cols="6">
                                                        <v-select dense small :items="units" :label="product.unit" v-model="picked.units"></v-select>
                                                    </v-col>
                                                    <v-col cols="6">
                                                        <v-btn :disabled="added" text light class="primary--text" @click.prevent="addToCart(product)">Add To Cart</v-btn>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-actions>
                                    </v-flex>
                                </v-layout>
                                <v-progress-circular indeterminate color="coral" :width="7" :size="70" v-if="!product"></v-progress-circular>
                            </v-container>
                        </v-card>
                    </v-flex>
                    <v-flex xs12 sm4 class="mt-4">
                        <v-card v-if="product && product.service.length > 0" raised elevation="12" light ripple min-height="350" width="100%" class="mb-4">
                            <v-card-title class="justify-center">
                                <div class="subtitle">Extra Services on {{ product.name }}</div>
                            </v-card-title>
                             <v-card v-for="serv in product.service" :key="serv.id" class="serv_card" min-height="120" width="90%">
                                 <v-card-title>
                                     <div class="body-2 primary--text">{{ serv.name }}</div>
                                 </v-card-title>
                                 <v-card-text>
                                     <span>{{ serv.description }}</span>
                                     <v-btn :disabled="loading" text dark class="primary--text float-right" @click.prevent="addService(serv)">Add Service</v-btn>
                                </v-card-text>
                             </v-card>
                        </v-card>
                        <v-card raised elevation="12" light ripple hover min-height="380" width="100%">
                            <v-card-title class="justify-center">
                                <div class="subtitle">Similar Products</div>
                            </v-card-title>
                            <v-progress-circular indeterminate color="coral" :width="2" :size="30" v-if="similar.length == 0"></v-progress-circular>
                            <div v-if="similar.length > 0" class="similar">
                                <v-card v-for="prod in similar" :key="prod.id" class="mb-3" height="80" width="90%" :to="{path: `/${prod.category.slug}/${prod.id}/${prod.slug}`}" hover>
                                    <v-container grid-list-sm>
                                        <v-layout row wrap>
                                            <v-flex xs3>
                                                <v-img contain max-height="60" :src="prod.img" transition="scale-transition"></v-img>
                                            </v-flex>
                                            <v-flex xs9>
                                                <div class="details">
                                                    <div class="body-5 primary--text">{{ prod.name }} - &#8358;{{ product.price | price }}</div>
                                                    <div class="body-2 grey--text">{{ prod.description }}</div>
                                                </div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                            </div>
                        </v-card>
                    </v-flex>
                </v-layout>
                <v-row justify="center">
                    <v-dialog v-model="confirmAdd" max-width="350">
                        <v-card class="confirm_dialg">
                            <v-card-title class="subtitle-1 justify-center">Item Added To Cart</v-card-title>

                            <v-card-text>
                                <div class="subtitle-2 black--text">What do you want to do?</div>
                            </v-card-text>
                            <v-card-actions>
                                <div class="flex-grow-1"></div>
                                <v-btn dark color="#ff5e5a" @click="confirmAdd = false">
                                    Continue Shopping
                                </v-btn>
                                <v-btn href="/my_cart" class='btn btn_submit'>Buy Now</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-row>
                <v-snackbar v-model="serviceAddedSuccess" :timeout="4000" top color="#44a80f">
                    You have added a service to your cart
                    <v-btn color="white green--text" text @click.prevent="serviceAddedSuccess = false">Close</v-btn>
                </v-snackbar>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            id: null,
            product: null,
            units: [1,2,3,4,5],
            picked: {
                id: null,
                name: '',
                price: null,
                units: null,
                cost: null,
            },
            addedService: {
                id: null,
                type: null,
                price: null,
                units: null,
                cost: null
            },
            loading: false,
            loading2: false,
            // addSuccess: false,
            similar: [],
            serviceAddedSuccess: false,
            added: false,
            unitsPicked: null,
            added: false,
            pickedUnit: null,
            confirmAdd: false,
            prodImg: ''
        }
    },
    watch: {
        '$route.params':{
            handler(newVal){
                let id = newVal
                this.getProduct(id);
                this.picked.units = null
            },
            immediate: true
        }
    },
    methods: {
        getProduct(){
            axios.get(`/get_product/${this.$route.params.id}`).then((res) => {
                this.product = res.data.product
                this.prodImg = res.data.image

                //get similar products
                axios.get(`/get_similar_products/${res.data.category_id}/${this.$route.params.id}`).then((res) => {
                    this.similar = res.data
                })
            })
        },
        addToCart(product){
            this.added = true
            this.picked.id = product.id
            this.picked.name = product.name
            this.picked.price = product.price
            if(!this.picked.units){
                this.picked.units = 1
            }
            this.pickedUnit = this.picked.units
            this.picked.cost = parseFloat(product.price) * this.picked.units
            // console.log(this.picked)
            this.added = false

            this.$store.commit('addItemsToCart', this.picked)
            this.picked = {}
            this.confirmAdd = true
        },
        addService(serv){
            this.loading = true
            this.addedService.type = serv.name
            this.addedService.id = serv.id
            this.addedService.price = serv.price
            this.addedService.units = this.pickedUnit
            this.addedService.cost = parseFloat(serv.price) * this.addedService.units
            this.$store.commit('addServicesToCart', this.addedService)
            this.serviceAddedSuccess = true
            this.loading = false
            this.addedService = {}
        },
        goSimilar(){
            this.$router.push('/raw_foodstuff/' + this.product.id + '/' + this.product.slug)
        }
    },
    mounted() {
        this.getProduct();

        // this.getSimilar()
    },
}
</script>

<style lang="scss" scoped>
    @media screen and (min-width: 600px){
        .v-card:not(.v-card.confirm_dialg){
            margin-left: 2.5rem !important;

        }
        .v-card__title{
            font-size: 1.1rem;
            font-weight: 200 !important;
        }
    }
    .v-application .primary--text{
        color: #ff3c38 !important;
    }
    .v-application .sec--text{
        color: #15C5C5 !important;
    }

    *{
        text-transform: none !important;
    }
    .similar a{
        margin: 1rem auto !important;
        text-decoration: none !important;

        .details{
            padding: 8px;
        }
    }
    .similar a:first-child{
        margin-top:-1rem;
    }
    .v-card .serv_card{
        margin: 1rem auto !important;
        line-height: 1.8 !important;
    }
</style>
