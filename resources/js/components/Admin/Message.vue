<template>
    <v-app light>
        <v-row>
            <nav-drawer-admin></nav-drawer-admin>
            <v-container>
            <v-row>
                <v-col cols="10" offset="1" class="content_wrap">
                    <v-row>
                        <v-col cols="12">
                            <div class="subtitle-1" v-if="user">
                                Messages with {{ user.name }}
                            </div>
                        </v-col>
                    </v-row>
                    <v-divider></v-divider>
                    <v-row>
                        <v-col cols="12">
                            <v-btn color="#ff3c38" dark raised rounded ripple @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
                        </v-col>
                        <v-col cols="12" md="8" offset-md="2">
                            <v-progress-circular v-if="!user" indeterminate color="orange" :width="5" :size="50" class="justify-center align-center"></v-progress-circular>
                            <v-card v-else light elevation="12" raised min-height="300" class="pa-3">
                                <div class="messages" v-chat-scroll="{always: false, smooth: true}">
                                    <div v-for="(msg, index) in messages" :key="index" class="msg_wrap">
                                        <div>
                                            <span :class="msg.self_owned ? 'self' : 'admins'">{{ msg.sender_name }}</span>: {{ msg.message}}<span class="float-right grey--text lighten-2 time">{{ msg.time }}</span>
                                        </div>
                                    </div>
                                    <div class="message_form mt-4 mb-2">
                                        <v-textarea v-model="text" rows="3" auto-grow outlined no-resize placeholder="Type messages here..." @keyup.enter="sendMessage"></v-textarea>
                                        <v-btn class="px-5 mx-5" raised elevation="12" large dark color="#ff383c" :loading="sending" @click.prevent="sendMessage">Send</v-btn>
                                    </div>
                                </div>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
            </v-container>
        </v-row>
    </v-app>
</template>

<script>
export default {
    data(){
        return{
            user: null,
            userId: this.$route.params.userId,
            userSlug: this.$route.params.userSlug,
            text: '',
            messages: [],
            sending: false
        }
    },
    methods: {
        getUser(){
            axios.get('/admin_get_user/' + this.$route.params.userId).then((res) => {
                this.user = res.data
            })
        },
        getMessages(){
            axios.get('/admin_get_messages/' + this.userId).then((res) => {
                this.messages = res.data
                res.data.forEach(msg => {
                    if(msg.sender_id == 1000){
                        msg.sender_name = 'Me'
                    }
                });
                // console.log(res.data);
            })
        },
        sendMessage(){
            if(this.text.trim() !== ''){
                this.messages.push({
                    sender_name: 'Me',
                    message: this.text.trim(),
                    self_owned: true,
                    time: this.$moment().fromNow()
                })

                // persist to db
                axios.post('/admin_post_messages', {
                    message: this.text.trim(),
                    receiver: this.userId
                }).then((res) => {
                    // console.log(res.data)
                })
                this.text = ''
            }
        },
        changeMessageStatus(){
            axios.get('/admin_change_messages_status/' + this.userId).then((res)=>{
                // console.log(res.data);
            })
        }
    },
    mounted(){
        this.getUser();
        this.getMessages()
        this.changeMessageStatus()
    }
}
</script>


<style lang="scss" scoped>
    @media screen and(max-width: 700px){
        .content_wrap{
            margin-left: 6rem !important;
        }
    }
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
