<template>
    <v-app light>
        <nav-drawer-admin></nav-drawer-admin>
        <v-container>
            <v-row>
                <v-col cols="10" offset="1" md="6" offset-md="0">
                    <div class="title ml-8">Categories Management</div>
                </v-col>
                <v-col cols="10" offset="1" md="6" offset-md="0">
                    <div class="mt-n5 ml-8">
                        <v-text-field v-model="search" append-icon="search" label="Search for a category" single-line hide-details @keyup="searchCats"></v-text-field>
                    </div>
                </v-col>
            </v-row>
            <v-divider></v-divider>
            <v-row>
                <v-col cols="10" offset="1">
                    <div class="content_wrap pa-8">
                        <v-card light raised elevation="14" min-height="400" min-width="600" class="pa-4">
                            <v-card-title>
                                <div class="subtitle-1">Categories Table <v-chip>{{ categories.length }}</v-chip></div>
                                <v-spacer></v-spacer>
                                <v-btn dark color="primary" @click="createDialog = true"><v-icon>add</v-icon>Add Category</v-btn>
                            </v-card-title>

                            <v-simple-table fixed-header min-height="300px" class="products_table mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(cat, index) in categories" :key="index">
                                        <td>{{ cat.id }}</td>
                                        <td>{{ cat.name }}</td>
                                        <td><v-btn text small dark color="blue lighten-1" @click.prevent="openEditCat(cat)"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small dark color="#ff3c38" @click.prevent="delCat(cat.id, index)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </v-simple-table>

                            <div class="my-3" v-if="searchMode">
                                <span class="pl-4">
                                    <v-btn dark text color="#ff3c38" @click.prevent="clearSearch"><v-icon>sync</v-icon> &nbsp; Clear Filter</v-btn>
                                </span>
                            </div>
                        </v-card>
                    </div>
                </v-col>
            </v-row>
            <v-dialog v-model="editDialog" max-width="450">
                <v-card>
                    <v-card-title class="justify-center">Edit Category</v-card-title>
                    <v-card-text>
                        <v-text-field label="Name" v-model="editName" required :counter="30" v-validate="'required|max:30'" data-vv-scope="edit" :error-messages="errors.collect('edit.name')" data-vv-name="name"></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text color="#ff3c38" @click.prevent="editDialog = false"> Cancel </v-btn>
                        <v-btn color="primary" dark @click.prevent="update" :loading="isSaving">Update Category</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="createDialog" max-width="450">
                <v-card>
                    <v-card-title class="subtitle-1 justify-center">Add a new Category</v-card-title>
                    <div v-if="createError" class="mx-3">
                        <div class="alert alert-danger pa-3">{{ createError }}</div>
                    </div>
                    <v-card-text>
                        <v-text-field label="Name" v-model="newCat" required :counter="30" v-validate="'required|max:30'" :error-messages="errors.collect('name')" data-vv-name="name"></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text color="#ff3c38" @click.prevent="cancelCreate"> Cancel </v-btn>
                        <v-btn color="primary" dark @click.prevent="create" :loading="isCreating">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="delDialog" max-width="450">
                <v-card>
                    <v-card-title>
                        <div class="subtitle-1">Do you want to delete this category?</div>
                    </v-card-title>
                    <v-card-text>
                        <div class="subtitle-2">This deletes the category and all the products under it. Once deleted, it cannot be recovered.</div>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn dark color="#ff3c38" text @click.prevent="delDialog = false">Cancel</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn dark color="#ff3c38" small @click.prevent="deleteCat">Delete</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-snackbar v-model="createdSuccess" :timeout="4000" top color="#44a80f">
                New category created successfully!
                <v-btn dark color="green darken-2" @click.prevent="createdSuccess = false">Close</v-btn>
            </v-snackbar>
            <v-snackbar v-model="deleteSuccess" :timeout="4000" top color="#44a80f">
                Category has been deleted!
                <v-btn dark color="green darken-2" @click.prevent="deleteSuccess = false">Close</v-btn>
            </v-snackbar>
            <v-snackbar v-model="editSuccess" :timeout="4000" top color="#44a80f">
                Category has been updated!
                <v-btn dark color="green darken-2" @click.prevent="editSuccess = false">Close</v-btn>
            </v-snackbar>
        </v-container>
    </v-app>
</template>

<script>
export default {
    data(){
        return{
            search: '',
            categories: [],
            createDialog: false,
            createdSuccess: false,
            createError: null,
            newCat: '',
            isCreating: false,
            searchMode: false,
            editDialog: false,
            editName: '',
            isSaving: false,
            delDialog: false,
            deleteSuccess: false,
            catToEdit: null,
            editSuccess: false
        }
    },
    methods:{
        toLower(str){
             return str.replace(/\w\S*/g, function(txt){
                return txt.toLowerCase();
            })
        },
        searchCats(){
            if(this.search !== ''){
                const search = this.search;
                const filtered = this.categories.filter(item => this.toLower(item.name).includes(this.toLower(search)))
                this.categories = filtered
            }else{
                this.getCats()
            }
        },
        clearSearch(){
            this.searchMode = false
            this.search = ''
            this.getCats()
        },
        getCats(){
            axios.get('/admin_get_categories').then((res) => {
                this.categories = res.data
            })
        },
        create(){
            this.$validator.validateAll().then((isValid) =>{
                if(isValid){
                    this.isCreating = true

                    axios.post('/admin_create_cat',{
                        cat: this.newCat
                    }).then((res)=>{
                        this.isCreating = false
                        this.categories.unshift(res.data)
                        this.cancelCreate()
                        // this.newcat = ''
                        // this.$validator.reset()
                        // this.createDialog = false
                        this.createdSuccess = true
                    }).catch((err) => {
                        this.isCreating = false
                        this.createError = "Category creation failed. Please try again"
                    })
                }else{
                    this.createError = "Kindly fix error and try again!"
                }
            })
        },
        cancelCreate(){
            this.$validator.reset()
            this.createDialog = false
            this.newCat = ''
        },
        delCat(cat, index){
            this.catToDelete = cat
            this.catIndexToDel = index
            this.delDialog = true
        },
        deleteCat(){
            // console.log(this.catToDelete)
            // axios.post(`/admin_delete_cat/${this.catToDelete}`).then((res) => {
            //     this.categories.splice(this.catIndexToDel, 1)
            //     this.deleteSuccess = true;
            //     this.delDialog = false
            // })
        },
        openEditCat(cat){
            this.editDialog = true
            this.editName = cat.name
            this.catToEdit = cat
        },
        update(){
            this.$validator.validateAll('edit').then((isValid) =>{
                if(isValid){
                    this.isSaving = true
                    axios.post(`/admin_update_categ/${this.catToEdit.id}`, {
                        cat: this.editName
                    }).then((res) => {
                        this.editSuccess = true
                        this.editDialog = false
                        this.isSaving = false
                        this.getCats()
                    })
                }
            })
        }
    },
    mounted() {
        this.getCats()
    },
}
</script>

<style lang="scss" scoped>
@media screen and(max-width: 960px){
    .content_wrap{
        width: 90vw !important;
        margin-right: 40px;
        margin-left: 20px;

        .v-card{
            overflow-x: scroll;
        }
    }
}
</style>

