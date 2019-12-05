<template>
    <v-app light>
        <v-row>
            <nav-drawer-admin></nav-drawer-admin>
            <v-row>
                <v-col cols="10" offset="1" class="content_wrap">
                    <v-row>
                        <v-col cols="12">
                            <div class="title">
                                Orders Management - Order {{ order }}
                            </div>
                        </v-col>
                    </v-row>
                    <v-divider></v-divider>
                    <v-row>
                        <v-col cols="12">
                            <v-btn color="#ff3c38" dark raised rounded ripple @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
                        </v-col>
                        <v-col cols="12" md="5">
                            <v-progress-circular v-if="!summary" indeterminate color="orange" :width="5" :size="50" class="justify-center align-center"></v-progress-circular>
                            <v-card v-if="summary" light elevation="12" raised min-height="500" class="pa-3">
                                <v-card-title class="justify-center">
                                    <div class="subtitle-1">Order Summary </div>
                                </v-card-title>
                                <table class="table">
                                    <tr>
                                        <th>Order Id:</th>
                                        <td>{{ order }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date:</th>
                                        <td>{{ summary.date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time:</th>
                                        <td>{{ summary.time }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer:</th>
                                        <td>{{ summary.user && summary.user.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Item Count:</th>
                                        <td>{{ summary.item_count }}</td>
                                    </tr>
                                    <tr>
                                        <th>Services Count:</th>
                                        <td>{{ summary.services_count}}</td>
                                    </tr>
                                    <tr>
                                        <th>Value(&#8358;):</th>
                                        <td>{{ summary.value | price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order Status:</th>
                                        <td>{{ summary.status }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Mode:</th>
                                        <td>{{ summary.pymt_mode }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Status:</th>
                                        <td>{{ summary.payment_status }}</td>
                                    </tr>
                                    <tr>
                                        <th>message:</th>
                                        <td>{{ summary.message }}</td>
                                    </tr>
                                </table>
                                <div class="action_btn text-center py-4">
                                    <v-btn v-if="summary.order_status == 7" color="primary" text disabled>Order Delivered</v-btn>
                                    <v-btn v-else :loading="orderAction == true" color="primary" large @click.prevent="processOrder">{{ orderStatus }}</v-btn>
                                </div>
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="7">
                            <v-progress-circular v-if="loading" indeterminate color="orange" :width="5" :size="50" class="justify-center align-center"></v-progress-circular>
                            <v-card v-else light elevation="12" raised min-height="500" class="pa-3">
                                <v-card-title class="justify-center">
                                    <div class="subtitle-1">
                                        Order Items
                                    </div>
                                </v-card-title>
                                <table class="table table-responsive table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product ID</th>
                                            <th>Product</th>
                                            <th>Units</th>
                                            <th>Unit Price(&#8358;)</th>
                                            <th>Total Price(&#8358;)</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in orders" :key="index">
                                            <td width="10%">{{ index + 1 }}</td>
                                            <td width="10%">{{ item.product_id }}</td>
                                            <td>{{ item.product && item.product.name }}</td>
                                            <td>{{ item.units }}</td>
                                            <td>{{ item.product && item.product.price | price }}</td>
                                            <td>{{ item.cost | price }}</td>
                                            <td><v-btn dark text small color="#ff3c38" @click.prevent="delOrder(item, index)"><v-icon>delete_forever</v-icon></v-btn></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-card>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-progress-circular v-if="!summary" indeterminate color="orange" :width="5" :size="50" class="justify-center align-center"></v-progress-circular>
                            <v-card v-else light elevation="12" raised min-height="500" class="pa-3">
                                <v-card-title class="justify-center">
                                    <div class="subtitle-1">Customers Information</div>
                                </v-card-title>
                                <table class="table table-responsive table-striped table-hovered">
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ user.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone No:</th>
                                        <td>{{ user.phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alt. Phone No:</th>
                                        <td>{{ user.alt_phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        <td>{{ user.address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td>{{ user.location && user.location.name }}</td>
                                    </tr>
                                </table>
                            </v-card>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-progress-circular v-if="!summary" indeterminate color="orange" :width="5" :size="50" class="justify-center align-center"></v-progress-circular>
                            <v-card v-else light elevation="12" raised min-height="500" class="pa-3">
                                <v-card-title class="justify-center">
                                    <div class="subtitle-1">Customer order History</div>
                                </v-card-title>
                                <div class="history">
                                <table class="table table-responsive table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Order ID</th>
                                            <th>No of Items</th>
                                            <th>Value</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="order in OrderHistory" :key="order.id">
                                            <td>{{ order.date }}</td>
                                            <td width="20%">{{ order.order_id }}</td>
                                            <td>{{ order.item_count }}</td>
                                            <td>{{ order.value | price }}</td>
                                            <td width="25%">{{ order.status }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="6" v-if="summary" class="my-4">
                            <v-btn :to="{name: 'AdminMessage', params: {userId: user.id, userSlug: user.slug}}" color="primary" large>Send Message To Customer</v-btn>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
            <v-dialog v-model="confirmDelete" max-width="350">
                <v-card>
                    <v-card-title class="subtitle-1 justify-center">Do you want to delete this order?</v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text color="ff3c38" @click.prevent="confirmDelete = false"> Cancel </v-btn>
                        <v-btn color="#ff3c38" dark @click.prevent="deleteOrder">Delete</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-snackbar v-model="orderDeleted" :timeout="4000" top color="#43A047">
                Order was successfully deleted.
                <v-btn color="#388E3C" flat @click="orderDeleted = false">Close</v-btn>
            </v-snackbar>
        </v-row>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            order: this.$route.params.order,
            id: this.$route.params.id,
            summary: null,
            orders: [],
            loading: false,
            loading2: false,
            confirmDelete: false,
            orderToDel: null,
            orderToDelIndex: null,
            orderDeleted: false,
            userLoading: true,
            user: null,
            OrderHistory: [],
            orderAction: false,
            message: '',

        }
    },
    computed: {
        totalValue(){
            const val = this.orders.map(item => item.cost).reduce((prev, next) => parseFloat(prev) + parseFloat(next), 0)
            return val;
        },
        orderStatus(){
            if(this.summary.order_status == 1){
                return 'Process Order'
            }else if(this.summary.order_status == 2){
                return 'Dispatch Order'
            }else{
                return 'Deliver Order'
            }
        }
    },
    methods: {
        getOrder(){
            this.loading = true
            axios.get(`/admin_get_order/${this.order}`).then((res) => {
                this.loading = false
                this.orders = res.data

                this.orders.forEach(item => {
                    item.cost = item.units * item.product.price
                });
            })
        },
        getSummary(){
            // this.loading2 = true
            axios.get(`/admin_get_summary/${this.$route.params.id}`).then((res) => {
                // this.loading2 = false
                this.summary = res.data
                console.log(res.data);
                this.user = res.data.user
                this.getOrderHistory()
            })
        },
        delOrder(item, index){
            this.confirmDelete = true
            this.orderToDel =  item
            this.orderToDelIndex = index
        },
        deleteOrder(){
            this.orders.splice(this.orderToDelIndex, 1)
            axios.post('/admin_delete_order/', {
                order: this.orderToDel.id
            }).then((res) => {
                this.orderDeleted = true
                this.summary.item_count --
            })
            this.confirmDelete = false
        },
        getOrderHistory(){
            axios.get(`/admin_get_users_order_history/${this.user.id}`).then((res) => {
                this.OrderHistory = res.data
            })
        },
        processOrder(){
            this.orderAction = true
            axios.post('/admin_order_actions/' + this.$route.params.id, {
                status: this.summary.order_status
            }).then((res) => {
                console.log(res.data);
                this.orderAction = false
                this.summary.order_status = res.data.order_status
            })
        },
        sendMessage(){
            if(this.message.trim() !== ''){
                axios.post('/admin_send_message', {
                    message: this.message.trim(),
                    user: this.user
                }).then((res) => {
                    // console.log(res.data);
                })
            }
        }
    },
    mounted() {
        this.getOrder()
        this.getSummary()
    },
}
</script>

<style lang="scss" scoped>
    @media screen and(max-width: 700px){
        .content_wrap{
            margin-left: 6rem !important;
        }
    }

    .v-progress-circular{
        justify-content: center !important;
        align-items: center !important;
        left: 50%;
        margin-top: 5rem;
    }
    table th, table td{
        font-size: 90% !important;
    }
    .v-card .history{
        overflow-x: scroll !important;
    }
</style>
