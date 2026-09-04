<template>
    <b-modal v-model="showModal" size="lg" header-class="p-3 bg-light" title="New Carrier" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <InputLabel for="carrier" value="Carrier" :message="form.errors.carrier_id"/>
                    <NameSearch v-model="carrier" type="Carrier" :options="names.Carrier || []" placeholder="Search carrier/truck"/>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-1 mb-1"/></BCol>
                <BCol lg="12">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0">Tubs</h6>
                        <b-button size="sm" variant="soft-primary" @click="addRow()" type="button">
                            <i class="ri-add-line align-bottom me-1"></i> Add Tub
                        </b-button>
                    </div>
                    <div v-for="(row,index) in form.tubs" v-bind:key="index" class="d-flex gap-2 align-items-start mb-2">
                        <div style="width: 45%;">
                            <NameSearch v-model="row.fish" type="Fish" :options="names.Fish || []" placeholder="Select fish"/>
                        </div>
                        <div style="width: 22%;">
                            <TextInput v-model="row.quantity" type="number" class="form-control" placeholder="Quantity" :light="true"/>
                        </div>
                        <div style="width: 22%;">
                            <TextInput v-model="row.amount" type="number" class="form-control" placeholder="Amount" :light="true"/>
                        </div>
                        <div class="flex-shrink-0 pt-1">
                            <b-button size="sm" variant="soft-danger" @click="removeRow(index)" type="button" :disabled="form.tubs.length === 1">
                                <i class="ri-delete-bin-line"></i>
                            </b-button>
                        </div>
                    </div>
                    <div class="text-danger fs-12" v-if="form.errors.tubs">{{ form.errors.tubs }}</div>
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
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import NameSearch from '../../Shared/NameSearch.vue';
export default {
    // Creates a Carrier plus its Tub batch in one call, from within the Trip view.
    components: { InputLabel, TextInput, NameSearch },
    props: {
        names: { type: Object, default: () => ({}) }
    },
    data(){
        return {
            form: useForm({
                trip_id: null,
                carrier_id: null,
                tubs: [ { fish_id: null, fish: null, quantity: null, amount: null } ]
            }),
            carrier: null,
            showModal: false
        }
    },
    watch: {
        carrier(newVal){
            this.form.carrier_id = newVal ? newVal.value : null;
        },
        'form.tubs': {
            deep: true,
            handler(rows){
                rows.forEach(row => {
                    row.fish_id = row.fish ? row.fish.value : null;
                });
            }
        }
    },
    methods: {
        show(trip){
            this.form.trip_id = trip.id;
            this.showModal = true;
        },
        addRow(){
            this.form.tubs.push({ fish_id: null, fish: null, quantity: null, amount: null });
        },
        removeRow(index){
            if(this.form.tubs.length > 1){
                this.form.tubs.splice(index, 1);
            }
        },
        submit(){
            this.form.post('/carriers', {
                preserveScroll: true,
                onSuccess: () => {
                    this.$emit('created', true);
                    this.hide();
                }
            });
        },
        hide(){
            this.form.reset();
            this.form.clearErrors();
            this.form.tubs = [ { fish_id: null, fish: null, quantity: null, amount: null } ];
            this.carrier = null;
            this.showModal = false;
        }
    }
}
</script>
