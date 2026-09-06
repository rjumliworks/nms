<template>
    <div class="table-responsive table-card" style="height: calc(100vh - 420px); overflow: auto;">
        <table class="table align-middle table-striped mb-0">
            <thead class="bg-primary text-white thead-fixed">
                <tr class="fs-11">
                    <th>Buyer</th>
                    <th>Truck</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Status</th>
                    <th style="width: 6%;"></th>
                </tr>
            </thead>
            <tbody class="fs-12">
                <tr v-for="(list,index) in trip.sales" v-bind:key="index">
                    <td>{{ list.buyer ? list.buyer.name : '-' }}</td>
                    <td>{{ list.truck ? list.truck.name : '-' }}</td>
                    <td class="text-end">{{ formatCurrency(list.total) }}</td>
                    <td class="text-center">
                        <span v-if="!list.buyer" class="badge bg-secondary">Pending</span>
                        <span v-else-if="!list.is_paid" class="badge bg-warning">Pending Payment</span>
                        <span v-else class="badge bg-success">Paid</span>
                    </td>
                    <td class="text-end">
                        <b-button @click="openUpdate(list)" variant="primary" class="me-1" v-b-tooltip.hover title="Edit" size="sm">
                            <i class="ri-edit-2-fill align-bottom"></i>
                        </b-button>
                    </td>
                </tr>
                <tr v-if="!trip.sales || !trip.sales.length">
                    <td colspan="5" class="text-center text-muted">No sales recorded for this trip</td>
                </tr>
            </tbody>
        </table>
    </div>
    <Create :names="names" ref="create" @update="$emit('update')"/>
    <Assign :names="names" ref="assign" @update="$emit('update')"/>
</template>
<script>
import Create from '../../../Sale/Modals/Create.vue';
import Assign from '../../../Sale/Modals/Assign.vue';
export default {
    components: { Create, Assign },
    props: ['trip','names'],
    emits: ['update'],
    methods: {
        openCreate(){
            this.$refs.create.show(this.trip);
        },
        openUpdate(data){
            this.$refs.assign.show(data);
        },
        formatCurrency(value){
            return '₱' + Number(value ?? 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
</script>
