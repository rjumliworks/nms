<template>
    <Head title="Cash Advance"/>
    <PageHeader title="Cash Advance" pageTitle="Boat Operations" />
    <BRow class="g-3 mb-1">
        <BCol lg="3" v-for="(item,index) in breakdown" v-bind:key="index">
            <div class="card bg-light-subtle shadow-none border mb-0">
                <div class="card-body">
                    <p class="text-muted mb-1 fs-12">{{ item.title }}</p>
                    <h4 class="mb-0">{{ item.total }}</h4>
                    <p class="text-muted mb-0 fs-11">{{ item.count }} record(s)</p>
                </div>
            </div>
        </BCol>
    </BRow>
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-hand-coin-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">List of Cash Advances</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Hull Boat and Service cash advances</p>
                        </div>
                    </div>
                </div>
                <div class="car-body bg-white border-bottom shadow-none">
                    <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                        <b-col lg>
                            <div class="input-group mb-1">
                                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                <input type="text" v-model="filter.keyword" placeholder="Search recipient" class="form-control">
                                <Multiselect class="white" style="width: 15%;" :options="categoryOptions" v-model="filter.category" label="name" :searchable="true" placeholder="All Categories" />
                                <b-button type="button" variant="primary" @click="openCreate">
                                    <i class="ri-add-circle-fill align-bottom me-1"></i> New Cash Advance
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <div class="table-responsive table-card" style="height: calc(100vh - 545px); overflow: auto;">
                        <table class="table align-middle table-striped table-centered mb-0">
                            <thead class="table-light thead-fixed">
                                <tr class="fs-11">
                                    <th>Recipient</th>
                                    <th>Category</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-center">Status</th>
                                    <th>Date</th>
                                    <th style="width: 8%;"></th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-12">
                                <tr v-for="(list,index) in lists" v-bind:key="index">
                                    <td>{{ list.employee ? list.employee.name : '-' }}</td>
                                    <td><span class="badge bg-info-subtle text-info">{{ list.category ? list.category.name : '-' }}</span></td>
                                    <td class="text-end">{{ list.amount }}</td>
                                    <td class="text-center">
                                        <span v-if="list.is_cancelled" class="badge bg-danger">Cancelled</span>
                                        <span v-else-if="list.is_paid" class="badge bg-success">Paid</span>
                                        <span v-else class="badge bg-warning">Unpaid</span>
                                    </td>
                                    <td>{{ list.created_at }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <b-button v-if="!list.is_paid && !list.is_cancelled" size="sm" variant="soft-success" @click="pay(list)" type="button">
                                                Pay Now
                                            </b-button>
                                            <a @click="openUpdate(list)" class="btn btn-ghost-primary btn-icon btn-sm" role="button">
                                                <i class="ri-edit-2-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!lists.length">
                                    <td colspan="6" class="text-center text-muted">No cash advances found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <Pagination class="ms-2 me-2 mt-n1" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
                </div>
            </div>
        </div>
    </BRow>
    <Create :categories="categories" :names="names" ref="create" @update="fetch"/>
</template>
<script>
import _ from 'lodash';
import Create from './Modals/Create.vue';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Multiselect, Create },
    props: {
        categories: { type: Array, default: () => [] },
        names: { type: Object, default: () => ({}) },
        breakdown: { type: Array, default: () => [] }
    },
    data(){
        return {
            lists: [],
            meta: {},
            links: {},
            filter: {
                keyword: null,
                category: null
            }
        }
    },
    computed: {
        categoryOptions(){
            if(this.categories && this.categories.length){
                return this.categories;
            }
            return [ { value: 'Hull Boat', name: 'Hull Boat' }, { value: 'Service', name: 'Service' } ];
        }
    },
    created(){
        this.fetch();
    },
    watch: {
        "filter.keyword"(){
            this.checkSearchStr();
        },
        "filter.category"(){
            this.fetch();
        }
    },
    methods: {
        checkSearchStr: _.debounce(function(){
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/cash-advances';
            axios.get(page_url, {
                params: {
                    options: 'lists',
                    keyword: this.filter.keyword,
                    category: this.filter.category,
                    counts: 10
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;
                }
            })
            .catch(err => console.log(err));
        },
        pay(loan){
            axios.post('/cash-advances', {
                id: loan.id,
                option: 'pay'
            })
            .then(() => {
                this.fetch();
            })
            .catch(err => console.log(err));
        },
        openCreate(){
            this.$refs.create.show();
        },
        openUpdate(data){
            this.$refs.create.edit(data);
        }
    }
}
</script>
