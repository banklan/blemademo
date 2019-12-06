<template>
    <v-app>
        <v-container>
            <v-row>
                <v-col cols="12" md="8" offset-md="2">
                    <v-card light raised elevation="14" min-height="350" class="mx-auto py-4 px-5 how_it_works">
                        <v-card-title class="justify-center headline font-weight-light"> How it Works</v-card-title>
                        <v-card-text class="card_text">
                            <div class="title" style="color: #4a4a4a">
                                For listed items:
                            </div>
                            <ol class="list1">
                                <li class="list_item">Sign into app to make orders.</li>
                                <li class="list_item">Select the category of the item you want to order, choose item, unit and add service for items that has additional services.</li>
                                <li class="list_item">Add to cart</li>
                                <li class="list_item">Click on the cart and proceed to checkout</li>
                                <li class="list_item">Choose to make payment or Pay on delivery</li>
                            </ol>
                            <v-spacer></v-spacer>
                            <div class="title" style="color: #4a4a4a">
                                To order for items/meals not listed on the app:
                            </div>
                            <ol class="list2">
                                <li class="list_item">Sign into the app.</li>
                                <li class="list_item">Go to the <a href="/special_order">special order </a>page.</li>
                                <li class="list_item">Fill out the form giving full details of what you are ordering for.</li>
                                <li class="list_item">Wait for a call or an email from us informing you of the cost of your order.</li>
                                <li class="list_item">Confirm your acceptance to us and make payment for your order.</li>
                                <li class="list_item">Receive your order after 24 hours.</li>
                            </ol>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>

<script>
export default {
    data() {
        return {

        }
    },
}
</script>

