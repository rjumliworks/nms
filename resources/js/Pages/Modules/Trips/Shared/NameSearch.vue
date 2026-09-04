<template>
    <Multiselect
        v-model="proxyValue"
        :options="filteredOptions"
        label="name"
        object
        :searchable="true"
        :placeholder="placeholder"
    />
</template>
<script>
import Multiselect from "@vueform/multiselect";
export default {
    // Reusable "search a list_names record" picker, parameterized by `type`
    // (Boat, Carrier, Truck, Buyer, Person, Fish). No dedicated /names search
    // route exists yet, so this filters a plain array of {id,name,type}
    // records passed down via the `options` prop instead of hitting an API.
    components: { Multiselect },
    props: {
        type: { type: String, default: null },
        modelValue: { type: Object, default: null },
        options: { type: Array, default: () => [] },
        placeholder: { type: String, default: 'Search...' }
    },
    emits: ['update:modelValue'],
    computed: {
        filteredOptions(){
            return (this.options || [])
                .filter(item => !this.type || !item.type || item.type === this.type)
                .map(item => ({ value: item.id, name: item.name }));
        },
        proxyValue: {
            get(){
                return this.modelValue;
            },
            set(val){
                this.$emit('update:modelValue', val);
            }
        }
    }
}
</script>
