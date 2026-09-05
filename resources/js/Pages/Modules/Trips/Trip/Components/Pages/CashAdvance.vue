<template>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <p class="mb-0 fs-12 text-muted">Cash advances tied to this trip</p>
        <b-button size="sm" variant="primary" @click="openCreate()" type="button">
            <i class="ri-add-circle-fill align-bottom me-1"></i> New Cash Advance
        </b-button>
    </div>
    <div class="table-responsive" style="height: calc(100vh - 560px); overflow: auto;">
        <table class="table table-sm align-middle table-striped mb-0">
            <thead class="table-light thead-fixed">
                <tr class="fs-11">
                    <th>Recipient</th>
                    <th>Category</th>
                    <th class="text-end">Amount</th>
                    <th class="text-center">Status</th>
                    <th style="width: 12%;"></th>
                </tr>
            </thead>
            <tbody class="fs-12">
                <tr v-for="(list,index) in lists" v-bind:key="index">
                    <td>{{ list.employee ? list.employee.name : '-' }}</td>
                    <td><span class="badge bg-info-subtle text-info">{{ list.category ? list.category.name : '-' }}</span></td>
                    <td class="text-end">{{ list.amount }}</td>
                    <td class="text-center">
                        <span v-if="list.is_cancelled" class="badge bg-danger">Cancelled</span>
                        <span v-else-if="list.is_paid" class="badge bg-success">Paid</span>
                        <span v-else class="badge bg-warning">Unpaid</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <b-button v-if="!list.is_paid && !list.is_cancelled" size="sm" variant="soft-success" @click="pay(list)" type="button">
                                Pay
                            </b-button>
                            <a @click="openUpdate(list)" class="text-primary" role="button" title="Edit">
                                <i class="ri-edit-2-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <tr v-if="!lists.length">
                    <td colspan="5" class="text-center text-muted">No cash advances recorded for this trip</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mt-2">
        <Pagination v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Create :categories="categories" :names="names" ref="create" @update="fetch"/>
</template>
<script>
import Create from '../../../CashAdvance/Modals/Create.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Create, Pagination },
    props: ['trip','categories','names'],
    data(){
        return {
            lists: [],
            meta: {},
            links: {}
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch(page_url){
            page_url = page_url || '/cash-advances';
            axios.get(page_url, {
                params: { options: 'lists', trip_id: this.trip.id, counts: 10 }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        pay(loan){
            axios.post('/cash-advances', { id: loan.id, option: 'pay' })
            .then(() => this.fetch())
            .catch(err => console.log(err));
        },
        openCreate(){
            this.$refs.create.show(this.trip);
        },
        openUpdate(data){
            this.$refs.create.edit(data);
        }
    }
}
</script>
