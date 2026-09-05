<template>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <p class="mb-0 fs-12 text-muted">Catch sold to buyers for this trip</p>
        <b-button size="sm" variant="primary" @click="openCreate()" type="button">
            <i class="ri-add-circle-fill align-bottom me-1"></i> New Sale
        </b-button>
    </div>
    <div class="table-responsive" style="height: calc(100vh - 560px); overflow: auto;">
        <table class="table table-sm align-middle table-striped mb-0">
            <thead class="table-light thead-fixed">
                <tr class="fs-11">
                    <th>Buyer</th>
                    <th>Truck</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody class="fs-12">
                <tr v-for="(list,index) in lists" v-bind:key="index">
                    <td>{{ list.buyer ? list.buyer.name : '-' }}</td>
                    <td>{{ list.truck ? list.truck.name : '-' }}</td>
                    <td class="text-end">{{ list.total }}</td>
                    <td class="text-center">
                        <span v-if="list.is_sold" class="badge bg-success">Sold</span>
                        <span v-else class="badge bg-secondary">Pending</span>
                    </td>
                </tr>
                <tr v-if="!lists.length">
                    <td colspan="4" class="text-center text-muted">No sales recorded for this trip</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mt-2">
        <Pagination v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Create :names="names" ref="create" @update="fetch"/>
</template>
<script>
import Create from '../../../Sale/Modals/Create.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Create, Pagination },
    props: ['trip','names'],
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
            page_url = page_url || '/sales';
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
        openCreate(){
            this.$refs.create.show(this.trip);
        }
    }
}
</script>
