<template>
    <Head title="Names"/>
    <PageHeader title="Names" pageTitle="Boat Operations" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-contacts-book-2-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">Names</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Boats, carriers, trucks, buyers, persons, and fish used across trips</p>
                        </div>
                    </div>
                </div>
                <div class="car-body bg-white border-bottom shadow-none">
                    <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                        <b-col lg>
                            <div class="input-group mb-1">
                                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                <input type="text" v-model="filter.keyword" placeholder="Search name" class="form-control">
                                <Multiselect class="white" style="width: 15%;" :options="typeOptions" v-model="filter.type" label="name" :searchable="true" placeholder="Select Type" :can-clear="false" />
                                <b-button type="button" variant="primary" @click="openCreate">
                                    <i class="ri-add-circle-fill align-bottom me-1"></i> New Name
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
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th class="text-center">Status</th>
                                    <th style="width: 6%;"></th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-12">
                                <tr v-for="(list,index) in lists" v-bind:key="index">
                                    <td class="fw-semibold">{{ list.name }}</td>
                                    <td>{{ list.type }}</td>
                                    <td class="text-center">
                                        <span v-if="list.is_active" class="badge bg-success">Active</span>
                                        <span v-else class="badge bg-secondary">Inactive</span>
                                    </td>
                                    <td class="text-end">
                                        <a @click="openUpdate(list)" class="text-primary" role="button" title="Edit">
                                            <i class="ri-edit-2-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr v-if="!lists.length">
                                    <td colspan="4" class="text-center text-muted">No names found</td>
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
    <Create :types="types" :default-type="filter.type" ref="create" @update="fetch"/>
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
        types: { type: Array, default: () => ['Boat', 'Carrier', 'Truck', 'Buyer', 'Person', 'Fish'] }
    },
    data(){
        return {
            lists: [],
            meta: {},
            links: {},
            filter: {
                type: 'Boat',
                keyword: null
            }
        }
    },
    created(){
        this.fetch();
    },
    computed: {
        typeOptions(){
            return (this.types || []).map(t => ({ value: t, name: t }));
        }
    },
    watch: {
        "filter.keyword"(){
            this.checkSearchStr();
        },
        "filter.type"(){
            this.fetch();
        }
    },
    methods: {
        checkSearchStr: _.debounce(function(){
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/names';
            axios.get(page_url, {
                params: {
                    options: 'lists',
                    type: this.filter.type,
                    keyword: this.filter.keyword,
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
