<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Update Sale" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform" v-if="sale">
            <BRow class="g-3">
                <BCol lg="12">
                    <InputLabel value="Trip"/>
                    <p class="fw-semibold mb-0">{{ sale.trip ? sale.trip.code : '-' }}</p>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="buyer" value="Buyer (optional)" :message="form.errors.buyer_id"/>
                    <NameSearch v-model="buyer" type="Buyer" :options="names.Buyer || []" placeholder="Search buyer, or leave blank"/>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="truck" value="Truck (optional)"/>
                    <NameSearch v-model="truck" type="Truck" :options="names.Truck || []" placeholder="Search truck"/>
                </BCol>
                <BCol lg="12" v-if="form.buyer_id">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_paid" v-model="form.is_paid">
                        <label class="form-check-label" for="is_paid">Payment received</label>
                    </div>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import NameSearch from '../../Shared/NameSearch.vue';
export default {
    components: { InputLabel, NameSearch },
    props: {
        names: { type: Object, default: () => ({}) }
    },
    data(){
        return {
            sale: null,
            form: useForm({
                id: null,
                buyer_id: null,
                truck_id: null,
                is_paid: false,
            }),
            buyer: null,
            truck: null,
            showModal: false
        }
    },
    watch: {
        buyer(newVal){
            this.form.buyer_id = newVal ? newVal.value : null;
        },
        truck(newVal){
            this.form.truck_id = newVal ? newVal.value : null;
        }
    },
    methods: {
        show(data){
            this.sale = data;
            this.form.id = data.id;
            this.form.is_paid = !!data.is_paid;
            if(data.buyer){
                this.buyer = { value: data.buyer.id, name: data.buyer.name };
                this.form.buyer_id = data.buyer.id;
            }
            if(data.truck){
                this.truck = { value: data.truck.id, name: data.truck.name };
                this.form.truck_id = data.truck.id;
            }
            this.showModal = true;
        },
        submit(){
            this.form.put(`/sales/${this.form.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    this.$emit('update', true);
                    this.hide();
                }
            });
        },
        handleInput(field){
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
            this.form.clearErrors();
            this.sale = null;
            this.buyer = null;
            this.truck = null;
            this.showModal = false;
        }
    }
}
</script>
