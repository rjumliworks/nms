<template>
    <div class="table-responsive table-card" style="height: calc(100vh - 420px); overflow: auto;">
        <table class="table align-middle table-striped mb-0">
            <thead class="bg-primary text-white thead-fixed">
                <tr class="fs-11">
                    <th>Payee</th>
                    <th>Category</th>
                    <th class="text-end">Amount</th>
                    <th>Date</th>
                    <th style="width: 6%;"></th>
                </tr>
            </thead>
            <tbody class="fs-12">
                <tr v-for="(list,index) in trip.expenses" v-bind:key="index">
                    <td>{{ list.employee ? list.employee.name : '-' }}</td>
                    <td><span class="badge bg-info-subtle text-info">{{ list.category ? list.category.name : '-' }}</span></td>
                    <td class="text-end">{{ formatCurrency(list.amount) }}</td>
                    <td>{{ list.created_at }}</td>
                    <td class="text-end">
                        <a @click="openUpdate(list)" class="text-primary" role="button" title="Edit">
                            <i class="ri-edit-2-fill"></i>
                        </a>
                    </td>
                </tr>
                <tr v-if="!trip.expenses || !trip.expenses.length">
                    <td colspan="5" class="text-center text-muted">No expenses recorded for this trip</td>
                </tr>
            </tbody>
        </table>
    </div>
    <Create :categories="categories" :names="names" ref="create" @update="$emit('update')"/>
</template>
<script>
import Create from '../../../Expense/Modals/Create.vue';
export default {
    components: { Create },
    props: ['trip','categories','names'],
    emits: ['update'],
    methods: {
        openCreate(){
            this.$refs.create.show(this.trip);
        },
        openUpdate(data){
            this.$refs.create.edit(data);
        },
        formatCurrency(value){
            return '₱' + Number(value ?? 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
</script>
