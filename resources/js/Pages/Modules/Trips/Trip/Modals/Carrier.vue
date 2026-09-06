<template>
    <b-modal v-model="showModal" size="lg" header-class="p-3 bg-light" title="New Carrier" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <InputLabel for="carrier" value="Carrier" :message="form.errors.carrier_id"/>
                    <NameSearch v-model="carrier" type="Carrier" :options="names.Carrier || []" placeholder="Search carrier/truck"/>
                </BCol>
            </BRow>
            <BRow>
                <div class="col-md-12 mt-3 mb-n3">
                    <div class="card bg-light-subtle shadow-none border">
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="ri-archive-fill text-primary fs-18"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 mt-n1 fs-12"><span class="text-body">Tub Allocation</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-11">Catch loaded onto this carrier</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <b-button size="sm" variant="soft-primary" @click="addRow()" type="button">
                                        <i class="ri-add-line align-bottom me-1"></i> Add Tub
                                    </b-button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-white rounded-bottom">
                            <div v-for="(row,index) in form.tubs" v-bind:key="index" class="d-flex gap-2 align-items-start mb-2">
                                <div style="width: 45%;">
                                    <NameSearch v-model="row.fish" type="Fish" :options="names.Fish || []" placeholder="Select fish"/>
                                </div>
                                <div style="width: 250px;">
                                    <TextInput v-model="row.quantity" type="number" class="form-control" placeholder="Quantity" :light="true"/>
                                </div>
                                <div style="width: 400px;">
                                    <Amount @amount="row.amount = $event" :readonly="false"/>
                                </div>
                                <div class="flex-shrink-0">
                                    <b-button variant="soft-danger" @click="removeRow(index)" type="button" :disabled="form.tubs.length === 1">
                                        <i class="ri-delete-bin-line"></i>
                                    </b-button>
                                </div>
                            </div>
                            <p class="text-muted fs-12 mb-0" v-if="!form.tubs.length">No tubs added yet. Click "Add Tub" to add one.</p>
                            <div class="text-danger fs-12" v-if="form.errors.tubs">{{ form.errors.tubs }}</div>
                        </div>
                    </div>
                </div>
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
import Amount from '@/Shared/Components/Forms/Amount.vue';
import NameSearch from '../../Shared/NameSearch.vue';
export default {
    // Creates a Carrier plus its Tub batch in one call, from within the Trip view.
    components: { InputLabel, TextInput, Amount, NameSearch },
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
