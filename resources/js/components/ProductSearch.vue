<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-text-field label="Search for Product..." append-icon="search" v-model="q" @keyup.enter="searchForProd"></v-text-field>
        </v-flex>
    </v-layout>
</template>

<script>
export default {
    data() {
        return {
            q: '',
            query: this.$route.query.q
        }
    },
    watch: {
        '$route.query.q': {
            handler(newVal){
                // this.$router.push({name: 'ProductSearchResult', query:{q: newVal}})
            }
        }
    },
    methods: {
        searchForProd(){
            if(this.q.trim() !== ""){
                this.$router.push({name: 'ProductSearchResult', query:{q: this.q}})
            }
        }
    },
}
</script>
