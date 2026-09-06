<template>
    <div class="table-responsive table-card" style="height: calc(100vh - 420px); overflow: auto;">
        <table class="table align-middle table-striped mb-0">
            <thead class="bg-primary text-white thead-fixed">
                <tr class="fs-11">
                    <th>Recipient</th>
                    <th>Category</th>
                    <th class="text-end">Amount</th>
                    <th class="text-end">Balance</th>
                    <th class="text-center">Status</th>
                    <th style="width: 12%;"></th>
                </tr>
            </thead>
            <tbody class="fs-12">
                <tr v-for="(list,index) in trip.loans" v-bind:key="index">
                    <td>{{ list.employee ? list.employee.name : '-' }}</td>
                    <td><span class="badge bg-info-subtle text-info">{{ list.category ? list.category.name : '-' }}</span></td>
                    <td class="text-end">{{ formatCurrency(list.amount) }}</td>
                    <td class="text-end">{{ list.is_cancelled ? '-' : formatCurrency(list.balance) }}</td>
                    <td class="text-center">
                        <span v-if="list.is_cancelled" class="badge bg-danger">Cancelled</span>
                        <span v-else-if="list.is_paid" class="badge bg-success">Paid</span>
                        <span v-else-if="list.paid_amount > 0" class="badge bg-info">Partial</span>
                        <span v-else class="badge bg-warning">Unpaid</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <b-button v-if="!list.is_paid && !list.is_cancelled" size="sm" variant="soft-success" @click="openPayment(list)" type="button">
                                Pay
                            </b-button>
                            <a v-if="list.paid_amount > 0" @click="openPayment(list)" class="text-secondary" role="button" title="View payment history">
                                <i class="ri-history-line"></i>
                            </a>
                            <a @click="openUpdate(list)" class="text-primary" role="button" title="Edit">
                                <i class="ri-edit-2-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <tr v-if="!trip.loans || !trip.loans.length">
                    <td colspan="6" class="text-center text-muted">No cash advances recorded for this trip</td>
                </tr>
            </tbody>
        </table>
    </div>
    <Create :categories="categories" :names="names" ref="create" @update="$emit('update')"/>
    <Payment ref="payment" @update="$emit('update')"/>
</template>
<script>
import Create from '../../../CashAdvance/Modals/Create.vue';
import Payment from '../../../CashAdvance/Modals/Payment.vue';
export default {
    components: { Create, Payment },
    props: ['trip','categories','names'],
    emits: ['update'],
    methods: {
        openCreate(){
            this.$refs.create.show(this.trip);
        },
        openUpdate(data){
            this.$refs.create.edit(data);
        },
        openPayment(loan){
            this.$refs.payment.show(loan);
        },
        formatCurrency(value){
            return '₱' + Number(value ?? 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
</script>
