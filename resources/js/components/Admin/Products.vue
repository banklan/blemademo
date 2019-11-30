<template>
    <v-app light>
        <nav-drawer-admin></nav-drawer-admin>
        <v-container>
            <v-row>
                <v-col cols="10" offset="1" md="4" offset-md="0">
                    <div class="title ml-8">Products Management</div>
                </v-col>
                <v-col cols="10" offset="1" md="4" offset-md="0">
                    <div class="mt-n5 ml-8">
                        <v-select label="Filter By category" :items="cats" item-value="id" item-text="name" v-model="filterItem" @change="filterTable"></v-select>
                    </div>
                </v-col>
                <v-col cols="10" offset="1" md="4" offset-md="0">
                    <div class="mt-n5 ml-8">
                        <v-text-field v-model="search" append-icon="search" label="Search for products" single-line hide-details @keyup.enter="searchProducts"></v-text-field>
                    </div>
                </v-col>
            </v-row>
            <v-divider></v-divider>
            <v-row>
                <v-col cols="10" offset="1">
                    <div class="content_wrap pa-8">
                        <v-card light raised elevation="14" min-height="400" min-width="600" class="pa-4">
                            <v-card-title>
                                <div class="subtitle-1">Products Table <v-chip>{{ products.length }}</v-chip></div>
                                <v-spacer></v-spacer>
                                <v-btn dark color="primary" @click="createDialog = true"><v-icon>add</v-icon>Add Product</v-btn>
                            </v-card-title>

                            <v-simple-table fixed-header min-height="300px" class="products_table mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Price (&#8358;)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(product, index) in products" :key="index">
                                        <td>{{ product.id }}</td>
                                        <td>{{ product.name }}</td>
                                        <td>{{ product.price | price }}</td>
                                        <td width="25%"><v-btn text small dark color="blue lighten-1" :to="{name: 'AdminProductShow', params: {product: product.id, slug: product.slug}}"><v-icon>visibility</v-icon></v-btn> &nbsp; <v-btn small dark color="#ff3c38" @click.prevent="delProduct(product.id, index)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </v-simple-table>
                            <v-card-actions class="my-5" v-if="showPag">
                                <span class="pl-4">
                                    <v-btn color="primary" @click.prevent="getProducts(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                    <v-btn color="primary" @click.prevent="getProducts(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                </span>
                                <span class="pl-8">
                                    Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                                </span>
                            </v-card-actions>
                            <div class="my-3" v-if="searchMode">
                                <span class="pl-4">
                                    <v-btn dark text color="#ff3c38" @click.prevent="clearSearch"><v-icon>sync</v-icon> &nbsp; Clear Filter</v-btn>
                                </span>
                            </div>
                        </v-card>
                    </div>
                </v-col>
            </v-row>
            <v-dialog v-model="createDialog" max-width="550">
                <v-card>
                    <v-card-title class="subtitle-1 justify-center">Add a new Product</v-card-title>
                    <div v-if="createError" class="mx-3">
                        <div class="alert alert-danger pa-3">{{ createError }}</div>
                    </div>
                    <v-card-text>
                        <v-text-field label="Fullname" v-model="newProduct.name" required :counter="60" v-validate="'required|max:60'" :error-messages="errors.collect('name')" data-vv-name="name"></v-text-field>
                        <v-textarea rows="2" no-resize auto-grow label="Description" v-model="newProduct.description" :counter="100" v-validate="'required|max:100'" :error-messages="errors.collect('description')" data-vv-name="description"></v-textarea>
                        <v-text-field label="Price" v-model="newProduct.price" required v-validate="'required|decimal'" :error-messages="errors.collect('price')" data-vv-name="price"></v-text-field>
                        <v-select label="Unit" :items="units" item-text="name" item-value="name" v-model="newProduct.unit" required v-validate="'required'" :error-messages="errors.collect('unit')" data-vv-name="unit"></v-select>
                        <v-select label="Size" v-model="newProduct.size" :items="sizes" item-text="name" item-value="name"></v-select>
                        <v-select label="Product Category" v-model="newProduct.cat" :items="cats" item-text="name" item-value="id" v-validate="'required'" :error-messages="errors.collect('cat')" data-vv-name="cat"></v-select>
                        <v-text-field label="Color" v-model="newProduct.color"></v-text-field>
                        <v-file-input label="Upload image" ref="file" clearable @change="chooseFile" accept="image/*" :loading="isUploading" prepend-icon="camera"></v-file-input>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text color="#ff3c38" @click.prevent="cancelCreate"> Cancel </v-btn>
                        <v-btn color="primary" dark @click.prevent="create" :loading="isLoading">Create Product</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="delDialog" max-width="350">
                <v-card>
                    <v-card-title>
                        <div class="subtitle-1">Do you want to delete the product?</div>
                    </v-card-title>
                    <v-card-text>
                        <div class="subtitle-2">Once deleted, the product cannot be recovered.</div>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn dark color="#ff3c38" text @click.prevent="delDialog = false">Cancel</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn dark color="#ff3c38" small @click.prevent="deleteProd">Delete</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-snackbar v-model="createdSuccess" :timeout="4000" top color="#44a80f">
                New product created successfully!
                <v-btn dark color="green darken-2" @click.prevent="createdSuccess = false">Close</v-btn>
            </v-snackbar>
            <v-snackbar v-model="deleteSuccess" :timeout="4000" top color="#44a80f">
                Product has been deleted!
                <v-btn dark color="green darken-2" @click.prevent="deleteSuccess = false">Close</v-btn>
            </v-snackbar>
        </v-container>
    </v-app>
</template>

<script>
export default {
    data(){
        return{
            search: '',
            products: [],
            createDialog: false,
            newProduct: {
                name: '',
                description: '',
                unit: '',
                price: null,
                color: '',
                size: '',
                cat: null,
                file: ''
            },
            isLoading: false,
            createdSuccess: false,
            createError: null,
            isUploading: false,
            pagination: {},
            units: [
                {name: 1}, {name: 'Basket'}, {name: 'Per Pack'}, {name: 'Per Unit'},{name: 'Tuber'},
                {name: 'Kg'}, {name: '5kg'}, {name: '10kg'}, {name: '20kg'}, {name: '50kg'}, {name: '100kg'} ,
                {name: 'Big Bunch'}, {name: 'Medium Bunch'}, {name: 'Small Bunch'}, {name: 'Full'},
                {name: 'Big Pot'}, {name: 'Small Pot'}
            ],
            sizes: [
                {name: 'Big'}, {name: 'Medium'}, {name: 'Small'}
            ],
            cats: [],
            showPag: true,
            searchMode: false,
            filterItem: '',
            prodToDelete: null,
            prodIndexToDel: null,
            delDialog: false,
            deleteSuccess: false
        }
    },
    methods:{
        searchProducts(){
            if(this.search !== ''){
                this.showPag = false
                this.searchMode = true
                axios.post('/admin_search_products', {
                    q: this.search
                }).then((res) => {
                    this.products = res.data
                })
            }
        },
        clearSearch(){
            this.searchMode = false
            this.showPag = true
            this.search = ''
            this.getProducts()
        },
        getCats(){
            axios.get('/admin_get_categories').then((res) => {
                this.cats = res.data
            })
        },
        getProducts(pag){
            pag = pag || '/admin_get_products'
            // console.log(pag);

            axios.get(pag).then((res) => {
                this.products = res.data.data
                // console.log(res.data)
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    from_page: res.data.from,
                    to_page: res.data.to,
                    total: res.data.total,
                    path_page: res.data.path+"?page=",
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        },
        chooseFile(e){
            this.isUploading = true
            this.newProduct.file = e
            this.isUploading = false
        },
        create(){
            this.$validator.validateAll().then((isValid) =>{
                if(isValid && this.newProduct.file !== ''){
                    this.isLoading = true
                    const config = {headers: {
                        'Content-Type' : 'multipart/form-data',
                        }
                    };

                    let form = new FormData();
                    let vm = this

                    form.append('name', vm.newProduct.name)
                    form.append('description', vm.newProduct.description)
                    form.append('unit', vm.newProduct.unit)
                    form.append('price', vm.newProduct.price)
                    form.append('color', vm.newProduct.color)
                    form.append('size', vm.newProduct.size)
                    form.append('cat', vm.newProduct.cat)
                    form.append('file', vm.newProduct.file)
                    // console.log(form);

                    axios.post('/admin_create_product', form, config).then((res)=>{
                        // console.log(res.data);
                        this.products.unshift({
                            name: res.data.name,
                            price: res.data.price
                        })
                        this.isLoading = false
                        this.cancelCreate()
                        this.createdSuccess = true
                    }).catch((err) => {
                        this.isLoading = false
                        this.createError = "Product creation failed. Please try again"
                    })
                }else{
                    this.createError = "Kindly fix all errors and upload an image for the product"
                }
            })
        },
        cancelCreate(){
            this.$validator.reset()
            this.newProduct.name = ''
            this.newProduct.description = ''
            this.newProduct.unit = ''
            this.newProduct.price = null
            this.newProduct.color = ''
            this.newProduct.size = ''
            this.newProduct.cat = null
            this.newProduct.file = ''
            this.createDialog = false
        },
        filterTable(){
            axios.get(`/admin_filter_products_by_cats/${this.filterItem}`).then((res)=>{
                this.products = res.data
                this.showPag = false
                this.searchMode = true
            })
        },
        delProduct(product, index){
            this.prodToDelete = product
            this.prodIndexToDel = index
            this.delDialog = true
        },
        deleteProd(){
            axios.get(`/admin_delete_product/${this.prodToDelete}`).then((res) => {
                this.products.splice(this.prodIndexToDel, 1)
                this.deleteSuccess = true;
                this.delDialog = false
            })
        }
    },
    mounted() {
        this.getProducts();
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

