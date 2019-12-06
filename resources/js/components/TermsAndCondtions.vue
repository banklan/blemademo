<template>
    <v-app>
        <v-container>
            <v-row>
                <v-col cols="12" md="8" offset-md="2">
                    <v-card light raised elevation="14" min-height="350" class="mx-auto py-4 px-5 how_it_works">
                        <v-card-title class="justify-center title font-weight-light"> Our Terms & Conditions</v-card-title>
                        <v-card-text class="card_text">
                            <ol class="black--text">
                                <li>Blema Groceries is opened for business 24hours daily</li>
                                <li>Blema groceries is strictly an online grocery store. However, there is the option for grocery pick-up in our outlet. </li>
                                <li>All orders will be delivered to customer's doorsteps once payment has been confirmed</li>
                                <li>No grocery pickup/delivery on sundays.</li>
                                <li>All orders sent after 4pm will be delivered morning of the following working day. </li>
                                <li>Grocery pickups are available as early as 30 minutes (after order has been validated) at our office.</li>
                                <li>Your order invoices shall be sent to your email</li>
                                <li>Your payments validate your orders. Orders will only be delivered after payment is confirmed.</li>
                                <li>Standard flat rate is for packages less than 10kg. Extra delivery fees will be charged on packages above 10kg.</li>
                                <li>Please send proof of payments to <span class="email">blemadeliveries@gmail.com</span> or our whatsapp number 080...</li>
                            </ol>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>

<style lang="scss" scoped>
    .v-card{
        ol{
            li{
                font-size: 15px !important;
                padding: 10px;
                line-height: 1.6;
                color: #4e4e4e;

                .email{
                    color: rgb(14, 136, 236);
                    font-weight: 400;
                }
            }
        }
    }
</style>

