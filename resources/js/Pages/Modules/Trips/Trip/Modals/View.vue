<template>
    <b-modal v-model="showModal" size="lg" header-class="p-3 bg-light" :title="`Trip ${trip ? trip.code : ''}`" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop hide-footer>
        <div v-if="trip">
            <BRow class="g-2 mb-3">
                <BCol lg="4"><span class="text-muted fs-12">Boat</span><h6 class="mb-0">{{ trip.boat ? trip.boat.name : '-' }}</h6></BCol>
                <BCol lg="4"><span class="text-muted fs-12">Date</span><h6 class="mb-0">{{ trip.date }}</h6></BCol>
                <BCol lg="4">
                    <span class="text-muted fs-12">Status</span><br/>
                    <span v-if="trip.is_completed" class="badge bg-success">Completed</span>
                    <span v-else-if="trip.has_departed" class="badge bg-warning">Ongoing</span>
                    <span v-else class="badge bg-secondary">Preparing</span>
                </BCol>
                <BCol lg="12" v-if="trip.note"><span class="text-muted fs-12">Note</span><p class="mb-0 fs-13">{{ trip.note }}</p></BCol>
            </BRow>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="mb-0">Carriers</h6>
                <b-button size="sm" variant="primary" @click="$emit('add-carrier', trip)" type="button">
                    <i class="ri-add-circle-fill align-bottom me-1"></i> New Carrier
                </b-button>
            </div>
            <div class="table-responsive mb-3">
                <table class="table table-sm align-middle table-striped mb-0">
                    <thead class="table-light fs-11">
                        <tr>
                            <th>Order</th>
                            <th>Carrier</th>
                            <th class="text-center">Tubs</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody class="fs-12">
                        <tr v-for="(carrier,index) in trip.carriers" v-bind:key="index">
                            <td>{{ carrier.order }}</td>
                            <td>{{ carrier.carrier ? carrier.carrier.name : '-' }}</td>
                            <td class="text-center">{{ carrier.tubs ? carrier.tubs.length : 0 }}</td>
                            <td class="text-end">{{ carrier.total }}</td>
                        </tr>
                        <tr v-if="!trip.carriers || !trip.carriers.length">
                            <td colspan="4" class="text-center text-muted">No carriers yet</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <BRow class="g-2">
                <BCol lg="6">
                    <div class="border rounded p-2 text-center">
                        <span class="text-muted fs-12">Expenses</span>
                        <h6 class="mb-0">{{ trip.expenses ? trip.expenses.length : 0 }}</h6>
                    </div>
                </BCol>
                <BCol lg="6">
                    <div class="border rounded p-2 text-center">
                        <span class="text-muted fs-12">Sales</span>
                        <h6 class="mb-0">{{ trip.sales ? trip.sales.length : 0 }}</h6>
                    </div>
                </BCol>
            </BRow>
        </div>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            trip: null,
            showModal: false
        }
    },
    methods: {
        show(trip){
            this.trip = trip;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>
