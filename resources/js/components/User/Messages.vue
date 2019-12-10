<template>
    <v-app light>
        <v-row>
            <nav-drawer-user></nav-drawer-user>
            <v-col cols="10" offset="1">
                <v-row>
                    <v-col cols="10" offset="1">
                        <div class="title ml-4 pl-3 page_title">My Messages</div>
                    </v-col>
                </v-row>
                <v-divider></v-divider>
                <v-row class="ml-5">
                    <v-col cols="12" class="pa-5">
                        <div class="title">Please feel free to send us messages concerning your orders or any general enquiry</div>
                        <v-divider></v-divider>
                        <v-row>
                            <v-col cols="12" sm="8">
                                <div class="messages" v-chat-scroll="{always: false, smooth: true}">
                                    <div v-for="(msg, index) in messages" :key="index" class="msg_wrap">
                                        <div>
                                            <span :class="msg.self_owned ? 'self' : 'admins'">{{ msg.sender_name }}</span>: {{ msg.message}}<span class="float-right grey--text lighten-2 time">{{ msg.time }}</span>
                                        </div>
                                    </div>
                                    <div class="message_form mt-4 mb-2">
                                        <v-textarea v-model="text" rows="2" auto-grow outlined no-resize placeholder="Type messages here..." @keyup.enter="sendMessage"></v-textarea>
                                        <v-btn class="px-5 mx-5" raised elevation="12" large dark color="#ff383c" :loading="sending" @click.prevent="sendMessage">Send</v-btn>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-app>
</template>

<script>
import moment from 'vue-moment';

export default {
    data() {
        return {
            messages: [],
            text: '',
            sending: false
        }
    },
    methods: {
        sendMessage(){
            if(this.text.trim() !== ''){
                this.messages.push({
                    sender_name: 'Me',
                    message: this.text.trim(),
                    self_owned: true,
                    time: this.$moment().fromNow()
                })

                //persist to db
                axios.post('/post_user_messages', {
                    message: this.text.trim()
                }).then((res) => {
                    // console.log(res.data)
                })
                this.text = ''
            }
        },
        getMessages(){
            //get messages and reload page
            axios.get('/get_user_messages').then((res)=>{
                this.messages = res.data
            })
        },
        resetUnread(){
            axios.get('/reset_unread_messages').then((res) => {
                // console.log(res.data)
            })
        }
    },
    mounted() {
        this.getMessages()
        this.resetUnread()
    },
}
</script>

<style lang="scss" scoped>
    .messages{
        min-height: 5rem;
        max-height: 25rem;
        overflow-y: scroll;
        border: ipx solid #0000001f;
        padding: 20px;
        border-radius: 6px;
        background: #fff;
        box-shadow: 0 7px 8px -4px rgba(0,0,0,.2),0 12px 17px 2px rgba(0,0,0,.14),0 5px 22px 4px rgba(0,0,0,.12)!important;

        .msg_wrap{
            padding: 12px 8px !important;
            overflow: hidden;
            min-height: 3rem;
            line-height: 1.6;

            &:not(:last-child){
                border-bottom: 1px solid #0000001f;
            }

            .time{
                margin-bottom: 10px !important;
            }
        }
    }
    .self{
        color: #15c5c5;
        font-weight: 400 !important;
    }
    .admins{
        color: tomato;
        font-weight: 400 !important;
        font-style: italic;
    }
</style>


