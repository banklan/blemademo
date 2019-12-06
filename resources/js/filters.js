import Vue from 'vue';

Vue.filter('price', (value)=>{
    let amt = parseFloat(value/100);
    let amount = amt.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits: 2});
    return amount;
});

Vue.filter('truncate', (string, value)=>{
    if(string.length > value){
        return string.substring(0, value) + " ...";
    }else{
        return string;
    }
});

Vue.filter('pluralize', (int, string) => {
    if(parseInt(int) < 2){
        return int + ' ' + string
    }else{
        return int + ' '+ string +'s'
    }
})


