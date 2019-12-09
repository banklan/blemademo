o<template>
    <v-app light>
        <v-layout row wrap>
            <v-flex xs2 sm1>
                <nav-drawer-user></nav-drawer-user>
            </v-flex>
            <v-flex xs10 sm11>
                <v-container grid-list-xs>
                    <v-row>
                        <v-col cols="4" md="3">
                            <v-btn color="#ff3c38" dark raised rounded ripple @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
                        </v-col>
                        <v-col cols="8" md="9">
                            <div class="title">My Order - {{ orderId }}</div>
                        </v-col>
                    </v-row>
                    <v-divider></v-divider>
                    <v-layout row wrap justify-center>
                        <v-flex xs12 md5>
                            <v-progress-circular v-if="loading" indeterminate color="orange" :width="7" :size="70"></v-progress-circular>
                            <v-card v-if="!loading" light min-height="300" elevation="20" class="justify-center mt-4 ml-2 mb-4">
                                <v-card-title class="justify-center">
                                    <div class="subtitle-1">
                                        Order Summary
                                    </div>
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text>
                                    <v-simple-table light>
                                        <tr>
                                            <th>Order ID</th>
                                            <td class="primary--text darken-5">{{ order.order_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Order Date</th>
                                            <td>{{ order.date }}</td>
                                        </tr>
                                        <tr>
                                            <th>Order Time</th>
                                            <td>{{ order.time }}</td>
                                        </tr>
                                        <tr>
                                            <th>Items Count</th>
                                            <td>{{ order.item_count }}</td>
                                        </tr>
                                        <tr>
                                            <th>Services count ID</th>
                                            <td>{{ order.services_count }}</td>
                                        </tr>
                                        <tr>
                                            <th>Order Value</th>
                                            <td>&#8358;{{ order.value | price }}</td>
                                        </tr>
                                        <tr>
                                            <th>Order Status</th>
                                            <td class="orange--text darken-4">{{ order.status }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Status</th>
                                            <td>{{ order.payment_status }}</td>
                                        </tr>
                                    </v-simple-table>
                                </v-card-text>
                                <v-card-actions class="pa-5">
                                    <v-btn color="#ff3c38" dark raised rounded ripple @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                        <v-flex xs12 md6>
                            <v-progress-circular v-if="loading" indeterminate color="orange" :width="7" :size="70"></v-progress-circular>
                            <v-card v-else light min-height="300" elevation="20" class="justify-center ma-4 order_table">
                                <v-card-title class="justify-center">
                                    <div class="subtitle-1">Order Items</div>
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text>
                                    <v-simple-table light fixed-header min-height="250" table-bordered>
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Product/Service</th>
                                                <th>Price(&#8358;)</th>
                                                <th>Units</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, i) in orders" :key="i">
                                                <td>{{ i + 1 }}</td>
                                                <td v-if="item.product_id" width="25%">{{ item.product && item.product.name }}</td>
                                                <td v-if="!item.product_id" width="22%">{{ item.service && item.service.name }}</td>
                                                <td v-if="item.product_id" width="22%">{{ item.product && item.product.price | price }}</td>
                                                <td v-if="!item.product_id">{{ item.service && item.service.price | price }}</td>
                                                <td>{{ item.units }}</td>
                                                <td width="22%">{{  item.cost | price }}</td>
                                            </tr>
                                        </tbody>
                                    </v-simple-table>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>
    </v-app>
</template>

<script>
export default {
    data(){
        return{
            id: this.$route.params.id,
            orderId: this.$route.params.orderId,
            loading: true,
            order: null,
            orders: []
        }
    },
    methods:{
        getOrder(){
            axios.get(`/get_userorder/${this.orderId}`).then((res) => {
                this.loading = false
                this.order = res.data
            })
        },
        getOrders(){
            axios.get(`/get_userorders_byorder_id/${this.orderId}`).then((res) => {
                this.loading = false
                this.orders = res.data
                // console.log(res.data);

                res.data.forEach(item => {
                    if(item.product_id){
                        item.cost = parseFloat(item.product.price) * parseFloat(item.units)
                    }else{
                        item.cost = parseFloat(item.service.price) * parseFloat(item.units)
                    }
                });
            })
        }
    },
    mounted() {
        this.getOrder()

        this.getOrders()
    },
}
</script>

<style lang="scss" scoped>
    .v-data-table{
        margin-top: -15px !important;
    }
    @media screen and(max-width: 960px){
       .v-card.order_table{
            width: 100vw !important;

            .v-simple-table{
                overflow-x: scroll !important;
                width: 100%;
            }
        }
    }

</style>

