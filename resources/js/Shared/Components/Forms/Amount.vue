<template>
    <input type="text" class="form-control test" v-money="money" v-model.lazy="value" :readonly="readonly" style="min-height: 38.4px !important; background-color: #f5f6f7;">
</template>
<script>
import {VMoney} from 'v-money'
export default {
    props: ['readonly'],
    data(){
        return {
            value: 0,
            money: {
                decimal: '.',
                thousands: ',',
                prefix: '₱',
                precision: 2,
                masked: false
            },
        }
    },
    watch: {
        value: function(val){
            const numeric = typeof val === 'string' ? parseFloat(val.replace(/[^0-9.-]/g, '')) : val;
            this.$emit('amount', isNaN(numeric) ? 0 : numeric);
        }
    },
    methods: {
        empty(){
            this.value = 0;
        },
        emitValue(value){
            const numeric = typeof value === 'string' ? parseFloat(value.replace(/[^0-9.-]/g, '')) : Number(value);
            const safe = isNaN(numeric) ? 0 : numeric;
            const parts = safe.toFixed(this.money.precision).split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, this.money.thousands);
            this.value = this.money.prefix + parts.join(this.money.decimal);
        }
    },
    directives: {money: VMoney},
}
</script>
<style scoped>
input::placeholder {
    font-size: 12px;
}
</style>