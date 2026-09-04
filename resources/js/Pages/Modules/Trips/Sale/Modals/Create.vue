<template>
    <b-modal v-model="showModal" size="lg" header-class="p-3 bg-light" title="New Sale" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="6">
                    <InputLabel for="trip" value="Trip" :message="form.errors.trip_id"/>
                    <select id="trip" v-model="form.trip_id" class="form-select" @change="onTripChange">
                        <option :value="null">Select Trip</option>
                        <option v-for="trip in trips" v-bind:key="trip.id" :value="trip.id">{{ trip.code }} - {{ trip.date }}</option>
                    </select>
                </BCol>
                <BCol lg="6">
                    <InputLabel for="buyer" value="Buyer" :message="form.errors.buyer_id"/>
                    <NameSearch v-model="buyer" type="Buyer" :options="names.Buyer || []" placeholder="Search buyer"/>
                </BCol>
                <BCol lg="6">
                    <InputLabel for="truck" value="Truck (optional)"/>
                    <NameSearch v-model="truck" type="Truck" :options="names.Truck || []" placeholder="Search truck"/>
                </BCol>
            </BRow>
            <BRow class="g-3 mt-1" v-if="rows.length">
                <BCol lg="12"><hr class="text-muted mt-1 mb-1"/></BCol>
                <BCol lg="12">
                    <h6 class="mb-2">Tub Allocation</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light fs-11">
                                <tr>
                                    <th>Fish</th>
                                    <th class="text-center">Available Qty</th>
                                    <th class="text-center">Sell Qty</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="fs-12">
                                <tr v-for="row in rows" v-bind:key="row.tub_id">
                                    <td>{{ row.fish_name }}</td>
                                    <td class="text-center">{{ row.available_quantity }}</td>
                                    <td style="width: 20%;">
                                        <input type="number" class="form-control form-control-sm" v-model="row.quantity" :max="row.available_quantity" min="0"/>
                                    </td>
                                    <td style="width: 20%;">
                                        <input type="number" class="form-control form-control-sm" v-model="row.amount" min="0"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-2">
                        <span class="text-muted fs-12">Total:</span>
                        <span class="fw-semibold fs-14 ms-1">{{ total }}</span>
                    </div>
                    <div class="text-danger fs-12" v-if="form.errors.lines">{{ form.errors.lines }}</div>
                </BCol>
            </BRow>
            <div v-else-if="form.trip_id" class="text-muted fs-12 mt-2">No unsold tubs available for this trip.</div>
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
            form: useForm({
                trip_id: null,
                buyer_id: null,
                truck_id: null,
                lines: []
            }),
            buyer: null,
            truck: null,
            trips: [],
            rows: [],
            showModal: false
        }
    },
    computed: {
        total(){
            return this.rows.reduce((sum, row) => sum + (Number(row.amount) || 0), 0);
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
        onTripChange(){
            this.rows = [];
            if(!this.form.trip_id){
                return;
            }
            const trip = this.trips.find(t => t.id === this.form.trip_id);
            if(!trip){
                return;
            }
            // Build allocation rows from the trip's carriers -> tubs, excluding already sold tubs.
            const tubs = [];
            (trip.carriers || []).forEach(carrier => {
                (carrier.tubs || []).forEach(tub => {
                    if(!tub.is_sold){
                        tubs.push(tub);
                    }
                });
            });
            this.rows = tubs.map(tub => ({
                tub_id: tub.id,
                fish_name: tub.fish ? tub.fish.name : '-',
                available_quantity: tub.quantity,
                quantity: null,
                amount: null
            }));
        },
        show(){
            this.showModal = true;
        },
        submit(){
            this.form.lines = this.rows
                .filter(row => Number(row.quantity) > 0)
                .map(row => ({
                    tub_id: row.tub_id,
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
            this.rows = [];
            this.showModal = false;
        }
    }
}
</script>
