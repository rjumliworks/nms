<template>
    <Head title="Expenses"/>
    <PageHeader title="Expenses" pageTitle="Boat Operations" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-file-list-3-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">List of Expenses</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Hull Boat and Carrier expenses across all trips</p>
                        </div>
                    </div>
                </div>
                <div class="car-body bg-white border-bottom shadow-none">
                    <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                        <b-col lg>
                            <div class="input-group mb-1">
                                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                <input type="text" v-model="filter.keyword" placeholder="Search payee" class="form-control">
                                <Multiselect class="white" style="width: 15%;" :options="categoryOptions" v-model="filter.category" label="name" :searchable="true" placeholder="All Categories" />
                                <b-button type="button" variant="primary" @click="openCreate">
                                    <i class="ri-add-circle-fill align-bottom me-1"></i> New Expense
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <div class="table-responsive table-card" style="height: calc(100vh - 465px); overflow: auto;">
                        <table class="table align-middle table-striped table-centered mb-0">
                            <thead class="table-light thead-fixed">
                                <tr class="fs-11">
                                    <th>Payee</th>
                                    <th>Category</th>
                                    <th>Trip</th>
                                    <th class="text-end">Amount</th>
                                    <th>Date</th>
                                    <th style="width: 6%;"></th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-12">
                                <tr v-for="(list,index) in lists" v-bind:key="index">
                                    <td>{{ list.name ? list.name.name : '-' }}</td>
                                    <td><span class="badge bg-info-subtle text-info">{{ list.category ? list.category.name : '-' }}</span></td>
                                    <td>{{ list.trip ? list.trip.code : '-' }}</td>
                                    <td class="text-end">{{ list.amount }}</td>
                                    <td>{{ list.created_at }}</td>
                                    <td class="text-end">
                                        <a @click="openUpdate(list)" class="btn btn-ghost-primary btn-icon btn-sm" role="button">
                                            <i class="ri-edit-2-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr v-if="!lists.length">
                                    <td colspan="6" class="text-center text-muted">No expenses found</td>
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
        names: { type: Object, default: () => ({}) }
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
            return [ { value: 'Hull Boat', name: 'Hull Boat' }, { value: 'Carrier', name: 'Carrier' } ];
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
            page_url = page_url || '/expenses';
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
        openCreate(){
            this.$refs.create.show();
        },
        openUpdate(data){
            this.$refs.create.edit(data);
        }
    }
}
</script>
