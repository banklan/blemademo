<template>
    <v-app>
        <v-container>
            <v-row>
                <v-col cols="12" md="8" offset-md="2">
                    <v-card light raised elevation="14" min-height="350" class="mx-auto py-4 px-5">
                        <template v-if="!messageSent">
                            <v-card-title class="justify-center headline font-weight-light"> Got an enquiry to make?</v-card-title>
                            <v-card-text>
                                <div class="title py-3" style="color: #4a4a4a" v-if="!sentError">
                                    Kindly use the form below to contact us on any enquiry or questions you need to ask us.
                                    Please fill all the fields.
                                </div>
                                <div v-else class="alert alert-danger">{{ sentError }}</div>
                                <v-divider></v-divider>
                                <div class="contact_form py-3">
                                    <v-text-field label="Your name" v-model="contact.name" required :counter="40" v-validate="'required|max:40'" :error-messages="errors.collect('name')" data-vv-name="name"></v-text-field>
                                    <v-text-field label="Your Email address" type="email" v-model="contact.email" required v-validate="'required|email'" :error-messages="errors.collect('email')" data-vv-name="email"></v-text-field>
                                    <v-text-field label="Your Phone Number" v-model="contact.phone" required v-validate="'required|max:15'" :error-messages="errors.collect('phone')" data-vv-name="phone"></v-text-field>
                                    <v-text-field label="Subject" v-model="contact.subject" required :counter="80" v-validate="'required|max:80'" :error-messages="errors.collect('subject')" data-vv-name="subject"></v-text-field>
                                    <v-textarea label="Your enquiry" rows="4" no-resize auto-grow v-model="contact.message" :counter="240" v-validate="'required|max:240'" :error-messages="errors.collect('message')" data-vv-name="message"></v-textarea>
                                </div>
                            </v-card-text>
                            <v-card-actions class="justify-center">
                                <v-btn dark ripple color="primary" large @click.prevent="sendMessage" :loading="isSending">Send</v-btn>
                            </v-card-actions>
                        </template>
                        <template v-else>
                            <div class="alert alert success white--text subtitle-1">{{ successMsg }}</div>
                        </template>
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
            contact: {
                name: '',
                email: '',
                phone: '',
                subject: '',
                message: ''
            },
            isSending: false,
            messageSent: false,
            sentError: null,
            successMsg: null
        }
    },
    methods: {
        sendMessage(){
            this.$validator.validateAll().then((isValid) =>{
                if(isValid){
                    this.isSending = true
                    axios.post('/send_message_contact', {
                        message: this.contact
                    }).then((res) => {
                        this.isSending = false
                        console.log(res.data)
                        this.messageSent = true
                        this.successMsg = "Thanks for contacting us. Your message has been sent. We will reply you shortly."
                        this.resetFormValidation()
                        //send email
                        this.sendEmail(res.data.id)
                    }).catch((err) => {
                        this.isSending = false
                        this.sentError = 'Message sending failed. Please try again later.'
                    })
                }
            })
        },
        sendEmail(mail){
            axios.post('/send_enquiry_email', {
                msg: mail
            }).then((resp) =>{
                console.log(resp.data)
            }).catch((err) => {
                setInterval(this.sendEmail, 120000)
            })
        },
        resetFormValidation(){
            this.$validator.reset()
        }
    },
}
</script>

