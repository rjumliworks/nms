<template>
    <b-modal v-model="showModal" size="lg" header-class="p-3 bg-light" title="New Sale" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="6">
                    <InputLabel for="trip" value="Trip" :message="form.errors.trip_id"/>
                    <Multiselect :options="tripOptions" label="name" v-model="trip" object :searchable="true" placeholder="Select Trip"/>
                </BCol>
                <BCol lg="6">
                    <InputLabel for="carrier" value="Carrier"/>
                    <Multiselect :options="carrierOptions" label="name" v-model="carrier" object :searchable="true" :disabled="!carrierOptions.length" placeholder="Select Carrier"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="buyer" value="Buyer" :message="form.errors.buyer_id"/>
                    <NameSearch v-model="buyer" type="Buyer" :options="names.Buyer || []" placeholder="Search buyer"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="truck" value="Truck (optional)"/>
                    <NameSearch v-model="truck" type="Truck" :options="names.Truck || []" placeholder="Search truck"/>
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
                                            <i class="ri-shopping-bag-fill text-primary fs-18"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 mt-n1 fs-12"><span class="text-body">Tub Allocation</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-11">Catch sold to buyers per trip</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <b-button size="sm" variant="soft-primary" @click="addRow()" type="button" :disabled="!canAddRow">
                                        <i class="ri-add-line align-bottom me-1"></i> Add Tub
                                    </b-button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-white rounded-bottom">
                            <div v-for="(row,index) in rows" v-bind:key="index" class="d-flex gap-2 align-items-start mb-2">
                                <div    style="width: 100%;">
                                    <Multiselect v-model="row.tub" :options="tubOptions(index)" label="name" object :searchable="true" placeholder="Select tub">
                                        <template v-slot:singlelabel>
                                            <div class="multiselect-single-label">{{ tubDisplayLabel(row) }}</div>
                                        </template>
                                    </Multiselect>
                                </div>
                                <div style="width: 250px;">
                                    <TextInput v-model="row.quantity" type="number" class="form-control" placeholder="Sell Qty" :max="tubQuantity(row)" min="0" :light="true"/>
                                </div>
                                <div style="width: 400px;">
                                    <Amount @amount="row.amount = $event" :readonly="false"/>
                                </div>
                                <div class="flex-shrink-0">
                                    <b-button variant="soft-danger" @click="removeRow(index)" type="button">
                                        <i class="ri-delete-bin-line"></i>
                                    </b-button>
                                </div>
                            </div>
                            <p class="text-muted fs-12 mb-0" v-if="!rows.length">No tubs added yet. Click "Add Tub" to pick one.</p>
                        </div>
                    </div>
                </div>
            </BRow>
            <!-- <BRow class="g-3 mt-1" v-if="selectedCarrierId">
                <BCol lg="12"><hr class="text-muted mt-1 mb-1"/></BCol>
                <BCol lg="12">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0">Tub Allocation</h6>
                        <b-button size="sm" variant="soft-primary" @click="addRow()" type="button" :disabled="!canAddRow">
                            <i class="ri-add-line align-bottom me-1"></i> Add Tub
                        </b-button>
                    </div>
                    <div v-for="(row,index) in rows" v-bind:key="index" class="d-flex gap-2 align-items-start mb-2">
                        <div    style="width: 100%;">
                            <Multiselect v-model="row.tub" :options="tubOptions(index)" label="name" object :searchable="true" placeholder="Select tub">
                                <template v-slot:singlelabel>
                                    <div class="multiselect-single-label">{{ tubDisplayLabel(row) }}</div>
                                </template>
                            </Multiselect>
                        </div>
                        <div style="width: 250px;">
                            <TextInput v-model="row.quantity" type="number" class="form-control" placeholder="Sell Qty" :max="tubQuantity(row)" min="0" :light="true"/>
                        </div>
                        <div style="width: 400px;">
                            <TextInput v-model="row.amount" type="number" class="form-control" placeholder="Amount" min="0" :light="true"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button variant="soft-danger" @click="removeRow(index)" type="button">
                                <i class="ri-delete-bin-line"></i>
                            </b-button>
                        </div>
                    </div>
                    <p class="text-muted fs-12 mb-0" v-if="!rows.length">No tubs added yet. Click "Add Tub" to pick one.</p>
                    <div class="text-end mt-2" v-if="rows.length">
                        <span class="text-muted fs-12">Total:</span>
                        <span class="fw-semibold fs-14 ms-1">{{ total }}</span>
                    </div>
                    <div class="text-danger fs-12" v-if="form.errors.lines">{{ form.errors.lines }}</div>
                </BCol>
            </BRow>
            <div v-else-if="form.trip_id" class="text-muted fs-12 mt-2">Select a carrier to allocate its tubs.</div> -->
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Amount from '@/Shared/Components/Forms/Amount.vue';
import NameSearch from '../../Shared/NameSearch.vue';
export default {
    components: { InputLabel, TextInput, Amount, NameSearch, Multiselect },
    props: {
        names: { type: Object, default: () => ({}) }
    },
    data(){
        return {
            form: useForm({
                trip_id: null,
                buyer_id: null,
                truck_id: null,
                lines: []
            }),
            buyer: null,
            truck: null,
            carrier: null,
            trip: null,
            trips: [],
            rows: [],
            showModal: false
        }
    },
    computed: {
        total(){
            return this.rows.reduce((sum, row) => sum + (Number(row.amount) || 0), 0);
        },
        tripOptions(){
            return this.trips.map(t => ({ value: t.id, name: `${t.code} - ${t.date}` }));
        },
        selectedTrip(){
            return this.trips.find(t => t.id === this.form.trip_id) || null;
        },
        selectedCarrierId(){
            return this.carrier ? this.carrier.value : null;
        },
        carrierOptions(){
            const seen = new Map();
            (this.selectedTrip?.carriers || []).forEach(carrier => {
                const identityId = carrier.carrier_id;
                if(identityId && !seen.has(identityId)){
                    seen.set(identityId, { value: identityId, name: carrier.carrier ? carrier.carrier.name : '-' });
                }
            });
            return Array.from(seen.values());
        },
        availableTubs(){
            if(!this.selectedCarrierId){
                return [];
            }
            const tubs = [];
            (this.selectedTrip?.carriers || [])
                .filter(c => c.carrier_id === this.selectedCarrierId)
                .forEach(c => (c.tubs || []).forEach(tub => {
                    if(!tub.is_sold){
                        tubs.push(tub);
                    }
                }));
            return tubs;
        },
        canAddRow(){
            return this.rows.length < this.availableTubs.length;
        }
    },
    watch: {
        buyer(newVal){
            this.form.buyer_id = newVal ? newVal.value : null;
        },
        truck(newVal){
            this.form.truck_id = newVal ? newVal.value : null;
        },
        trip(newVal){
            this.form.trip_id = newVal ? newVal.value : null;
            this.carrier = null;
            this.rows = [];
        },
        carrier(){
            this.rows = [];
        },
        rows: {
            deep: true,
            handler(rows){
                rows.forEach(row => this.clampQuantity(row));
            }
        }
    },
    mounted(){
        this.fetchTrips();
    },
    methods: {
        fetchTrips(){
            axios.get('/trips', { params: { options: 'lists', counts: 50 } })
            .then(response => {
                this.trips = response.data.data;
            })
            .catch(err => console.log(err));
        },
        tubOptions(index){
            const chosenIds = this.rows
                .filter((row, i) => i !== index && row.tub)
                .map(row => row.tub.value);
            return this.availableTubs
                .filter(tub => !chosenIds.includes(tub.id))
                .map(tub => ({ value: tub.id, name: `${tub.fish ? tub.fish.name : '-'} — ${tub.quantity} available` }));
        },
        tubQuantity(row){
            const tub = row.tub ? this.availableTubs.find(t => t.id === row.tub.value) : null;
            return tub ? tub.quantity : null;
        },
        clampQuantity(row){
            const max = Number(this.tubQuantity(row));
            if(row.tub && !isNaN(max) && row.quantity !== null && row.quantity !== '' && Number(row.quantity) > max){
                row.quantity = String(max);
            }
        },
        tubDisplayLabel(row){
            const tub = row.tub ? this.availableTubs.find(t => t.id === row.tub.value) : null;
            if(!tub){
                return row.tub ? row.tub.name : '';
            }
            const remaining = Math.max(0, Number(tub.quantity) - (Number(row.quantity) || 0));
            const fishName = tub.fish ? tub.fish.name : '-';
            return `${fishName} — ${remaining} available`;
        },
        addRow(){
            this.rows.push({ tub: null, quantity: null, amount: null });
        },
        removeRow(index){
            this.rows.splice(index, 1);
        },
        show(trip){
            this.showModal = true;
            if(trip){
                if(!this.trips.find(t => t.id === trip.id)){
                    this.trips.push(trip);
                }
                this.trip = { value: trip.id, name: `${trip.code} - ${trip.date}` };
            }
        },
        submit(){
            this.rows.forEach(row => this.clampQuantity(row));
            this.form.lines = this.rows
                .filter(row => row.tub && Number(row.quantity) > 0)
                .map(row => ({
                    tub_id: row.tub.value,
                    quantity: row.quantity,
                    amount: row.amount
                }));
            this.form.post('/sales', {
                preserveScroll: true,
                onSuccess: () => {
                    this.$emit('update', true);
                    this.hide();
                }
            });
        },
        hide(){
            this.form.reset();
            this.form.clearErrors();
            this.buyer = null;
            this.truck = null;
            this.carrier = null;
            this.trip = null;
            this.rows = [];
            this.showModal = false;
        }
    }
}
</script>
