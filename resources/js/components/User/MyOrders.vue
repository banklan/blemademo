<template>
    <v-app light>
        <v-layout row wrap>
            <v-flex xs2 sm1>
                <nav-drawer-user></nav-drawer-user>
            </v-flex>
            <v-flex xs10 sm11>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs10 offset-xs1>
                            <div class="title">My Orders</div>
                        </v-flex>
                    </v-layout>
                    <v-divider></v-divider>
                    <v-layout row wrap>
                        <v-flex xs12 sm8 offset-sm2>
                            <v-progress-circular v-if="loading" indeterminate color="orange" :width="7" :size="70"></v-progress-circular>
                            <div class="table_card">
                                <table class="table table-responsive table-hover table-condensed">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Date</th>
                                            <th>Order #</th>
                                            <th>No of Items</th>
                                            <th>Value(&#8358;)</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(order, i) in Orders" :key="i">
                                            <td>{{ i + 1 }}</td>
                                            <td width="35%">{{ order.short_date }}</td>
                                            <td width="30%">{{ order.order_id }}</td>
                                            <td width="10%">{{ order.item_count }}</td>
                                            <td width="15%">{{ order.value | price }}</td>
                                            <td><v-btn text color="primary" :to="{name: 'MyOrder', params: {id: order.id, orderId: order.order_id}}"><v-icon>visibility</v-icon></v-btn></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
            loading: false,
        }
    },
    computed: {
        Orders(){
            this.loading = false
            return this.$store.getters.getOrders
        }
    },
    mounted() {
        this.$store.dispatch('getOrders')
    },
}
</script>

<style lang="scss" scoped>
    .table_card{
        min-height: 30rem;
        background: #fff;
        color: rgba(0,0,0,.75);
        border-color: #fff;
        justify-content: center !important;
        box-shadow: 0 10px 13px -6px rgba(0,0,0,.2),0 20px 31px 3px rgba(0,0,0,.14),0 8px 38px 7px rgba(0,0,0,.12)!important;
        max-width: 100%;
        outline: none;
        overflow-wrap: break-word;
        text-decoration: none;
        transition-property: box-shadow,opacity;
        white-space: normal;
        border-radius: 6px;
        margin: 15px !important;
        margin-left: 20px !important;

        table{
            padding: 0 15px !important;
            margin: 0 auto !important;
            width: 100%;

            td{
                vertical-align: middle;
            }
        }
    }

    @media screen and(max-width: 960px) {
        .table_card{
            min-width: 100vw !important;
            margin: 0;
            overflow-x: scroll;
        }
    }
</style>

