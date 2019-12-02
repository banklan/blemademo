<template>
    <v-app light>
        <nav-drawer-admin></nav-drawer-admin>
        <v-container>
            <v-row>
                <v-col cols="10" offset="1" md="4" offset-md="1">
                    <div class="title ml-8">Users Management</div>
                </v-col>
                <v-col cols="10" offset="1" md="6">
                    <div class="mt-n5 ml-8">
                        <v-text-field v-model="search" append-icon="search" label="Search for users" single-line hide-details @keyup="searchUsers"></v-text-field>
                    </div>
                </v-col>
            </v-row>
            <v-divider></v-divider>
            <v-row>
                <v-col cols="10" offset="1">
                    <div class="content_wrap pa-8">
                        <v-card light raised elevation="14" min-height="400" min-width="600" class="pa-4">
                            <v-card-title>
                                <div class="subtitle-1">Users Table <v-chip>{{ users.length }}</v-chip></div>
                                <v-spacer></v-spacer>
                                <v-btn dark color="primary" @click="createDialog = true"><v-icon>add</v-icon>Add User</v-btn>
                            </v-card-title>

                            <v-simple-table fixed-header min-height="300px" class="users_table mt-3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, index) in users" :key="index">
                                        <td>{{ user.id }}</td>
                                        <td>{{ user.name }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.phone }}</td>
                                        <td>{{ user.user_status }}</td>
                                        <td width="25%"><v-btn text small dark color="blue lighten-1" :to="{name: 'AdminUser', params: {user: user.id, slug:user.slug}}"><v-icon>visibility</v-icon></v-btn> &nbsp; <v-btn small dark color="#ff3c38" @click.prevent="confirmDelUser(user, index)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </v-simple-table>
                        </v-card>
                    </div>
                </v-col>
            </v-row>
            <v-dialog v-model="createDialog" max-width="550">
                <v-card>
                    <v-card-title class="subtitle-1 justify-center">Create a new User</v-card-title>
                    <div v-if="createError">
                        <div class="alert alert-danger pa-3">{{ createError }}</div>
                    </div>
                    <v-card-text>
                        <v-text-field label="Fullname" v-model="newUser.name" required prepend-icon="people" :counter="60" v-validate="'required|max:60'" :error-messages="errors.collect('name')" data-vv-name="name"></v-text-field>
                        <v-text-field type="Email" label="Email" v-model="newUser.email" required prepend-icon="mail" v-validate="'required|email'" :error-messages="errors.collect('email')" data-vv-name="email"></v-text-field>
                        <v-text-field label="Phone No" v-model="newUser.phone" required prepend-icon="phone" v-validate="'required|numeric|min:8|max:12'" :error-messages="errors.collect('phone')" data-vv-name="phone"></v-text-field>
                        <v-text-field label="Alternate Phone No" v-model="newUser.alt_phone" prepend-icon="phone" v-validate="'numeric|min:8'" :error-messages="errors.collect('alternate_phone')" data-vv-name="alternate_phone"></v-text-field>
                        <v-textarea label="Address" rows="2" auto-grow no-resize v-model="newUser.address" prepend-icon="map" :counter="120" v-validate="'required|max:120'" :error-messages="errors.collect('address')" data-vv-name="address"></v-textarea>
                        <v-select label="Location" v-model="newUser.location" :items="locations" item-text="name" item-value="id" required prepend-icon="map" v-validate="'required'" :error-messages="errors.collect('location')" data-vv-name="location"></v-select>
                        <v-text-field type="password" label="Password" v-model="newUser.password" prepend-icon="lock" required v-validate="'required|min:5|max:30'" :error-messages="errors.collect('password')" data-vv-name="password"></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text color="#ff3c38" @click.prevent="cancelCreate"> Cancel </v-btn>
                        <v-btn color="primary" dark @click.prevent="create" :loading="isLoading">Create User</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="delUserDialog" max-width="450">
                <v-card>
                    <v-card-title class="justify-center">
                        <div class="subtitle-1">Are you sure you want to delete this user?</div>
                    </v-card-title>
                    <v-card-text>
                        Once deleted, user cannot be recovered!
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="#ff3c38" text @click.prevent="delUserDialog = false">Cancel</v-btn>
                        <v-btn color="primary" dark @click.prevent="deleteUser">Delete User</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-snackbar v-model="createdSuccess" :timeout="4000" top color="#44a80f">
                New user created successfully!
                <v-btn color="white green--text" text @click.prevent="createdSuccess = false">Close</v-btn>
            </v-snackbar>
        </v-container>
    </v-app>
</template>

<script>
export default {
    data(){
        return{
            search: '',
            users: [],
            createDialog: false,
            newUser: {
                name: '',
                phone: '',
                email: '',
                location: null,
                alt_phone: null,
                address: '',
                password: ''
            },
            locations: [],
            isLoading: false,
            createdSuccess: false,
            createError: null,
            userToDelete: null,
            userIndexToDel: null,
            delUserDialog: false
        }
    },
    methods:{
        toLower(str){
             return str.replace(/\w\S*/g, function(txt){
                return txt.toLowerCase();
            })
        },
        searchUsers(){
            if(this.search !== ''){
                const search = this.search;
                const filtered = this.users.filter(item => this.toLower(item.name).includes(this.toLower(search)) || item.email.includes(search))
                this.users = filtered
            }else{
                this.getUsers()
            }
        },
        getUsers(){
            axios.get('/admin_get_users').then((res) => {
                this.users = res.data
            })
        },
        getLocations(){
            axios.get('/admin_getAllLocations').then((res)=>{
                this.locations = res.data
            })
        },
        create(){
            this.$validator.validateAll().then((isValid) =>{
                if(isValid){
                    this.isLoading = true
                    axios.post('/admin_create_user', {
                        user: this.newUser
                    }).then((res)=>{
                        console.log(res.data);
                        this.isLoading = false
                        this.createdSuccess = true
                        this.cancelCreate()
                        this.users.unshift(res.data)

                        // send welcome mail to new user
                        axios.post('/admin_send_welcome_mail_to_user', {
                            user: res.data.id
                        }).then((res) => {
                            console.log(res.data);
                        })
                    }).catch((err) => {
                        this.isLoading = false
                        this.createError = "User creation failed. Please try again"
                    })
                }
            })
        },
        cancelCreate(){
            this.$validator.reset()
            this.createDialog = false
            this.newUser.name = ''
            this.newUser.email = ''
            this.newUser.phone = ''
            this.newUser.alt_phone = ''
            this.newUser.address = ''
            this.newUser.location = ''
            this.newUser.password = ''
        },
        confirmDelUser(user, index){
            this.userToDelete = user.id
            this.userIndexToDel = index
            this.delUserDialog = true
        },
        deleteUser(){

        }
    },
    mounted() {
        this.getUsers();
        this.getLocations()
    },
}
</script>

<style lang="scss" scoped>
@media screen and(max-width: 960px){
    .content_wrap{
        width: 90vw !important;
        margin-right: 40px;
        margin-left: 20px;
        // overflow-x: scroll;
        .v-card{
            overflow-x: scroll;
        }
    }
}
</style>

