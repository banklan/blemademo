<template>
   <v-app light>
        <nav-drawer-admin></nav-drawer-admin>
        <v-container>
            <v-row>
                <v-col cols="10" offset="1">
                    <div v-if="product" class="title ml-8">Product Management - <span class="subtitle-1"><strong>{{ product.name }}</strong></span></div>
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
                <v-col cols="10" offset="1" md="5">
                    <div class="image_wrap">
                        <v-card light raised elevation="14" min-height="350" class="mx-auto">
                            <v-progress-circular v-if="!product" indeterminate color="#ff3c38" :width="5" :size="30" style="left: 50%; margin-top: 120px"></v-progress-circular>
                            <v-img v-else contain max-height="350" :src="prodImg" transition="scale-transition"></v-img>
                        </v-card>
                        <div class="ml-5 my-3" v-if="!previewImage">
                            <input type="file" style="display: none" ref="file" @change="chooseFile">
                            <v-btn light fab color="primary" @click="openFilePicker"><v-icon>edit</v-icon> </v-btn>
                        </div>
                        <div class="preview" v-if="previewImage">
                            <div class="prev">
                                <v-img :src="imageUrl" height="120" width="160"></v-img>
                            </div>
                            <div class="prev_btns">
                                <v-btn text dark color="#ff3c38" @click.prevent="cancelPreview">Cancel</v-btn>
                                <v-btn raised dark color="primary" :loading="isUploading" @click.prevent="saveImg">Save</v-btn>
                            </div>
                        </div>
                    </div>
                    <!-- {{ product }} -->
                </v-col>
                <v-col cols="10" offset="1" md="5" offset-md="0">
                    <div class="image_wrap">
                        <v-card light raised elevation="14" min-height="350" class="mx-auto">
                            <v-progress-circular v-if="!product" indeterminate color="#ff3c38" :width="5" :size="30" style="left: 50%; margin-top: 120px"></v-progress-circular>
                            <span v-if="product">
                                <v-card-title class="justify-center">
                                    <div class="subtitle-1">{{ product.name }}</div>
                                </v-card-title>
                                <div v-if="!editProduct">
                                    <v-simple-table>
                                        <tr>
                                            <th width="25%">Category:</th>
                                            <td>{{ product.category.name }}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%">Price:</th>
                                            <td>&#8358;{{ product.price | price }}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%">Unit:</th>
                                            <td>{{ product.unit }}</td>
                                        </tr>
                                        <tr>
                                            <th width="27%">Description:</th>
                                            <td width="73%">{{ product.description }}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%">Size:</th>
                                            <td>{{ product.size }}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%">Colour:</th>
                                            <td>{{ product.color }}</td>
                                        </tr>
                                    </v-simple-table>
                                    <v-card-actions class="justify-center">
                                        <v-btn light fab color="primary" @click.prevent="edit"><v-icon>edit</v-icon> </v-btn>
                                    </v-card-actions>
                                </div>
                                <div v-if="editProduct" class="px-5">
                                    <v-text-field label="Name" v-model="editDetails.name" required :counter="60" v-validate="'required|max:60'" :error-messages="errors.collect('name')" data-vv-name="name"></v-text-field>
                                    <v-textarea rows="2" no-resize auto-grow label="Description" v-model="editDetails.description" :counter="100" v-validate="'required|max:100'" :error-messages="errors.collect('description')" data-vv-name="description"></v-textarea>
                                    <v-text-field label="Price" v-model="editDetails.price" required v-validate="'required|decimal'" :error-messages="errors.collect('price')" data-vv-name="price"></v-text-field>
                                    <v-select label="Unit" :items="units" item-text="name" item-value="name" v-model="editDetails.unit" required v-validate="'required'" :error-messages="errors.collect('unit')" data-vv-name="unit"></v-select>
                                    <v-select label="Size" v-model="editDetails.size" :items="sizes" item-text="name" item-value="name"></v-select>
                                    <v-select label="Product Category" v-model="editDetails.category" readonly :items="cats" item-text="name"></v-select>
                                    <v-text-field label="Color" v-model="editDetails.color"></v-text-field>
                                    <v-card-actions class="justify-center py-4">
                                        <v-btn text color="#ff3c38" @click.prevent="cancelUpdate">Cancel</v-btn>
                                        <v-spacer></v-spacer>
                                        <v-btn light color="primary" @click.prevent="updateProds" :loading="isUpdating">Update</v-btn>
                                    </v-card-actions>
                                </div>
                            </span>
                        </v-card>
                    </div>
                </v-col>
                <v-col cols="10" offset="1" md="6">
                     <div class="image_wrap">
                        <v-card light raised elevation="14" min-height="350" class="mx-auto">
                            <v-progress-circular v-if="!product" indeterminate color="#ff3c38" :width="5" :size="30" style="left: 50%; margin-top: 120px"></v-progress-circular>
                            <span v-if="product">
                                <v-card-title>
                                    <div class="subtitle-1">Services for {{ product.name }}</div>
                                    <v-spacer></v-spacer>
                                    <v-btn color="primary" dark @click.prevent="createServiceDialog = true"><v-icon>add</v-icon>Add Service</v-btn>
                                </v-card-title>
                                <v-card-text>
                                    <span v-if="services.length > 0">
                                        <v-list nav light>
                                            <v-list-item-group>
                                                <v-list-item link v-for="(serv, i) in services" :key="i" :to="{name: 'AdminServiceShow', params:{id: serv.id, product: product.id, slug: serv.slug}}">
                                                    <v-list-item-title v-text="serv.name"></v-list-item-title>
                                                    <v-list-item-action><v-btn text color="#ff3c38" @click.prevent="confirmDel(serv, i)"><v-icon>delete</v-icon></v-btn></v-list-item-action>
                                                </v-list-item>
                                            </v-list-item-group>
                                        </v-list>
                                    </span>
                                    <span v-else>
                                        There are no services for this product.
                                    </span>
                                </v-card-text>
                            </span>
                        </v-card>
                     </div>
                </v-col>
            </v-row>
            <v-dialog v-model="createServiceDialog" max-width="500">
                <v-card>
                    <v-card-title class="justify-center">
                        <div class="subtitle-1">Create New Service for this product</div>
                    </v-card-title>
                    <v-card-text>
                        <v-text-field label="Service" v-model="newService.name" :counter="30" required v-validate="'required|max:30'" data-vv-scope="service" :error-messages="errors.collect('service.name')" data-vv-name="name"></v-text-field>
                        <v-textarea rows="2" auto-grow non-resize label="Description" v-model="newService.description" :counter="80" required v-validate="'required|max:80'" data-vv-scope="service" :error-messages="errors.collect('service.description')" data-vv-name="description"></v-textarea>
                        <v-text-field label="Price" v-model="newService.price" required v-validate="'required|decimal'" data-vv-scope="service" :error-messages="errors.collect('service.price')" data-vv-name="price"></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn dark color="#ff3c38" text @click.prevent="cancelService">Cancel</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn dark color="primary" class="px-8" @click.prevent="saveService" :loading="isSaving">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="servDelete" max-width="500" min-height="300">
                <v-card>
                    <v-card-title class="justify-center">Are you sure you want to delete this service?</v-card-title>
                    <v-card-actions>
                        <v-btn text color="#ff3c38" @click="servDelete = false">Cancel</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click.prevent="delServ">Yes, Delete</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-snackbar v-model="serviceCreatedSuccess" :timeout="4000" top color="#44a80f">
               Service has been created!
                <v-btn dark color="green darken-2" @click.prevent="serviceCreatedSuccess = false">Close</v-btn>
            </v-snackbar>
            <v-snackbar v-model="uploadSuccess" :timeout="4000" top color="#44a80f">
                Product image has been updated!
                <v-btn dark color="green darken-2" @click.prevent="uploadSuccess = false">Close</v-btn>
            </v-snackbar>
            <v-snackbar v-model="editSuccess" :timeout="4000" top color="#44a80f">
                Product details has been updated!
                <v-btn dark color="green darken-2" @click.prevent="editSuccess = false">Close</v-btn>
            </v-snackbar>
            <v-snackbar v-model="servDelSuccess" :timeout="4000" top color="#44a80f">
                Service has been deleted!
                <v-btn dark color="green darken-2" @click.prevent="servDelSuccess = false">Close</v-btn>
            </v-snackbar>
        </v-container>
   </v-app>
</template>

<script>
export default {
    data() {
        return {
            id: this.$route.params.product,
            product: null,
            editProduct: false,
            newFile: null,
            previewImage: false,
            imageUrl: null,
            isUploading: false,
            uploadSuccess: false,
            editDetails: {},
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
            editSuccess: false,
            isUpdating: false,
            services: [],
            createServiceDialog: false,
            newService: {
                name: '',
                description: '',
                price: null
            },
            serviceCreatedSuccess: false,
            isSaving: false,
            servDelete: false,
            servToDel: null,
            servIndexToDel: null,
            servDelSuccess: false,
            prodImg: null
        }
    },
    methods: {
        getProduct(){
            axios.get(`/admin_get_product/${this.$route.params.product}`).then((res) => {
                this.product = res.data.prod
                this.prodImg = res.data.img
                console.log(res.data)
            })
        },
        openFilePicker(){
            this.$refs.file.click()
        },
        chooseFile(e){
            const file = e.target.files[0]
            this.newFile = file
             if(file){
                this.previewImage = true
                this.imageUrl = URL.createObjectURL(file)
            }
        },
        cancelPreview(){
            // this.$refs.file.value = ''
            this.imageUrl = '';
            this.previewImage = false;
        },
        saveImg(){
            if(this.newFile != null){
                this.isUploading = true
                const config = {headers: {
                    'Content-Type' : 'multipart/form-data',
                    }
                };

                let form = new FormData();

                form.append('image', this.newFile)

                axios.post(`/admin_update_product_img/${this.$route.params.product}`, form, config).then((res)=>{
                    this.isUploading = false
                    this.product.picture = res.data.picture
                    this.cancelPreview()
                    this.uploadSuccess = true
                })
            }
        },
        edit(){
            this.editProduct = true
            this.editDetails.name = this.product.name
            this.editDetails.description = this.product.description
            this.editDetails.category = this.product && this.product.category.name
            this.editDetails.price = (this.product.price / 100).toFixed(2)
            this.editDetails.unit = this.product.unit
            this.editDetails.size = this.product.size
            this.editDetails.color = this.product.colour
            console.log(this.product.category.name)
        },
        updateProds(){
             this.$validator.validateAll().then((isValid) =>{
                if(isValid){
                    this.isUpdating = true
                    console.log(this.editDetails)
                    axios.post(`/admin_update_product/${this.$route.params.product}`, {
                        product: this.editDetails
                    }).then((res) => {
                        console.log(res.data)
                        this.isUpdating = false
                        this.editSuccess = true
                        this.editProduct = false
                        this.product = res.data
                    })
                }
             })
        },
        cancelUpdate(){
            // this.editDetails = {}
            this.editProduct = false
            this.$validator.reset()
        },
        getCats(){
            axios.get('/admin_get_categories').then((res) => {
                this.cats = res.data
            })
        },
        getServices(){
            axios.get(`/admin_get_prod_services/${this.$route.params.product}`).then((res) => {
                this.services = res.data
            })
        },
        saveService(){
            this.$validator.validateAll('service').then((isValid) =>{
                if(isValid){
                    this.isSaving = true
                    axios.post(`/admin_create_service/${this.$route.params.product}`, {
                        service: this.newService
                    }).then((res) => {
                        console.log(res.data)
                        this.isSaving = false
                        this.cancelService()
                        this.services.push(res.data)
                        this.serviceCreatedSuccess = true
                    })
                }else{
                    console.log("did not pass validation")
                }
            })
        },
        cancelService(){
            this.createServiceDialog = false
            this.newService.name = ""
            this.newService.description = ""
            this.newService.price = null
        },
        confirmDel(serv, i){
            this.servDelete = true
            this.servToDel = serv
            this.servIndexToDel = i
        },
        delServ(){
            this.services.splice(this.servIndexToDel, 1)
            axios.post(`/admin_delete_service/${this.servToDel.id}`).then((res) => {
                this.servDelete = false
                this.servToDel = null
                this.servIndexToDel = null
                this.servDelSuccess = true
            })
        }
    },
    created() {
        this.getProduct()
        this.getCats()
        this.getServices()
    },
}
</script>

<style lang="scss" scoped>
    @media screen and(max-width: 960px){
        .image_wrap{
            width: 80vw !important;
            margin-right: 30px;
            margin-left: 30px;

            .v-card{
                overflow-x: scroll;
            }
        }
    }
    .preview{
        width: 400px;
        margin-top: 15px;
        padding: 10px;
        display: flex;
        flex-direction: column;

        .prev{
            .v-image{
                border-radius: 4px;
                border:1px solid #fff;
            }
        }
        .prev_btns{
            width: 100%;
            margin-top: 15px;
            margin-bottom: 10px;
        }
    }
</style>

