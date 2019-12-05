<template>
    <v-app>
        <v-content>
            <v-container grid-list-sm>
                <v-layout row wrap class="mb-4">
                    <v-flex xs12 sm6 class="mt-4 cart_card">
                        <v-card raised elevation="12" light ripple hover min-height="100" v-if="items.length > 0">
                            <v-card-title class="justify-center">
                                <div class="body-3">My Cart</div>
                            </v-card-title>
                            <v-card-text>
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th>Items <v-chip rounded>{{ items.length }}</v-chip></th>
                                            <th>Units</th>
                                            <th>Price(&#8358;)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in items" :key="index">
                                            <td>{{ item.name }}</td>
                                            <td>{{ item.units }} X {{ item.price | price }}</td>
                                            <td>{{ item.cost | price }}</td>
                                            <td><v-btn text @click.prevent="delItem(index)"><v-icon small color="#ff3c38">delete_outline</v-icon></v-btn></td>
                                        </tr>
                                        <tr v-for="(service, index) in services" :key="'A' + index">
                                            <td>{{ service.type }}</td>
                                            <td>{{ service.units }} X {{ service.price | price }}</td>
                                            <td>{{ service.cost | price }}</td>
                                            <td><v-btn text @click.prevent="delService(index)"><v-icon small color="#ff3c38">delete_outline</v-icon></v-btn></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Cart Total</td>
                                            <td>{{ total | price }}</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Delivery Charges</td>
                                            <td>{{ charges | price }}</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" style="text-align:right">Total(&#8358;)</th>
                                            <td>{{ grandTotal | price }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-card-text>
                            <v-card-actions class="justify-center">
                                <!-- <div class="flex-grow-1"></div> -->
                                <v-btn text color="red" @click.prevent="comfirmEmptyCart = true">Empty Cart</v-btn>
                            </v-card-actions>
                        </v-card>
                         <v-card raised elevation="12" light ripple hover min-height="100" v-else class="pa-5">
                             <v-card-title>
                                 <div class="subtitle-1">My Cart</div>
                             </v-card-title>
                             <v-card-text>
                                 <div class="subtitle-1">
                                     Your cart is empty. You have not added any items to your cart.
                                 </div>
                             </v-card-text>
                         </v-card>
                    </v-flex>
                    <v-flex xs12 sm5 class="mt-4 msg_card" v-if="grandTotal">
                        <v-card raised elevation="12" light ripple hover min-height="150" class="mb-5">
                            <v-card-title class="justify-center">
                                <div class="subtitle-1">Delivery Address / Contact</div>
                            </v-card-title>
                            <v-card-text v-if="auth">
                                <span v-if="contact">
                                    <div v-if="!contact.address" class="body-2 pink--text mb-3">
                                        Please note that you are required to provide your address for the delivery of your order.
                                    </div>
                                    <div class="body-2"> {{ contact && contact.address }}</div>
                                    <div class="body-2">{{ contact && contact.phone }}</div>
                                    <div class="my-3"><v-btn text @click.prevent="editAddress" color="primary" class="pa-2"><v-icon color="primary">edit</v-icon> Update</v-btn></div>
                                </span>
                                <v-progress-circular  v-else class="justify-center" indeterminate color="#ff383c" :width="5" :size="50"></v-progress-circular>
                            </v-card-text>
                            <v-card-text v-if="!auth">
                                Please <a href="/login">login</a> to confirm delivery address and complete this order.
                            </v-card-text>
                        </v-card>
                        <v-card v-if="auth" raised elevation="12" light ripple hover min-height="150" class="mb-5">
                            <v-card-title class="justify-center">
                                <div class="body-1">Do you wish to pass any message across to us?</div>
                                <v-textarea v-model="message" :count="100" hint="Type any message you want to pass to us on this order" rows="3" no-resize placeholder="Type any message you want to pass to us e.g Drop package with gateman"></v-textarea>
                            </v-card-title>
                        </v-card>
                        <v-card raised elevation="12" light ripple hover min-height="150">
                            <v-card-title class="justify-center">
                                <div v-if="auth" class="body-1">How do you wish to make payments for your order?</div>
                                <div v-else class="body-1">Proceed to checkout</div>
                            </v-card-title>
                            <v-card-actions class="justify-center my-3">
                                <v-btn v-if="auth" :loading="loading2" class="btn btn_submit" @click.prevent="openPodDialog = true" style="text-decoration:none">Pay on Delivery</v-btn>
                                <v-btn v-if="auth" :loading="loading" class="btn btn_submit" @click="goToCheckOut" style="text-decoration:none">Checkout</v-btn>
                                <!-- <v-btn v-if="auth" class="btn btn_submit" to="/checkout_confirm">Checkout</v-btn> -->
                                <a href="/login" v-if="!auth" class="btn btn_submit">Login to complete order</a>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
                <v-row justify="center">
                    <v-dialog v-model="editDial" max-width="400">
                        <v-card>
                            <v-card-title class="subtitle-1 justify-center">Update Delivery Address</v-card-title>
                            <v-card-text>
                                <v-textarea v-model="update.address" label="Address" rows="2" :counter="80" auto-grow></v-textarea>
                                <v-text-field v-model="update.phone" label="Phone Number"></v-text-field>
                            </v-card-text>
                            <v-card-actions>
                                <div class="flex-grow-1"></div>
                                <v-btn text dark color="#ff5e5a" @click="cancelUpdate">
                                    Cancel
                                </v-btn>
                                <v-btn dark :disabled="!update.address || !update.phone" :loading="updating" color="#ff5e5a" @click="updateContact">Update</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-row>
                <v-dialog v-model="comfirmEmptyCart" max-width="350">
                    <v-card>
                        <v-card-title>
                            <div class="subtitle-1">Are you sure you want to empty your cart?</div>
                        </v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn text color="#ff3c38" @click.prevent="comfirmEmptyCart = false">Cancel</v-btn>
                            <v-btn dark color="#ff3c38" @click.prevent="emptyCart">Yes, empty the cart</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="openPodDialog" max-width="450">
                    <v-card>
                        <v-card-title class="justify-center">
                            <div class="subtitle-1">Confirm Pay On Delivery!</div>
                        </v-card-title>
                        <v-card-text>
                            <div class="subtitle-1 darken-2">
                                You have chosen to pay on delivery. Please ensure you have the cash for payment ready before our delivery personnel arrives.
                            </div>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-btn text color="#ff3c38" @click.prevent="openPodDialog = false">Cancel</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn dark color="primary" @click.prevent="payOnDel">Submit Order</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-snackbar v-model="orderCompleted" :timeout="5000" top color="#44a80f">
                    Your order has been sent!
                    <v-btn color="white green--text" text @click.prevent="orderCompleted = false">Close</v-btn>
                </v-snackbar>
                <v-snackbar v-model="updateSuccessfull" :timeout="5000" top color="#44a80f">
                    Your address has been updated!
                    <v-btn color="white green--text" text @click.prevent="updateSuccessfull = false">Close</v-btn>
                </v-snackbar>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            loading2: false,
            message: '',
            orderCompleted: false,
            auth: false,
            contact: null,
            editDial: false,
            update: {
                address: '',
                phone: ''
            },
            updating: false,
            updateSuccessfull: false,
            fees: {
                a: null, b: null, c: null, d: null, e: null
            },
            comfirmEmptyCart: false,
            openPodDialog: false
        }
    },
    computed: {
        items(){
            return this.$store.getters.getCart
        },
        services(){
            return this.$store.getters.getServices
        },
        itemsCost(){
            return this.$store.getters.getItemsCost
        },
        servicesCost(){
            return this.$store.getters.getServicesCost
        },
        total(){
            const total = parseFloat(this.itemsCost) + parseFloat(this.servicesCost)
            if(!total){
                return 0
            }else{
                return total;
            }
        },
        grandTotal(){
           var total = parseFloat(this.total) + parseFloat(this.charges)
           localStorage.setItem('grand_total', total)
           return total;
        },
        charges(){
            if(this.total == 0){
                return 0;
            }
            if(this.total > 10000 && this.total < 500000){
                var fees = parseFloat(this.fees.a)
                localStorage.setItem('charges', fees)
                return fees
            }
            if(this.total > 499900 && this.total < 1000000){
                var fees = this.fees.b
                localStorage.setItem('charges', fees)
                return fees
            }
            if(this.total > 990000 && this.total < 2000000){
                var fees = this.fees.c
                localStorage.setItem('charges', fees)
                return fees
            }
            if(this.total > 1999900 && this.total < 5000000){
                var fees = this.fees.d
                localStorage.setItem('charges', fees)
                return fees
            }
            if(this.total >= 5000000){
                var fees = this.fees.e
                localStorage.setItem('charges', fees)
                return fees
            }
        }
    },
    methods: {
        goToCheckOut(){
            axios.get('/gen_trnx_id_for_payment/').then((res) => {
                window.localStorage.setItem('message', this.message)
                window.localStorage.setItem('orderId', res.data)
                window.location.href="/checkout_confirm"
            }).catch((err) => {
                //show a msg that the transaction wasn't successful and should be redone
            })
        },
        emptyCart(){
            this.$store.commit('empty_cart')
            this.comfirmEmptyCart = false
        },
        delItem(index){
            this.items.splice(index, 1)
            this.$store.commit('update_items', this.items)
        },
        delService(indice){
            this.services.splice(indice, 1)
            this.$store.commit('update_services', this.services)
        },
        payOnDel(){
            if(this.items.length > 0){
                // console.log(this.services)
                this.loading2 = true
                axios.post('/pay_ondelivery', {
                    orders: this.items,
                    services: this.services,
                    charges: this.charges,
                    total: this.grandTotal,
                    message: this.message.trim()
                }).then((res) => {
                    this.orderCompleted = true
                    this.openPodDialog = false
                    // this.loading2 = false
                    localStorage.removeItem('items')
                    localStorage.removeItem('items_cost')
                    localStorage.removeItem('charges')
                    localStorage.removeItem('services')
                    localStorage.removeItem('services_cost')
                    localStorage.removeItem('grand_total')

                    // send mail here
                    axios.post(`/send_orderreceived_emails/${res.data.id}`, {
                        order: res.data
                    }).then((res) => {
                        console.log(res.data);
                    })
                })
                setInterval(() => {
                    window.location.href = '/home';
                    // this.$router.push({path: '/kitchen'})
                }, 2000);
            }
        },
        getContact(){
            if(this.auth)
            axios.get('/get_delivery_address').then((res) => {
                this.contact = res.data
                console.log(res.data)
            })
        },
        editAddress(){
            this.editDial = true
            this.update.address = this.contact.address
            this.update.phone = this.contact.phone
        },
        updateContact(){
            if(this.update.address !== ''){
                this.updating = true
                axios.post('/update_users_address', {
                    address: this.update.address.trim(),
                    phone: this.update.phone.trim()
                }).then((res) => {
                    // console.log(res.data)
                    this.contact.address = res.data.address
                    this.contact.phone = res.data.phone
                    this.cancelUpdate()
                    this.updateSuccessfull = true
                })
            }
        },
        cancelUpdate(){
            this.update = {},
            this.updating = false
            this.editDial = false
        },
        getOrderCharges(){
            axios.get('/get_order_charges').then((res)=>{
                this.fees.a = res.data[0].fee
                this.fees.b = res.data[1].fee
                this.fees.c = res.data[2].fee
                this.fees.d = res.data[3].fee
                this.fees.e = res.data[4].fee
                console.log(res.data)
            })
        }
    },
    mounted() {
        const auth = window.Laravel.auth
        this.auth = auth
        this.getContact()
        this.getOrderCharges()

    },
}
</script>

<style lang="scss" scoped>
    @media screen and (min-width: 600px){
        .cart_card{
            margin-left: 2rem;
        }
        .msg_card{
            margin-left: 3rem;
        }
    }
    .btn_submit{
        margin-bottom: 2rem;
    }
</style>

<style lang="scss" scoped>
    .v-btn{
        text-transform: none !important;
    }
</style>


