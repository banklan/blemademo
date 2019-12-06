<template>
    <v-app light>
        <v-row>
            <nav-drawer-admin></nav-drawer-admin>
            <v-container>
                <v-row>
                    <v-col cols="10" offset="1" class="content_wrap">
                        <v-row>
                            <v-col cols="12" sm="6">
                                <div class="title">
                                    Messages - Chats
                                </div>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <div class="mt-n6">
                                    <v-text-field v-model="search" append-icon="search" label="Search for users" single-line hide-details @keyup="searchUsers"></v-text-field>
                                </div>
                            </v-col>
                        </v-row>
                        <v-divider></v-divider>
                        <v-row>
                            <v-col cols="12">
                                <div class="table_header">
                                    <div class="subtitle-1"></div>
                                </div>
                                <v-row>
                                    <v-col cols="12" md="10">
                                        <v-simple-table fixed-header min-height="300px" class="order_table">
                                            <template v-slot:default>
                                            <thead>
                                                <tr>
                                                    <th class="text-left">User</th>
                                                    <th class="text-left">Unread Messages</th>
                                                    <th class="text-left">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(user, index) in users" :key="user.id">
                                                    <td>{{ user.name }}</td>
                                                    <td><v-chip dark color="blue lighten-2">{{ user.unread_messages }}</v-chip></td>
                                                    <td><v-btn small dark color="primary" :to="{name: 'AdminMessage', params:{userId: user.id, userSlug: user.slug}}"><v-icon>mail_outline</v-icon></v-btn>&nbsp; <v-btn small dark color="#ff3c38" @click="confirmDel(user, index)"><v-icon>delete_forever</v-icon></v-btn></td>
                                                 </tr>
                                            </tbody>
                                            </template>
                                        </v-simple-table>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
                <v-dialog v-model="confDelDialog" max-width="500">
                    <v-card class="pa-3" v-if="userMsgToDelete">
                        <v-card-title class="justify-center">
                            <div class="subtitle-2">Do you want to delete all messages between you and {{ userMsgToDelete.name }}</div>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn text light color="#ff3c38" @click="confDelDialog = false">Cancel</v-btn>
                            <v-btn light color="primary" @click="deleteMsgs" :loading="isLoading">Yes, Delete</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-container>
        </v-row>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            messages: [],
            search: '',
            users: [],
            confDelDialog: false,
            userMsgToDelete: null,
            indexToDelete: null
        }
    },
    methods: {
        getUsers(){
            axios.get('/admin_get_users').then((res) => {
                let users = res.data.filter(user => user.id != 99)
                this.users = users
            })
        },
        getMessages(){
            this.loading = true
            axios.get('/admin_get_all_messages').then((res) => {
                this.loading = false
                this.messages = res.data
            })
        },
        searchUsers(){
            if(this.search !== ''){
                const search = this.search.trim();
                const filtered = this.users.filter(user => this.toLower(user.name).includes(this.toLower(search)))
                this.users = filtered
            }else{
                this.getUsers()
            }
        },
        toLower(str){
             return str.replace(/\w\S*/g, function(txt){
                return txt.toLowerCase();
            })
        },
        confirmDel(user, index){
            this.confDelDialog = true
            this.userMsgToDelete = user
            this.indexToDelete = index
        },
        deleteMsgs(){
            this.isLoading = true
            let vm = this
            axios.post('/admin_delete_conversations', {
                user: vm.userMsgToDelete.id
            }).then((res)=>{console.log(res.data)
                vm.isLoading = false
                vm.confDelDialog = false
            })
        }
    },
    mounted() {
        this.getUsers()
        this.getMessages()
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
        top: 50% !important;
        left: 50%;
        margin-top: 10rem;
    }

    .table_header{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;

        .subtitle-1{
            flex: 0 1 50%;
            padding-left: 2rem;
        }
        .v-text-field{
            flex: 0 1 50%;
            margin-top: -25px !important;
        }
    }
    .order_table{
        margin-top: 2rem;
        border: 1px solid #fff !important;
        box-shadow: 0 10px 13px -6px rgba(0,0,0,.2),0 20px 31px 3px rgba(0,0,0,.14),0 8px 38px 7px rgba(0,0,0,.12)!important;
        overflow-x: scroll;
    }

    @media screen and(max-width: 960px) {
        .order_table{
            min-width: 100vw !important;
            margin-left: -2rem !important;
            overflow-x: scroll;
        }
    }
</style>
