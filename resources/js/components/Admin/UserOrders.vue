<template>
    <v-app light>
        <nav-drawer-admin></nav-drawer-admin>
        <v-container>
            <v-row>
                <v-col cols="10" offset="1">
                    <div v-if="user" class="title ml-8">Users Management - All Orders for <span class="subtitle-1"><strong>{{ user.name }}</strong></span></div>
                </v-col>
            </v-row>
            <v-divider></v-divider>
            <v-row>
                <v-col cols="10" offset="1">
                    <div class="ml-8">
                        <v-btn color="#ff3c38" dark raised rounded ripple @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
                    </div>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="10" offset="1">
                    <div class="content_wrap">
                    <v-card light raised elevation="14" min-height="300" min-width="800" class="pa-4">
                        <v-simple-table>
                            <template v-slot:default>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Order Id</th>
                                        <th>No Of Items</th>
                                        <th>Value</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="order in summaries" :key="order.id">
                                        <td>{{ order.date }}</td>
                                        <td>{{ order.order_id }}</td>
                                        <td>{{ order.item_count }}</td>
                                        <td>{{ order.value }}</td>
                                        <td>{{ order.status }}</td>
                                        <td><v-btn :to="{name: 'AdminOrder', params: {order: order.order_id, id:order.id}}" text color="blue" small><v-icon>visibility</v-icon></v-btn>&nbsp; </td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </v-card>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>

<script>
export default {
    data(){
        return{
            user: null,
            summaries: []
        }
    },
    methods: {
        getUser(){
            axios.get(`/admin_get_user/${this.$route.params.user}`).then((res)=>{
                this.user = res.data
            })
        },
        getOrders(){
            axios.get(`/admin_get_users_orders/${this.$route.params.user}`).then((res)=>{
                this.summaries = res.data
            })
        },

    },
    mounted() {
        this.getUser()
        this.getOrders()
    },
}
</script>

<style lang="scss" scoped>
    @media screen and(max-width: 620px) {
        .content_wrap{
            margin-left: 30px;
        }
    }
    @media screen and(max-width: 620px) {
        .content_wrap{
            overflow-x: scroll !important;
        }
    }
</style>

