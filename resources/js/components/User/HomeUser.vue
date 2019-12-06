<template>
    <v-app light>
        <v-layout row wrap>
            <v-flex xs3 sm1>
                <nav-drawer-user></nav-drawer-user>
            </v-flex>
            <v-flex xs9 sm11>
                <v-container grid-list-sm>
                    <v-row>
                        <v-col cols="12">
                            <div class="title teal--text pa-2">
                                Home
                            </div>
                            <v-divider></v-divider>
                        </v-col>
                    </v-row>
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="8">
                                <v-row>
                                    <v-col cols="12" sm="4">
                                        <v-card dark raised elevation="16" min-height="150" color="#9500a7" href="/my_cart">
                                            <v-card-title class="justify-center">
                                                <div class="subtitle-3 pt-5">My Cart <v-icon class="ml-3">shopping_cart</v-icon></div>
                                            </v-card-title>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" sm="4">
                                        <v-card dark raised elevation="16" min-height="150" color="#ef5800" href="/raw_foodstuffs">
                                            <v-card-title class="justify-center">
                                                <div class="subtitle-3 pt-5">Raw foods <i class="fas fa-cheese ml-3"></i></div>
                                            </v-card-title>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" sm="4">
                                        <v-card dark raised elevation="16" min-height="150" color="#378805" href="/special_order">
                                            <v-card-title class="justify-center">
                                                <div class="subtitle-3 pt-5">Special Orders <v-icon class="ml-3">fastfood</v-icon></div>
                                            </v-card-title>
                                        </v-card>
                                    </v-col>
                                </v-row>
                                <v-row>
                                     <v-col cols="12" sm="4">
                                        <v-card dark raised elevation="16" min-height="150" color="#eac50d" href="/soup-stew-ingredient">
                                            <v-card-title class="justify-center">
                                                <div class="subtitle-3 pt-5">Soup Recipes <i class="fas fa-carrot ml-3"></i></div>
                                            </v-card-title>
                                        </v-card>
                                     </v-col>
                                     <v-col cols="12" sm="4">
                                        <v-card dark raised elevation="16" min-height="150" color="#1b00ff" href="/fish-meat">
                                            <v-card-title class="justify-center">
                                                <div class="subtitle-3 pt-5">Fish & Meat <i class="fas fa-fish ml-3"></i></div>
                                            </v-card-title>
                                        </v-card>
                                     </v-col>
                                     <v-col cols="12" sm="4">
                                        <v-card dark raised elevation="16" min-height="150" color="#b90659eb" href="/groceries">
                                            <v-card-title class="justify-center">
                                                <div class="subtitle-3 pt-5">Groceries <i class=" fas fa-box ml-3"></i></div>
                                            </v-card-title>
                                        </v-card>
                                     </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-card light raised elevation="16" min-height="250" class="mt-4">
                                            <v-progress-circular v-if="loading" indeterminate color="orange" :width="7" :size="50"></v-progress-circular>
                                            <v-card-title class="justify-center">
                                                <div v-if="pendings.length > 0" class="subtitle-1">
                                                    You have {{ pendings.length }} pending order
                                                </div>
                                                <div v-else class="subtitle-1">
                                                    You have no pending order(s)
                                                </div>
                                            </v-card-title>
                                            <v-card-text v-if="pendings.length > 0">
                                                <v-expansion-panels accordion>
                                                    <v-expansion-panel v-for="(order, i) in pendings" :key="i">
                                                        <v-expansion-panel-header>
                                                            {{ order.order_id }} - {{ order.order_date }}
                                                        </v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <div class="primary--text darken--3">{{ order.status }}</div>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" md="4" class="mt-3">
                                <v-progress-circular v-if="loadingFour" indeterminate color="orange" :width="7" :size="50"></v-progress-circular>
                                <v-card v-else light raised elevation="16" min-height="320" class="mr-5">
                                    <v-card-title class="justify-center">
                                        <div v-if="lastFourOrders.length > 0" class="subtitle-1">
                                            Last 4 orders
                                        </div>
                                        <div v-else class="subtitle-1">
                                            You have no orders yet
                                        </div>
                                    </v-card-title>
                                    <v-card-text v-if="lastFourOrders.length > 0">
                                        <v-expansion-panels accordion>
                                            <v-expansion-panel v-for="(order, i) in lastFourOrders" :key="i">
                                                <v-expansion-panel-header>
                                                    {{ order.order_id }} - {{ order.order_date }}
                                                </v-expansion-panel-header>
                                                <v-expansion-panel-content>
                                                    <div class="primary--text darken--3">{{ order.status }}</div>
                                                </v-expansion-panel-content>
                                            </v-expansion-panel>
                                        </v-expansion-panels>
                                    </v-card-text>
                                    <v-card-actions v-if="lastFourOrders.length > 0" class="justify-center">
                                        <v-btn text color="primary" :to="{path: '/my_orders'}">All Orders</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-container>
            </v-flex>
        </v-layout>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            user: null,
            pendings: [],
            orders: [],
            loading: false,
            loadingFour: false,
            special: [],
            lastFourOrders: []
        }
    },
    methods: {
        getLastFourOrders(){
            this.loadingFour = true
            axios.get('/get_last_five_orders').then((res) => {
                this.loadingFour = false
                this.lastFourOrders = res.data
            })
        },
        getPendingOrders(){
            this.loading = true
            axios.get('/get_pending_orders').then((res) => {
                this.loading = false
                this.pendings = res.data
            })
        },
    },
    mounted() {
        if(window.Laravel.auth){
            this.user = window.Laravel.user
        }

        this.getLastFourOrders()
        this.getPendingOrders()
    },
}
</script>

<style lang="scss" scoped>
    a.v-card:hover{
        text-decoration: none !important;
    }
</style>

